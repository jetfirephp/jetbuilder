<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\WebsiteRequest;
use Jet\Models\CustomField;
use Jet\Models\ModuleCategory;
use Jet\Models\Society;
use Jet\Models\Template;
use Jet\Models\Theme;
use Jet\Models\Website;
use Jet\AdminBlock\Services\Auth;

/**
 * Class WebsiteController
 * @package Jet\AdminBlock\Controllers
 */
class WebsiteController extends AdminController
{

    /**
     * @return array
     */
    public function getIntro()
    {
        $intro = (isset($this->app->data['introjs'])) ? $this->app->data['introjs'] : [];
        return $intro;
    }

    /**
     * @param WebsiteRequest $request
     * @param Auth $auth
     * @return array
     */
    public function all(WebsiteRequest $request, Auth $auth)
    {
        $max = ($request->has('length')) ? (int)$request->query('length') : 10;
        $start = ($request->has('start')) ? (int)$request->query('start') : 1;
        $params = [
            'order' => ($request->has('order')) ? $request->query('order') : [],
            'search' => $request->query('search')['value']
        ];

        if ($auth->get('status')->level == 4) $params['account'] = $auth->get('id');

        $response = Website::repo()->listAll($start, $max, $params);
        $websites = [
            'draw' => (int)$request->query('draw'),
            'recordsTotal' => $response['total'],
            'recordsFiltered' => $response['total'],
            'data' => $response['data']
        ];
        return $websites;
    }

    /**
     * @param Auth $auth
     * @param $id
     * @return array
     */
    public function read(Auth $auth, $id)
    {
        if (!$this->isWebsiteOwner($auth, $id)) return ['status' => 'error', 'message' => 'Impossible d\'accéder au contenu d\'un autre site.'];
        $website = Website::findOneById($id);
        if (!is_null($website))
            return ['status' => 'success', 'resource' => $website];
        return ['status' => 'error', 'message' => 'Site web inexistant'];
    }

    /**
     * @param Auth $auth
     * @return array
     */
    public function count(Auth $auth)
    {
        $websites = Website::repo()->listAuthWebsite($auth->get('id'));
        if (!empty($websites))
            return ['status' => 'success', 'resource' => $websites];
        return ['status' => 'error', 'message' => 'Aucuns sites trouvés'];
    }

    /**
     * @param Auth $auth
     * @param $id
     * @return array
     */
    public function getSummary(Auth $auth, $id)
    {
        if (!$this->isWebsiteOwner($auth, $id)) return ['status' => 'error', 'message' => 'Impossible d\'accéder au contenu d\'un autre site.'];
        $website = Website::repo()->getSummary($id);
        if (!is_null($website))
            return ['status' => 'success', 'resource' => $website];
        return ['status' => 'error', 'message' => 'Site web inexistant'];
    }

