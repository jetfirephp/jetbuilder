<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\ThemeRequest;
use Jet\Models\Media;
use Jet\Models\Profession;
use Jet\Models\Theme;
use Jet\Models\Website;
use Jet\AdminBlock\Services\Auth;

/**
 * Class ThemeController
 * @package Jet\AdminBlock\Controllers
 */
class ThemeController extends AdminController
{

    /**
     * @param ThemeRequest $request
     * @return array
     */
    public function all(ThemeRequest $request)
    {
        $max = ($request->exists('max')) ? (int)$request->query('max') : 10;
        $page = ($request->exists('page')) ? (int)$request->query('page') : 1;

        $params = [
            'search' => ($request->has('params') && isset($request->query('params')['search'])) ? $request->query('params')['search'] : '',
            'order' => ($request->has('params') && isset($request->query('params')['order'])) ? $request->query('params')['order'] : [],
            'filter' => ($request->has('params') && isset($request->query('params')['filter'])) ? $request->query('params')['filter'] : [],
        ];

        $response = Theme::repo()->listAll($page, $max, $params);
        $pages_count = ceil($response['total'] / $max);

        $themes = [
            'current_page' => $page,
            'count_pages' => $pages_count,
            'count_all' => $response['total'],
            'data' => $response['data']
        ];
        return ['status' => 'success', 'content' => $themes];
    }

    /**
     * @param ThemeRequest $request
     * @return array
     */
    public function create(ThemeRequest $request)
    {
        if ($request->method() == 'POST') {
            $response = $request->validate();
            if ($response === true) {
                $values = $request->values();
                $website = Website::repo()->getSocietyWebsite($values['society']);
                if (!empty($website)) {
                    if (Theme::where('website', $website[0])->count() == 0) {
                        $theme = new Theme();
                        $theme->setName($values['name']);
                        $theme->setWebsite($website[0]);
                        $professions = Profession::findById($values['professions']);
                        $theme->setProfessions($professions);
                        $theme->setThumbnail(Media::findOneById($values['thumbnail']));
                        return (Theme::watchAndSave($theme))
                            ? ['status' => 'success', 'message' => 'Le thème a bien été créé', 'resource' => Theme::repo()->read($theme->getId())]
                            : ['status' => 'error', 'message' => 'Le thème n\'a pas pu être créé'];
                    }
                    return ['status' => 'error', 'message' => 'Le site est déjà utilisé pour un autre thème'];
                }
                return ['status' => 'error', 'message' => 'Site inconnu'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisé'];
    }

    /**
     * @param ThemeRequest $request
     * @param Auth $auth
     * @param $id
     * @return array
     */
    public function update(ThemeRequest $request, Auth $auth, $id)
    {
        if ($request->method() == 'PUT' && $auth->get('status')->level <= 2) {
            /** @var Theme $theme */
            $theme = Theme::findOneById($id);
            if (!is_null($theme)) {
                $response = $request->validate();
                if ($response === true) {
                    $values = $request->values();
                    $website = Website::repo()->getSocietyWebsite($values['society']);
                    if (!empty($website)) {
                        $theme->setName($values['name']);
                        if (isset($values['professions'])) {
                            $professions = Profession::findById($values['professions']);
                            $theme->setProfessions($professions);
                        }
                        if (isset($values['society'])) $theme->setWebsite($website[0]);
                        if (isset($values['thumbnail'])) $theme->setThumbnail(Media::findOneById($values['thumbnail']));
                        return (Theme::watchAndSave($theme))
                            ? ['status' => 'success', 'message' => 'Le thème a été mis à jour', 'resource' => Theme::repo()->read($theme->getId())]
                            : ['status' => 'error', 'message' => 'Le thème n\'a pas été mis à jour'];
                    }
                    return ['status' => 'error', 'message' => 'Site inconnu'];
                }
                return $response;
            }
            return ['status' => 'error', 'message' => 'Thème inexistant'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisé'];
    }

    /**
     * @param ThemeRequest $request
     * @param Auth $auth
     * @return array
     */
    public function delete(ThemeRequest $request, Auth $auth)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids') && $auth->get('status')->level <= 1) {
            return (Theme::destroy($request->get('ids')))
                ? ['status' => 'success', 'message' => 'Le(s) thème(s) ont bien été supprimé(s)']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Le(s) thème(s) n\'ont pas pu être supprimé(s)'];
    }

    /**
     * @param ThemeRequest $request
     * @return array
     */
    public function changeState(ThemeRequest $request)
    {
        if ($request->method() == 'PUT' && $request->exists(['ids', 'state'])) {
            foreach ($request->get('ids') as $id)
                Theme::where('id', $id)->set(['state' => $request->get('state')]);
            return ['status' => 'success', 'message' => 'Le(s) thème(s) ont bien été mis à jour'];
        }
        return ['status' => 'error', 'message' => 'Le(s) thème(s) n\'ont pas pu être mis à jour'];
    }

}