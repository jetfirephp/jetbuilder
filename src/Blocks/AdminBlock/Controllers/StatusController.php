<?php

namespace Jet\AdminBlock\Controllers;

use Jet\Models\Status;
use JetFire\Framework\System\Request;

/**
 * Class StatusController
 * @package Jet\AdminBlock\Controllers
 */
class StatusController extends AdminController
{

    /**
     * @return array
     */
    public function all()
    {
        return Status::findAll();
    }

    /**
     * @param Request $request
     * @return array
     */
    public function create(Request $request)
    {
        if ($request->method() == 'POST') {
            $response = $request->validate([
                'role' => 'required|noWhitespace',
                'level' => 'required|numeric'
            ]);
            if ($response === true) {
                $values = $request->filled();
                if (Status::where('role', $values['role'])->count() == 0) {
                    $status = new Status();
                    $status->setRole($values['role']);
                    $status->setLevel($values['level']);
                    return (Status::watchAndSave($status))
                        ? ['status' => 'success', 'message' => 'Le rôle a bien été créé', 'resource' => $status]
                        : ['status' => 'error', 'message' => 'Le rôle n\'a pas pu être créé'];
                }
                return ['status' => 'error', 'message' => 'Le rôle existe déjà'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param Request $request
     * @param $id
     * @return array
     */
    public function update(Request $request, $id)
    {
        if ($request->method() == 'PUT') {
            $status = Status::findOneById($id);
            if (!is_null($status)) {
                $response = $request->validate([
                    'role' => 'required|noWhitespace',
                    'level' => 'required|numeric'
                ]);
                if ($response === true) {
                    $values = $request->filled();
                    if (Status::where('role', $values['role'])->where('id', '!=', $id)->count() == 0) {
                        $status->setRole($values['role']);
                        $status->setLevel($values['level']);
                        return (Status::watchAndSave($status))
                            ? ['status' => 'success', 'message' => 'Le rôle a été mis à jour', 'resource' => $status]
                            : ['status' => 'error', 'message' => 'Le rôle n\'a pas été mis à jour'];
                    }
                    return ['status' => 'error', 'message' => 'Le rôle existe déjà'];
                }
                return $response;
            }
            return ['status' => 'error', 'message' => 'Rôle inexistant'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            $status = Status::findById($request->get('ids'));
            foreach ($status as $role) {
                if ($role->getLevel() > 0)
                    Status::removeWatch($role);
            }
            return (Status::save())
                ? ['status' => 'success', 'message' => 'Les rôles ont bien été supprimées']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Les rôles n\'ont pas pu être supprimées'];
    }

    /**
     * @param $role
     * @return array
     */
    public function getIdByRole($role)
    {
        $status = Status::select('id')->where('role', $role)->get(true);
        return (!is_null($status) && isset($status['id'])) ? $status['id'] : ['status' => 'error', 'message' => 'Impossible de trouver le status'];
    }
}
