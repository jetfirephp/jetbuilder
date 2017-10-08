<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\LibraryRequest;
use Jet\Models\Library;

/**
 * Class LibraryController
 * @package Jet\AdminBlock\Controllers
 */
class LibraryController extends AdminController
{

    /**
     * @param LibraryRequest $request
     * @return array
     */
    public function all(LibraryRequest $request)
    {
        $max = ($request->has('length')) ? (int)$request->query('length') : 10;
        $start = ($request->has('start')) ? (int)$request->query('start') : 1;
        $params = [
            'order' => ($request->has('order')) ? $request->query('order') : [],
            'search' => $request->query('search')['value']
        ];
        $response = Library::repo()->listAll($start, $max, $params);
        $libraries = [
            'draw' => (int)$request->query('draw'),
            'recordsTotal' => $response['total'],
            'recordsFiltered' => $response['total'],
            'data' => $response['data']
        ];
        return $libraries;
    }

    /**
     * @param LibraryRequest $request
     * @return array|array
     */
    public function create(LibraryRequest $request)
    {
        if ($request->method() == 'POST') {
            $response = $request->validate();
            if ($response === true) {
                $library = $request->only(['name', 'path', 'type', 'category']);
                if ($library['type'] == 'file' && !is_file(ROOT . '/public/' . $library['category'] . '/' . $library['path']))
                    return ['status' => 'error', 'message' => 'Fichier non trouvé'];
                if (Library::where('path', $library['path'])->count() > 0)
                    return ['status' => 'error', 'message' => 'La librairie existe déjà'];
                $response = (Library::create($library))
                    ? ['status' => 'success', 'message' => 'La librairie a bien été créé']
                    : ['status' => 'error', 'message' => 'La librairie n\'a pas pu être créé'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisé'];
    }

    /**
     * @param LibraryRequest $request
     * @param $id
     * @return array|array
     */
    public function update(LibraryRequest $request, $id)
    {
        if ($request->method() == 'PUT') {
            $response = $request->validate();
            if ($response === true) {
                $library = $request->only(['name', 'path', 'type', 'category']);
                if ($library['type'] == 'file' && !is_file(ROOT . '/public/' . $library['category'] . '/' . $library['path']))
                    return ['status' => 'error', 'message' => 'Fichier non trouvé'];
                if (Library::where('path', $library['path'])->where('id', '!=', $id)->count() > 0)
                    return ['status' => 'error', 'message' => 'La librairie existe déjà'];
                $response = (Library::update($id, $library))
                    ? ['status' => 'success', 'message' => 'La librairie a bien été modifiée']
                    : ['status' => 'error', 'message' => 'La librairie n\'a pas pu être modifiée'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisé'];
    }

    /**
     * @param LibraryRequest $request
     * @return array
     */
    public function delete(LibraryRequest $request)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            return (Library::destroy($request->get('ids')))
                ? ['status' => 'success', 'message' => 'Les librairies ont bien été supprimées']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Les librairies n\'ont pas pu être supprimées'];
    }


    /**
     * @return array
     */
    public function getNames()
    {
        $libraries = Library::select('id', 'name')->get();
        return ['resource' => $libraries->getResults()];
    }
}