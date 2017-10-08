<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\ProfessionRequest;
use Jet\Models\Profession;


/**
 * Class ProfessionController
 * @package Jet\AdminBlock\Controllers
 */
class ProfessionController extends AdminController
{
    /**
     * @param ProfessionRequest $request
     * @return array
     */
    public function all(ProfessionRequest $request)
    {
        $max = ($request->has('length')) ? (int)$request->query('length') : 10;
        $start = ($request->has('start')) ? (int)$request->query('start') : 1;
        $params = [
            'order' => ($request->has('order')) ? $request->query('order') : [],
            'search' => $request->query('search')['value']
        ];
        $response = Profession::repo()->listAll($start, $max, $params);
        $professions = [
            'draw' => (int)$request->query('draw'),
            'recordsTotal' => $response['total'],
            'recordsFiltered' => $response['total'],
            'data' => $response['data']
        ];
        return $professions;
    }

    /**
     * @return array
     */
    public function listNames()
    {
        $professions = Profession::select('id', 'name')->get();
        return ['resource' => $professions->getResults()];
    }

    /**
     * @param ProfessionRequest $request
     * @return array
     */
    public function create(ProfessionRequest $request)
    {
        if ($request->method() == 'POST') {
            $response = $request->validate();
            if ($response === true) {
                $values = $request->filled();
                if (Profession::where('slug', $values['slug'])->orWhere('name', $values['name'])->count() == 0) {
                    $profession = new Profession();
                    $profession->setName($values['name']);
                    $profession->setSlug($values['slug']);
                    $profession->setIcon($values['icon']);
                    return (Profession::watchAndSave($profession))
                        ? ['status' => 'success', 'message' => 'La profession a bien été créée']
                        : ['status' => 'error', 'message' => 'La profession n\'a pas pu être créée'];
                }
                return ['status' => 'error', 'message' => 'La profession existe déjà'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param ProfessionRequest $request
     * @param $id
     * @return array
     */
    public function update(ProfessionRequest $request, $id)
    {
        if ($request->method() == 'PUT') {
            /** @var Profession $profession */
            $profession = Profession::findOneById($id);
            if (!is_null($profession)) {
                $response = $request->validate();
                if ($response === true) {
                    $values = $request->filled();
                    if (Profession::where('slug', $values['slug'])->where('id', '!=', $id)->count() == 0) {
                        $profession->setName($values['name']);
                        $profession->setSlug($values['slug']);
                        $profession->setIcon($values['icon']);
                        return (Profession::watchAndSave($profession))
                            ? ['status' => 'success', 'message' => 'La profession a été mis à jour']
                            : ['status' => 'error', 'message' => 'La profession n\'a pas été mis à jour'];
                    }
                    return ['status' => 'error', 'message' => 'La profession existe déjà'];
                }
                return $response;
            }
            return ['status' => 'error', 'message' => 'Profession inexistante'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param ProfessionRequest $request
     * @return array
     */
    public function delete(ProfessionRequest $request)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            return (Profession::destroy($request->get('ids')))
                ? ['status' => 'success', 'message' => 'Les professions ont bien été supprimées']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Les professions n\'ont pas pu être supprimées'];
    }


}