    /**
     * @param Auth $auth
     * @param WebsiteRequest $request
     * @param $id
     * @return array
     */
    public function update(Auth $auth, WebsiteRequest $request, $id)
    {
        if ($request->method() == 'PUT') {
            if (!$this->isWebsiteOwner($auth, $id)) return ['status' => 'error', 'message' => 'Impossible d\'accéder au contenu d\'un autre site.'];
            $response = $request->validate();
            if ($response === true) {
                /** @var Website $website */
                $website = Website::findOneById($id);
                if (!is_null($website)) {
                    $values = $request->values();
                    if (Website::where('domain', $values['domain'])->where('id', '!=', $id)->count() > 0) return ['status' => 'error', 'message' => 'Le nom de domaine est déjà pris'];
                    $values['society'] = Society::findOneById($values['society']);
                    if (is_null($values['society'])) return ['status' => 'error', 'message' => 'Société non trouvée'];
                    $website->setSociety($values['society']);
                    $values['theme'] = Theme::findOneById($values['theme']);
                    if (is_null($values['theme'])) return ['status' => 'error', 'message' => 'Thème non trouvé'];
                    $website->setTheme($values['theme']);
                    $values['layout'] = Template::findOneById($values['layout']);
                    if (is_null($values['layout'])) return ['status' => 'error', 'message' => 'Template du site non trouvé'];
                    $website->setLayout($values['layout']);
                    $website->setDomain(trim($values['domain'], '/'));
                    $website->setState($values['state']);
                    $website->setRenderSystem($values['render_system']);

                    if(is_string($values['expiration_date'])) {
                        $website->setExpirationDate(\DateTime::createFromFormat('d/m/Y', $values['expiration_date']));
                    }

                    if (isset($values['data']) && $auth->get('status')->level <= 1) {
                        $website->setData(json_encode($values['data']));
                    }

                    return (Website::watchAndSave($website))
                        ? ['status' => 'success', 'message' => 'Le site a bien été mis à jour']
                        : ['status' => 'error', 'message' => 'Le site n\'a pas pu être mis à jour'];
                }
                return ['status' => 'error', 'message' => 'Site non trouvé'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param WebsiteRequest $request
     * @param Auth $auth
     * @return array
     */
    public function getModules(WebsiteRequest $request, Auth $auth)
    {
        if ($request->has('website')) {
            $website = Website::select('modules')->where('id', $request->query('website'))->get(true);
            if (is_null($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];
            $ids = $website['modules'];
        } else {
            $ids = ($request->has('ids')) ? $request->query('ids') : [];
        }
        $modules = ModuleCategory::repo()->websiteAll($ids, $auth->get('status')->level);
        return $this->getModulesWithOptions($modules);
    }

    /**
     * @param WebsiteRequest $request
     * @param $website
     * @return array
     */
    public function updateModules(WebsiteRequest $request, $website)
    {
        if ($request->has('modules')) {
            /** @var Website $website */
            $website = Website::findOneById($website);
            if (is_null($website)) return ['status' => 'error', 'message' => 'Site non trouvé'];
            $website->setModules(array_map('intval', array_values($request->get('modules'))));
            return (Website::watchAndSave($website))
                ? ['status' => 'success', 'message' => 'Les modules ont bien été mis à jours', 'resource' => Website::repo()->getSummary($website->getId())]
                : ['status' => 'error', 'message' => 'Erreur lors de la mise à jours'];
        }
        return ['status' => 'error', 'message' => 'Modules non envoyés dans la requête'];
    }

    /**
     * @param WebsiteRequest $request
     * @param Auth $auth
     * @return array
     */
    public function delete(WebsiteRequest $request, Auth $auth)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            $websites = Website::findById($request->get('ids'));
            foreach ($websites as $website) {
                if ($auth->get('id') == $website->getSociety()->getAccount()->getId() || $auth->get('status')->level <= 1) {
                    Website::removeWatch($website);
                    /*$website->setState(0);
                    Website::watch($website);*/
                } else
                    return ['status' => 'error', 'message' => 'Vous n\'avez pas les droits nécessaire pour supprimer le(s) site(s) suivant(s)'];
            }
            return (Website::save())
                ? ['status' => 'success', 'message' => 'Le(s) site(s) ont bien été supprimé(s)']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Le(s) site(s) n\'ont pas pu être supprimé(s)'];
    }

    /**
     * @param WebsiteRequest $request
     * @param Auth $auth
     * @return array
     */
    public function changeState(WebsiteRequest $request, Auth $auth)
    {
        if ($request->method() == 'PUT' && $auth->get('status')->level <= 1 && $request->exists(['ids', 'state'])) {
            foreach ($request->get('ids') as $id)
                Website::where('id', $id)->set(['state' => $request->get('state')]);
            return ['status' => 'success', 'message' => 'Le(s) site(s) ont bien été mis à jour'];
        }
        return ['status' => 'error', 'message' => 'Le(s) site(s) n\'ont pas pu être mis à jour'];
    }

    /**
     * @param WebsiteRequest $request
     * @param Auth $auth
     * @return array
     */
    public function changeTheme(WebsiteRequest $request, Auth $auth)
    {
        if ($request->method() == 'PUT' && $request->has(['website', 'theme']) && $auth->get('status')->level <= 1) {
            /** @var Theme $theme */
            $theme = Theme::findOneById($request->get('theme'));
            if (!is_null($theme)) {
                Website::where('id', $request->get('website'))->set(['theme' => $theme, 'layout' => $theme->getWebsite()->getLayout()]);
                return ['status' => 'success', 'message' => 'Le thème du site a bien été changé', 'resource' => ['id' => $theme->getId(), 'name' => $theme->getName()]];
            }
            return ['status' => 'error', 'message' => 'Impossible de trouver le thème'];
        }
        return ['status' => 'error', 'message' => 'Le thème du site n\'a pas pu être changé'];
    }

    /**
     * @param $website
     * @return array
     */
    public function listRuleValue($website = null)
    {
        return (isset($this->app->data['app']['settings']['publication_type'])) ? $this->app->data['app']['settings']['publication_type'] : [];
    }

    /**
     * @param Website $website
     * @param $websites
     * @return
     */
    public function listCustomFields(Website $website, $websites)
    {
        $rules = [
            'global' => '',
            'everywhere' => '',
            'model' => $website->getLayout()->getId(),
        ];
        return CustomField::repo()->frontRender($websites, $this->getWebsiteData($website), $rules);
    }


    /**
     * @param int $max
     * @return mixed
     */
    public function getLast($max = 5)
    {
        return Website::repo()->getLast($max);
    }

    /**
     * @param $modules
     * @return mixed
     */
    private function getModulesWithOptions($modules)
    {
        foreach ($modules as $key => $module){
            $name = str_replace(' ', '', $module['name']);
            if(isset($this->app->data['app'][$name])){
                $modules[$key]['hook'] = (isset($this->app->data['app'][$name]['hook']))
                    ? $this->app->data['app'][$name]['hook'] : [];
                $modules[$key]['order'] = (isset($this->app->data['app'][$name]['order']))
                    ? $this->app->data['app'][$name]['order'] : 0;
                $modules[$key]['routes'] = (isset($this->app->data['app'][$name]['routes']))
                    ? $this->app->data['app'][$name]['routes'] : [];
            }
        }
        return $modules;
    }
}