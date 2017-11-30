<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\RouteRequest;
use Jet\Models\Route;
use Jet\Models\Website;
use Jet\AdminBlock\Services\Auth;


/**
 * Class RouteController
 * @package Jet\AdminBlock\Controllers
 */
class RouteController extends AdminController
{
    /**
     * @param $website
     * @return array
     */
    public function all($website)
    {
        if ($website == 'global') {
            $routes = Route::repo()->listAll();
        } else {
            if (!$this->getWebsite($website)) return [];
            $routes = Route::repo()->listAll($this->websites, $this->getWebsiteData($this->website));
        }
        return $routes;
    }

    /**
     * @param RouteRequest $request
     * @param $website
     * @param string $id
     * @return array
     */
    public function updateOrCreate(RouteRequest $request, $website, $id = 'create')
    {
        if ($request->method() == 'POST' || $request->method() == 'PUT') {
            $response = $request->validate();
            if ($response === true) {

                $values = $request->getPost();

                $route = ($id != 'create') ? Route::findOneById($id) : new Route();
                if(is_null($route)) return ['status' => 'error', 'message' => 'Impossible de trouver la route'];

                if ($website != 'global') {
                    $website = Website::findOneById($website);
                    if(is_null($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];
                    if($route->getWebsite() != $website) $route = new Route();
                }else{
                    $website = null;
                }

                $route->setUrl($values->get('url'));
                $route->setName($values->get('name'));
                $route->setMethod($values->get('method'));
                if ($values->has('middleware') && !is_null($values->get('middleware')) && !empty($values->get('middleware'))) $route->setMiddleware($values->get('middleware'));
                if ($values->has('subdomain') && !is_null($values->get('subdomain')) && !empty($values->get('subdomain'))) $route->setSubdomain($values->get('subdomain'));
                $route->setPosition((int)$values->get('position'));
                if ($values->has('argument')) $route->setArgument($values->get('argument'));
                $route->setWebsite($website);

                return (Route::watchAndSave($route))
                    ? ['status' => 'success', 'message' => 'La route a bien été créée', 'resource' => $route]
                    : ['status' => 'error', 'message' => 'Erreur lors de la création de la route'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisé'];
    }

    /**
     * @param $website
     * @return array
     */
    public function getWebsiteRoutes($website)
    {
        if (!$this->getWebsite($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];
        return ['resource' => Route::repo()->getWebsiteRoutes($this->websites, $this->getWebsiteData($this->website))];
    }

    /**
     * @param $id
     * @return array
     */
    public function read($id)
    {
        $route = Route::findOneById($id);
        if (!is_null($route))
            return $route;
        return [];
    }

    /**
     * @param $key
     * @param $value
     * @return array
     */
    public function findBy($key, $value)
    {
        $route = Route::where($key, $value)->get(true);
        if (!is_null($route) && $route->count() > 0)
            return ['resource' => $route->_serialize()];
        return [];
    }


    /**
     * @param RouteRequest $request
     * @param Auth $auth
     * @param $website
     * @return array
     */
    public function delete(RouteRequest $request, Auth $auth, $website)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids') && $auth->get('status')->level <= self::ADMIN_LEVEL) {
            $routes = Route::findById($request->get('ids'));
            $ids = [];
            /** @var Route $route */
            foreach ($routes as $route) {
                if (!is_null($route->getWebsite()) && $route->getWebsite()->getId() == $website)
                    $ids[] = $route->getId();
            }
            return (Route::destroy($ids))
                ? ['status' => 'success', 'message' => 'Les routes ont bien été supprimées']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Les routes n\'ont pas pu être supprimées'];
    }
}