<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\SocietyRequest;
use Jet\Models\Account;
use Jet\Models\Society;
use Jet\Services\Auth;

class SocietyController extends AdminController
{
    /**
     * @param string $search
     * @return array
     */
    public function listNames($search = '_all')
    {
        $societies = ($search == '_all') ? Society::select('name')->get() : Society::select('name')->where('name', 'LIKE', '%' . $search . '%')->get();
        return array_map('current', $societies->getResults());
    }

    /**
     * @param SocietyRequest $request
     * @param $id
     * @return array
     */
    public function update(SocietyRequest $request, $id)
    {
        if ($request->method() == 'PUT') {
            $response = $request->validate();
            if ($response === true) {
                /** @var Society $society */
                $society = Society::findOneById($id);
                if (!is_null($society)) {
                    $values = $request->values();
                    if (Society::where('name', $values['name'])->where('id', '!=', $id)->count() > 0) return ['status' => 'error', 'message' => 'Le nom de la société est déjà prise'];
                    $account = Account::findOneById($values['account']);
                    if (is_null($account)) return ['status' => 'error', 'message' => 'Le compte n\'a pas été trouvé'];
                    $society->setAccount($account);
                    $society->setName($values['name']);
                    $society->setEmail($values['email']);
                    $society->setPhone($values['phone']);
                    return (Society::watchAndSave($society))
                        ? ['status' => 'success', 'message' => 'La société a bien été mise à jour']
                        : ['status' => 'error', 'message' => 'Erreur lors de la mise à jour de la société'];
                }
                return ['status' => 'error', 'message' => 'La société n\'a pas été trouvée'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param SocietyRequest $request
     * @param Auth $auth
     * @return array
     */
    public function delete(SocietyRequest $request, Auth $auth)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            $societies = Society::findById($request->get('ids'));
            /** @var Society $society */
            foreach ($societies as $society) {
                if ($auth->get('id') == $society->getAccount()->getId() || $auth->get('status')->level <= self::ADMIN_LEVEL) {
                    Society::removeWatch($society);
                } else
                    return ['status' => 'error', 'message' => 'Vous n\'avez pas les droits nécessaire pour supprimer la société'];
            }
            return (Society::save())
                ? ['status' => 'success', 'message' => 'La société a bien été supprimée']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'La société n\'a pas pu être supprimée'];
    }

}