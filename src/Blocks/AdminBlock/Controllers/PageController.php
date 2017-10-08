<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\PageRequest;
use Jet\Models\CustomField;
use Jet\Models\Library;
use Jet\Models\Page;
use Jet\Models\Route;
use Jet\Models\Template;
use Jet\Models\Website;
use Jet\Services\Auth;
use JetFire\Framework\Providers\EventProvider;

/**
 * Class PageController
 * @package Jet\AdminBlock\Controllers
 */
class PageController extends AdminController
{

    /**
     * @param PageRequest $request
     * @param $website
     * @return array
     */
    public function all(PageRequest $request, Auth $auth, $website)
    {
        $max = ($request->exists('max')) ? (int)$request->query('max') : 10;
        $page = ($request->exists('page')) ? (int)$request->query('page') : 1;

        if (!$this->getWebsite($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];

        $params = [
            'websites' => $this->websites,
            'options' => $this->getWebsiteData($this->website),
            'search' => ($request->has('params') && isset($request->query('params')['search'])) ? $request->query('params')['search'] : '',
            'order' => ($request->has('params') && isset($request->query('params')['order'])) ? $request->query('params')['order'] : [],
            'filter' => ($request->has('params') && isset($request->query('params')['filter'])) ? $request->query('params')['filter'] : [],
        ];

        if($auth->get('status')->level == 4){
            $params['static'] = true;
        }

        $response = Page::repo()->listAll($page, $max, $params);
        $pages_count = ceil($response['total'] / $max);

        $pages = [
            'current_page' => $page,
            'count_pages' => $pages_count,
            'count_all' => $response['total'],
            'data' => $response['data']
        ];
        return ['status' => 'success', 'content' => $pages];
    }

    /**
     * @param $website
     * @param $id
     * @return array
     */
    public function read($website, $id)
    {
        /** @var Website $website */
        $website = Website::findOneById($website);
        if (is_null($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];

        $page = Page::repo()->read($id, $this->getWebsiteData($website));
        return (!is_null($page))
            ? ['status' => 'success', 'resource' => $page]
            : ['status' => 'error', 'message' => 'Page inexistant'];
    }

    /**
     * @param PageRequest $request
     * @param Auth $auth
     * @param $website
     * @param $id
     * @return array
     */
    public function updateOrCreate(PageRequest $request, Auth $auth, $website, $id)
    {
        if ($request->method() == 'PUT' || $request->method() == 'POST') {

            if (!$this->isWebsiteOwner($auth, $website, self::ADMIN_LEVEL))
                return ['status' => 'error', 'message' => 'Vous n\'avez pas les permissions pour mettre à jour la page'];

            if ($id == 'create' && $auth->get('status')->level > self::ADMIN_LEVEL)
                return ['status' => 'error', 'message' => 'Vous n\'avez pas les permissions pour créer une page'];

            $response = ($id == 'create') ? $request->validate('createRules') : $request->validate('updateRules');
            if ($response === true) {
                $page = ($id == 'create') ? new Page() : Page::findOneById($id);
                if (!is_null($page)) {

                    $website = Website::findOneById($website);
                    if (is_null($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site web'];

                    if ($page->getWebsite() != $website && $id != 'create') {
                        $data = $this->excludeData($website->getData(), 'pages', $page->getId());
                        $website->setData($data);
                        Website::watch($website);
                        $page = new Page();
                    }
                    $page->setWebsite($website);

                    $value = $request->getPost();
                    $page->setTitle($value->get('title'));
                    $page->setType($value->get('type'));
                    ($value->get('builder') == 'true' || $value->get('builder') == 1) ? $page->setBuilder(1) : $page->setBuilder(0);
                    ($value->get('published') == 'true' || $value->get('published') == 1) ? $page->setPublished(1) : $page->setPublished(0);

                    if (isset($value->get('route')['id']) && !empty($value->get('route')['id'])) {
                        $route = Route::findOneById($value->get('route')['id']);
                        if (!is_null($route)) $page->setRoute($route);
                    } else
                        return ['status' => 'error', 'message' => 'Route non définie'];

                    if (isset($value->get('layout')['id']) && !empty($value->get('layout')['id'])) {
                        $layout = Template::findOneById($value->get('layout')['id']);
                        if (!is_null($layout)) $page->setLayout($layout);
                    } else
                        return ['status' => 'error', 'message' => 'Layout non défini'];

                    if ($value->has('stylesheets') && !empty($value->get('stylesheets'))) {
                        $stylesheets = Template::findBy(['id' => $value->get('stylesheets')]);
                        if (!is_null($stylesheets)) $page->setStylesheets($stylesheets);
                    } else
                        $page->setStylesheets([]);

                    if ($value->has('libraries') && !empty($value->get('libraries'))) {
                        $libraries = Library::findBy(['id' => $value->get('libraries')]);
                        if (!is_null($libraries)) $page->setLibraries($libraries);
                    } else {
                        $page->setLibraries([]);
                    }

                    if (Page::watchAndSave($page)) {
                        /* Emit event to listen */
                        //$this->app->emit('updatePage', ['old_page' => $old_page, 'page' => $page->getId(), 'website' => $website->getId()]);
                        return ['status' => 'success', 'message' => 'La page a bien été mis à jour', 'resource' => $page];
                    } else
                        return ['status' => 'error', 'message' => 'Erreur lors de la mise à jour'];
                }
                return ['status' => 'error', 'message' => 'Page non trouvée'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param EventProvider $event
     * @param $old_page
     * @param $page
     * @param $website
     */
    public function emitPageUpdateEvent(EventProvider $event, $old_page, $page, $website)
    {
        /* Emit event to listen */
        $event->emit('updatePage', ['old_page' => $old_page, 'page' => $page, 'website' => $website]);
    }

    /**
     * @param PageRequest $request
     * @param Auth $auth
     * @param $website
     * @return array
     */
    public function delete(PageRequest $request, Auth $auth, $website)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            /** @var Website $website */
            $website = Website::findOneById($website);
            if (is_null($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site web'];
            $data = $website->getData();

            if (!$this->isWebsiteOwner($auth, $website->getId(), self::ADMIN_LEVEL))
                return ['status' => 'error', 'message' => 'Vous n\'avez pas les permissions pour supprimer ces catégories'];

            $pages = Page::repo()->findById($request->get('ids'));
            $ids = [];

            foreach ($pages as $page) {
                if ($page['website']['id'] != $website->getId())
                    $data = $this->excludeData($data, 'pages', $page['id']);
                else
                    $ids[] = $page['id'];
            }

            $website->setData($data);
            Website::watchAndSave($website);

            return (Page::destroy($ids))
                ? ['status' => 'success', 'message' => 'Les pages ont bien été supprimées']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Les pages n\'ont pas pu être supprimées'];
    }

    /**
     * @param PageRequest $request
     * @param $website
     * @return array
     */
    public function changeState(PageRequest $request, $website)
    {
        if ($request->method() == 'PUT' && $request->exists(['ids', 'state'])) {
            $page_website = Website::findOneById($website);
            if (is_null($page_website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site web'];
            $data = $page_website->getData();
            foreach ($request->get('ids') as $id) {
                $page = Page::findOneById($id);
                if ($page->getWebsite()->getId() != $website) {
                    $new_page = new Page();
                    $new_page->setTitle($page->getTitle());
                    $new_page->setType($page->getType());
                    $new_page->setBuilder($page->isBuilder());
                    $new_page->setRoute($page->getRoute());
                    $new_page->setWebsite($page_website);
                    $new_page->setLayout($page->getLayout());
                    $new_page->setPublished($request->get('state'));
                    foreach ($page->getContents() as $content)
                        $new_page->addContent($content);
                    foreach ($page->getStylesheets() as $style)
                        $new_page->addStylesheet($style);
                    foreach ($page->getLibraries() as $lib)
                        $new_page->addLibrary($lib);
                    Page::watchAndSave($new_page);
                    $data = $this->excludeData($data, 'pages', $id);
                } else
                    Page::where('id', $id)->set(['published' => $request->get('state')]);
            }
            $page_website->setData($data);
            Website::watchAndSave($page_website);
            return ['status' => 'success', 'message' => 'Le(s) page(s) ont bien été mis à jour'];
        }
        return ['status' => 'error', 'message' => 'Le(s) page(s) n\'ont pas pu être mis à jour'];
    }

    /**
     * @param $website
     * @return array
     */
    public function listRuleValue($website)
    {
        return (!$this->getWebsite($website))
            ? ['status' => 'error', 'Impossible de trouver le site web']
            : Page::repo()->getPageRules($this->websites, $this->getWebsiteData($this->website));
    }

    /**
     * @param $website
     * @return array
     */
    public function listTypeRuleValue($website = null)
    {
        return [['id' => 'static', 'name' => 'Statique'], ['id' => 'dynamic', 'name' => 'Dynamique']];
    }

    /**
     * @param $website
     * @return array
     */
    public function listStaticPages($website)
    {
        return (!$this->getWebsite($website))
            ? ['status' => 'error', 'Impossible de trouver le site web']
            : ['resource' => Page::repo()->getStaticPages($this->websites, $this->getWebsiteData($this->website))];
    }

    /**
     * @param $id
     * @return Route | array
     */
    public function getStaticPageRoute($id)
    {
        $page = Page::find($id);
        return (is_null($page))
            ? ['status' => 'error', 'message' => 'Impossible de trouver la page']
            : $page->getRoute();
    }

    /**
     * @param Page $page
     * @param Website $website
     * @param $websites
     * @return
     */
    public function listCustomFields(Page $page, Website $website, $websites)
    {
        $rules = [
            'everywhere' => null,
            'publication_type' => 'page',
            'page' => $page->getId(),
            'model' => $page->getLayout()->getId(),
            'page_type' => $page->getType()
        ];
        return CustomField::repo()->frontRender($websites, $this->getWebsiteData($website), $rules);
    }
}