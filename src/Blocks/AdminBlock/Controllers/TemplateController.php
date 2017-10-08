<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\TemplateRequest;
use Jet\Models\Template;
use Jet\Models\Website;
use JetFire\Framework\App;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class TemplateController
 * @package Jet\AdminBlock\Controllers
 */
class TemplateController extends AdminController
{

    /**
     * @param TemplateRequest $request
     * @return array
     */
    public function all(TemplateRequest $request)
    {
        $max = ($request->has('length')) ? (int)$request->query('length') : 10;
        $start = ($request->has('start')) ? (int)$request->query('start') : 1;
        $params = [
            'order' => ($request->has('order')) ? $request->query('order') : [],
            'search' => $request->query('search')['value']
        ];
        $response = Template::repo()->listAll($start, $max, $params);
        $templates = [
            'draw' => (int)$request->query('draw'),
            'recordsTotal' => $response['total'],
            'recordsFiltered' => $response['total'],
            'data' => $response['data']
        ];
        return $templates;
    }

    /**
     * @param TemplateRequest $request
     * @return array
     */
    public function create(TemplateRequest $request)
    {
        if ($request->method() == 'POST') {
            $response = $request->validate();
            if ($response === true) {
                $template = $request->filled();
                if (Template::where('name', $template['name'])->count() == 0) {
                    if ($template['type'] == 'file' && !is_file(ROOT . DIRECTORY_SEPARATOR . $request->getRoute()->getTarget('view_dir') . $template['content']))
                        return ['status' => 'error', 'message' => 'Fichier non trouvé'];
                    return (Template::create($template))
                        ? ['status' => 'success', 'message' => 'Le template a bien été créé']
                        : ['status' => 'error', 'message' => 'Le template n\'a pas pu être créé'];
                }
                return ['status' => 'error', 'message' => 'Le template existe déjà'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }


    /**
     * @param $id
     * @return array
     */
    public function read($id)
    {
        $template = Template::findOneById($id);
        return (!is_null($template))
            ? ['status' => 'success', 'resource' => $template]
            : ['status' => 'error', 'message' => 'Template inexistant'];
    }

    /**
     * @param TemplateRequest $request
     * @param $id
     * @return array
     */
    public function update(TemplateRequest $request, $id)
    {
        if ($request->method() == 'PUT') {
            /** @var Template $template */
            $template = Template::findOneById($id);
            if (!is_null($template)) {
                $response = $request->validate();
                if ($response === true) {
                    $values = $request->values();
                    if (Template::where('name', $values['name'])->where('id', '!=', $id)->count() == 0) {
                        if ($values['type'] == 'file' && !is_file(ROOT . DIRECTORY_SEPARATOR . $request->getRoute()->getTarget('view_dir') . $values['content']))
                            return ['status' => 'error', 'message' => 'Fichier non trouvé'];
                        return (Template::update($id, $request->values()))
                            ? ['status' => 'success', 'message' => 'Le template a été mis à jour']
                            : ['status' => 'error', 'message' => 'Le template n\'a pas été mis à jour'];
                    }
                    return ['status' => 'error', 'message' => 'Le nom du template est déjà pris'];
                }
                return $response;
            }
            return ['status' => 'error', 'message' => 'Template inexistant'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param TemplateRequest $request
     * @param $website
     * @param $id
     * @return array
     */
    public function updateOrCreate(TemplateRequest $request, $website, $id = 'create')
    {
        if ($request->method() == 'POST' || $request->method() == 'PUT') {

            /** @var Template $template */
            $template = ($id == 'create') ? new Template() : Template::findOneById($id);
            if (is_null($template)) return ['status' => 'error', 'message' => 'Template inexistant'];

            $response = $request->validate();
            if ($response === true) {
                $values = $request->values();

                if ($values['type'] == 'file' && !is_file($values['path']))
                    return ['status' => 'error', 'message' => 'Fichier non trouvé'];

                /** @var Website $website */
                $website = Website::findOneById($website);
                if(is_null($website)) return ['status' => 'error', 'message' => 'Site web non trouvé'];

                if($id == 'create'){
                    if (Template::where('name', $values['name'])->count() > 0)
                        return ['status' => 'error', 'message' => 'Le template existe déjà'];
                    $template->setWebsite($website);
                    $template->setScope('specified');
                    $template->setType('content');
                    $template->setContent($values['content']);
                    $template->setName($values['name']);
                }
                elseif (!is_null($template) && $template->getWebsite() != $website) {
                    if($template->getName() == $values['name']) $values['name'] = $values['name'] . uniqid();
                    $template = new Template();
                    $template->setWebsite($website);
                    $template->setScope('specified');
                    $template->setType('content');
                    $template->setContent($values['content']);
                    $template->setName($values['name'] . uniqid());
                } else {
                    if ($values['type'] == 'file') {
                        if (!is_file($values['path']))
                            return ['status' => 'error', 'message' => 'Impossible de trouver le template'];
                        if (!file_put_contents($values['path'], $values['content']))
                            return ['status' => 'error', 'message' => 'Impossible d\'écrire dans le fichier : ' . $values['path']];
                    } else
                        $template->setContent($values['content']);
                    $template->setScope($values['scope']);
                    $template->setType($values['type']);
                    $template->setName($values['name']);
                }

                $template->setTitle($values['title']);
                $template->setCategory($values['category']);
                return (Template::watchAndSave($template))
                    ? ['status' => 'success', 'message' => 'Le template a été mis à jour', 'resource' => $template]
                    : ['status' => 'error', 'message' => 'Le template n\'a pas été mis à jour'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param TemplateRequest $request
     * @return array
     */
    public function delete(TemplateRequest $request)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            $templates = Template::findById($request->get('ids'));
            foreach ($templates as $template) {
                if ($template->getType() == 'file' && is_file(($file = ROOT . DIRECTORY_SEPARATOR . $request->getRoute()->getTarget('view_dir') . $template->getContent())))
                    unlink($file);
                Template::removeWatch($template);
            }
            return (Template::save())
                ? ['status' => 'success', 'message' => 'Les templates ont bien été supprimés']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Les templates n\'ont pas pu être supprimés'];
    }


    /**
     * @param $website
     * @return array
     */
    public function getWebsiteLayouts($website)
    {
        return $this->getTemplates($website, 'layout');
    }

    /**
     * @param $website
     * @return array
     */
    public function getWebsiteContentLayouts($website)
    {
        return $this->getTemplates($website, 'partial');
    }

    /**
     * @param $website
     * @return array
     */
    public function getWebsiteStylesheets($website)
    {
        return $this->getTemplates($website, 'stylesheet');
    }

    /**
     * @param $website
     * @return array
     */
    public function listRuleValue($website)
    {
        return $this->getWebsiteLayouts($website);
    }

    /**
     * @param $website
     * @param $category
     * @return array
     */
    private function getTemplates($website, $category){
        if (!$this->getWebsite($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];
        return Template::repo()->getWebsiteTemplates($this->getWebsiteData($this->website), $this->websites, $category);
    }

    /**
     * @param $id
     * @return array
     */
    public function findWithContent($id)
    {
        /** @var Template $template */
        $template = Template::findOneById($id);
        if (is_null($template)) return ['status' => 'error', 'message' => 'Impossible de trouver le template'];

        if ($template->getType() == 'file') {
            $path = '';
            if ($template->getScope() == 'specified') {
                if (is_file(ROOT . '/src/Themes/' . $template->getContent() . $this->app->data['template_extension']))
                    $path = ROOT . '/src/Themes/' . $template->getContent() . $this->app->data['template_extension'];
            } else {
                foreach ($this->app->data['app']['blocks'] as $block) {
                    if (is_file(ROOT . '/' . $block['view_dir'] . $template->getContent() . $this->app->data['template_extension']))
                        $path = ROOT . '/' . $block['view_dir'] . $template->getContent() . $this->app->data['template_extension'];
                }
            }

            return (!empty($path)) ? [
                'status' => 'success',
                'resource' => $template,
                'content' => file_get_contents($path),
                'path' => $path
            ] : ['status' => 'error', 'message' => 'Impossible de trouver le template'];
        }
        return ['status' => 'success', 'resource' => $template, 'content' => $template->getContent(), 'path' => ''];
    }
}