<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\CustomFieldRequest;
use Jet\Models\AdminCustomField;
use Jet\Models\CustomField;
use Jet\Models\CustomFieldRule;
use Jet\Models\Website;
use Jet\AdminBlock\Services\Auth;
use JetFire\Validation\Validation;

/**
 * Class CustomFieldController
 * @package Jet\AdminBlock\Controllers
 */
class CustomFieldController extends AdminController
{

    /**
     * @param CustomFieldRequest $request
     * @param Auth $auth
     * @param $website
     * @return array
     */
    public function all(CustomFieldRequest $request, Auth $auth, $website)
    {
        $max = ($request->exists('max')) ? (int)$request->query('max') : 10;
        $page = ($request->exists('page')) ? (int)$request->query('page') : 1;

        if (!$this->getWebsite($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site web'];

        $params = [
            'websites' => $this->websites,
            'access_level' => $auth->get('status')->level,
            'options' => $this->getWebsiteData($this->website),
            'search' => ($request->has('params') && isset($request->query('params')['search'])) ? $request->query('params')['search'] : '',
            'order' => ($request->has('params') && isset($request->query('params')['order'])) ? $request->query('params')['order'] : [],
            'filter' => ($request->has('params') && isset($request->query('params')['filter'])) ? $request->query('params')['filter'] : [],
        ];

        $response = CustomField::repo()->listAll($page, $max, $params);
        $custom_fields_count = ceil($response['total'] / $max);

        $custom_fields = [
            'current_page' => $page,
            'count_pages' => $custom_fields_count,
            'count_all' => $response['total'],
            'data' => $response['data']
        ];
        return ['status' => 'success', 'content' => $custom_fields];
    }

    /**
     * @param CustomFieldRequest $request
     * @param Auth $auth
     * @param $website
     * @return array
     */
    public function adminRender(CustomFieldRequest $request, Auth $auth, $website)
    {
        if (!$this->isWebsiteOwner($auth, $website))
            return ['status' => 'error', 'message' => 'Vous n\'avez pas les permissions pour voir ces contenus'];

        if ($request->exists('params')) {

            $params = $request->get('params');

            if (!$this->getWebsite($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];

            $params = [
                'rules' => $params,
                'websites' => $this->websites,
                'access_level' => $auth->get('status')->level,
                'options' => $this->getWebsiteData($this->website),
            ];

            return ['status' => 'success', 'resource' => CustomField::repo()->adminRender($params)];
        }
        return ['status' => 'error', 'message' => 'Paramètres non trouvés'];
    }

    /**
     * @param Auth $auth
     * @param $website
     * @param $id
     * @return array
     */
    public function read(Auth $auth, $website, $id)
    {
        if (!$this->isWebsiteOwner($auth, $website))
            return ['status' => 'error', 'message' => 'Vous n\'avez pas les permissions pour voir ces contenus'];

        $website = Website::findOneById($website);
        if (is_null($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];

        $params = [
            'access_level' => $auth->get('status')->level,
            'options' => $this->getWebsiteData($website),
        ];

        $custom_field = CustomField::repo()->read($id, $params);
        return (!is_null($custom_field))
            ? ['status' => 'success', 'resource' => $custom_field]
            : ['status' => 'error', 'message' => 'Champ inexistant'];
    }

    /**
     * @param CustomFieldRequest $request
     * @param Auth $auth
     * @param $website
     * @param $id
     * @return array
     */
    public function updateOrCreate(CustomFieldRequest $request, Auth $auth, $website, $id)
    {
        if ($request->method() == 'PUT' || $request->method() == 'POST') {

            if (!$this->isWebsiteOwner($auth, $website))
                return ['status' => 'error', 'message' => 'Vous n\'avez pas les permissions pour modifier ces contenus'];

            /** @var Website $website */
            $website = Website::findOneById($website);
            if (is_null($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];

            if ($id != 'create') {
                /** @var CustomField $custom_field */
                $custom_field = CustomField::findOneById($id);
                if (is_null($custom_field)) return ['status' => 'error', 'message' => 'Impossible de trouver le champ'];
                $cf_website = $custom_field->getWebsite();
            } else {
                $custom_field = new CustomField();
                $cf_website = $website;
            }

            if (!is_null($custom_field)) {

                $values = $request->get('custom_field');

                if ($cf_website != $website) {
                    $data = $this->excludeData($website->getData(), 'custom_fields', $custom_field->getId());
                    $website->setData($data);
                    Website::watch($website);
                    $custom_field = new CustomField();
                }

                $custom_field->setWebsite($website);
                $custom_field->setTitle($values['title']);

                if (!isset($values['rule']['id'])) {
                    return ['status' => 'error', 'message' => 'Règle non définie'];
                }

                $rule = CustomFieldRule::findOneById($values['rule']['id']);
                if (is_null($rule)) {
                    return ['status' => 'error', 'message' => 'Règle non trouvée'];
                }

                $custom_field->setRule($rule);
                if (!isset($values['operation']) || empty($values['operation'])) {
                    $values['operation'] = null;
                }
                $custom_field->setOperation($values['operation']);

                if (!isset($values['value']) || empty($values['value'])) $values['value'] = null;
                $values['value'] = $this->updateRuleValue($rule, $website, $values['value']);
                $custom_field->setValue($values['value']);

                $response = $this->updateFields($custom_field, $cf_website, $values['fields']);
                if (is_array($response)) {
                    return $response;
                }

                return (CustomField::watchAndSave($custom_field))
                    ? ['status' => 'success', 'message' => 'Les champs ont bien été mis à jour', 'resource' => CustomField::repo()->read($custom_field->getId(), $this->getWebsiteData($cf_website))]
                    : ['status' => 'error', 'message' => 'Les champs n\'ont pas pu être mis à jour'];
            }
            return ['status' => 'error', 'message' => 'Champ introuvable'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param CustomFieldRule $rule
     * @param Website $website
     * @param $value
     * @return mixed
     */
    private function updateRuleValue(CustomFieldRule $rule, Website $website, $value)
    {
        if ($rule->getType() == 'single') {
            $data = $website->getData();
            if (isset($data['parent_replace'][$rule->getReplaceTable()]) && isset($data['parent_replace'][$rule->getReplaceTable()][$value]))
                return $data['parent_replace'][$rule->getReplaceTable()][$value];
        }
        return $value;
    }

    /**
     * @param CustomFieldRequest $request
     * @param Auth $auth
     * @param $website
     * @param $type
     * @param $type_id
     * @return array
     */
    public function updateOrCreateFront(CustomFieldRequest $request, Auth $auth, $website, $type, $type_id)
    {
        if ($request->method() == 'PUT') {
            if ($request->has('custom_fields')) {
                $custom_fields = $request->get('custom_fields');
                $old_content_key = ($request->has('old_content_key')) ? $request->get('old_content_key') : 'value';
                $old_row_key = ($request->has('old_row_key')) ? $request->get('old_row_key') : 'rows';

                /** @var Website $website */
                $website = Website::findOneById($website);
                if (is_null($website)) {
                    return ['status' => 'error', 'message' => 'Impossible de trouver le site'];
                }

                foreach ($custom_fields as $value) {
                    /** @var CustomField $custom_field */
                    $custom_field = CustomField::findOneById($value['id']);
                    if (!is_null($custom_field) && $custom_field->getAccessLevel() >= $auth->get('status')->level) {
                        $cf_website = $custom_field->getWebsite();

                        if ($cf_website != $website) {
                            $data = $this->excludeData($website->getData(), 'custom_fields', $custom_field->getId());
                            $website->setData($data);
                            Website::watch($website);
                            $custom_field = new CustomField();
                            $custom_field->setWebsite($website);
                        }

                        /** @var CustomFieldRule $rule */
                        $rule = CustomFieldRule::findOneById($value['rule']['id']);
                        if (is_null($rule)) {
                            return ['status' => 'error', 'message' => 'Règle non trouvée'];
                        }
                        $custom_field->setRule($rule);
                        $custom_field->setTitle($value['title']);
                        if (!isset($value['operation']) || empty($value['operation'])) {
                            $value['operation'] = null;
                        }
                        $custom_field->setOperation($value['operation']);
                        if (!isset($value['value']) || empty($value['value'])) {
                            $value['value'] = null;
                        }
                        if ($rule->getName() == 'page' || $rule->getName() == 'post') {
                            $value['value'] = $type_id;
                        }
                        $custom_field->setValue($value['value']);

                        $options = ['updateFrom' => 'front', '_type' => $type, '_type_id' => $type_id, 'old_content_key' => $old_content_key, 'old_row_key' => $old_row_key];
                        $response = $this->updateFields($custom_field, $cf_website, $value['fields'], null, $options);
                        if (is_array($response)) {
                            return $response;
                        }

                        CustomField::watch($custom_field);
                    } else {
                        return ['status' => 'error', 'message' => 'Impossible de trouver le champ'];
                    }
                }
                if (CustomField::save()) {
                    $response = $this->adminRender($request, $auth, $website->getId());
                    if ($response['status'] == 'error') {
                        return ['reload' => true];
                    }
                    return ['status' => 'success', 'message' => 'Les champs ont bien été mis à jour', 'resource' => $response['resource']];
                }
                return ['status' => 'error', 'message' => 'Les champs n\'ont pas pu être mis à jour'];
            }
            return [];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param CustomField $custom_field
     * @param Website $cf_website
     * @param $fields
     * @param null $parent
     * @param array $options
     * @return bool|array
     */
    private function updateFields(CustomField $custom_field, Website $cf_website, $fields, $parent = null, $options = [])
    {
        foreach ($fields as $value) {
            $field = (isset($value['id']) && $cf_website == $custom_field->getWebsite())
                ? AdminCustomField::findOneById($value['id'])
                : new AdminCustomField();
            if (is_null($field)) {
                return ['status' => 'error', 'message' => 'Impossible de trouver le champ'];
            }
            $response = $this->updateField($custom_field, $cf_website, $field, $value, $parent, $options);
            if (is_array($response)) {
                return $response;
            }
        }
        return true;
    }

    /**
     * @param CustomField $custom_field
     * @param $cf_website
     * @param AdminCustomField $field
     * @param $value
     * @param $parent
     * @param array $options
     * @return array|bool|void
     */
    private function updateField(CustomField $custom_field, $cf_website, AdminCustomField $field, $value, $parent, $options = array())
    {
        $validation = new Validation();
        $response = $validation->validate([
            $value['title'] => 'required|length:<100',
            $value['name'] => 'required|noWhitespace|lowercase'
        ], [
            'required' => 'Le titre et le nom des champs sont requis',
            'length' => 'Le titre et le nom doivent comporter au plus 30 caractères',
            'noWhitespace' => 'Pas d\'espace pour le nom',
            'lowercase' => 'Le nom doit être en miniscule'
        ]);
        if ($response['valid'] === true) {
            $field->setTitle($value['title']);
            $field->setName($value['name']);
            $field->setDescription($value['description']);
            $field->setType($value['type']);
            $field->setPosition((int)$value['position']);
            if (isset($value['access_level']))
                $field->setAccessLevel((int)$value['access_level']);
            ($value['required'] == 'true')
                ? $field->setRequired(1)
                : $field->setRequired(0);
            $field->setCustomField($custom_field);
            $field->setParent($parent);
            if (isset($options['updateFrom']) && $options['updateFrom'] == 'front' && isset($value['content'])) {
                if ($value['required'] == 'true' && (!is_array($value['content']) || empty($value['content'])))
                    return ['status' => 'error', 'message' => 'Le champ "' . $value['title'] . '" est requis'];
            }
            if (!empty($options) && isset($value['content']) && is_array($value['content']) && !empty($value['content'])) {
                $response = $this->setContent($field, $value, $options);
                if (is_array($response)) {
                    return $response;
                }
            }
            if (isset($value['data'])) $field->setData($value['data']);
            if (isset($value['children']) && !empty($value['children'])) {
                $response = $this->updateFields($custom_field, $cf_website, $value['children'], $field, $options);
                if (is_array($response)) return $response;
            }

            AdminCustomField::watch($field);
            return true;
        }
        return $response;
    }

    /**
     * @param AdminCustomField $field
     * @param $value
     * @param $options
     * @return bool|array
     */
    private function setContent(AdminCustomField $field, $value, $options)
    {
        $content_key = ($options['_type'] == 'value') ? 'value' : $options['_type'] . '@' . $options['_type_id'];
        $row_key = ($options['_type'] == 'value') ? 'rows' : 'rows@' . $options['_type'] . '@' . $options['_type_id'];

        if ($value['type'] == 'repeater') {
            $value['content']['type'] = 'repeater';
            if (!isset($value['content'][$row_key]) && isset($value['content'][$options['old_row_key']])) {
                $value['content'][$row_key] = $value['content'][$options['old_row_key']];
                unset($value['content'][$options['old_row_key']]);
            }
        } else {
            if (!isset($value['content'][$content_key]) && isset($value['content'][$options['old_content_key']])) {
                $value['content'][$content_key] = $value['content'][$options['old_content_key']];
                unset($value['content'][$options['old_content_key']]);
            }
        }

        if (isset($value['content'][$content_key])) {
            $response = $this->validateContent($value, $value['content'][$content_key]);
            if (is_array($response)) {
                return $response;
            }
        }

        $field->setContent($value['content']);

        return true;
    }

    /**
     * @param $value
     * @param $content
     * @return array|bool
     */
    private function validateContent($value, $content)
    {
        if ($value['required'] == 'true') {
            if (empty($content))
                return ['status' => 'error', 'message' => 'Le champ "' . $value['title'] . '" est requis'];
            if (is_array($content))
                foreach ($content as $key => $sub_content) {
                    if (is_array($sub_content)) {
                        $response = $this->validateContent($value, $sub_content);
                        if (is_array($response)) {
                            return $response;
                        }
                    } else
                        if (empty($sub_content)) {
                            return ['status' => 'error', 'message' => 'Le champ "' . $value['title'] . '" est requis'];
                        }
                }
        }
        return true;
    }

    /**
     * @param CustomFieldRequest $request
     * @param $website
     * @return array
     */
    public function delete(CustomFieldRequest $request, $website)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {

            $website = Website::findOneById($website);
            if (is_null($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site web'];
            $data = $website->getData();

            $cfs = CustomField::repo()->findById($request->get('ids'));
            $ids = [];

            foreach ($cfs as $field) {
                if ($field['website']['id'] != $website->getId()) {
                    $data = $this->excludeData($data, 'custom_fields', $field['id']);
                } else {
                    $ids[] = $field['id'];
                }
            }

            $website->setData($data);
            Website::watchAndSave($website);

            return (CustomField::destroy($ids))
                ? ['status' => 'success', 'message' => 'Le(s) champ(s) ont bien été supprimées']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Le(s) champ(s) n\'ont pas pu être supprimées'];

    }

    /**
     * @param CustomFieldRequest $request
     * @param $website
     * @return array
     */
    public function deleteField(CustomFieldRequest $request, $website)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            $ids = [];
            $fields = CustomField::repo()->findFieldsById($request->get('ids'));
            foreach ($fields as $field)
                if ($field['custom_field']['website']['id'] == $website) {
                    $ids[] = $field['id'];
                }

            return (AdminCustomField::destroy($ids))
                ? ['status' => 'success', 'message' => 'Les champs ont bien été supprimés']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return ['resource' => CustomFieldRule::findAll()];
    }

}