<?php

namespace Jet\AdminBlock\Controllers;

use Jet\Models\Account;
use Jet\AdminBlock\Requests\AccountRequest;
use Jet\Services\Auth;

/**
 * Class AccountController
 * @package Jet\AdminBlock\Controllers
 */
class AccountController extends AdminController
{

    /**
     * @param AccountRequest $request
     * @return array
     */
    public function all(AccountRequest $request)
    {
        $max = ($request->exists('max')) ? (int)$request->query('max') : 10;
        $page = ($request->exists('page')) ? (int)$request->query('page') : 1;

        $params = [
            'search' => ($request->has('params') && isset($request->query('params')['search'])) ? $request->query('params')['search'] : '',
            'order' => ($request->has('params') && isset($request->query('params')['order'])) ? $request->query('params')['order'] : [],
            'filter' => ($request->has('params') && isset($request->query('params')['filter'])) ? $request->query('params')['filter'] : [],
            'role' => ($request->has('params') && isset($request->query('params')['role'])) ? $request->query('params')['role'] : ['>=' => 4]
        ];

        $response = Account::repo()->listAll($page, $max, $params);
        $pages_count = ceil($response['total'] / $max);

        $accounts = [
            'current_page' => $page,
            'count_pages' => $pages_count,
            'count_all' => $response['total'],
            'data' => $response['data']
        ];
        return ['status' => 'success', 'content' => $accounts];
    }

    /**
     * @param AccountRequest $request
     * @param Auth $auth
     * @return array|bool
     */
    public function create(AccountRequest $request, Auth $auth)
    {
        if ($request->method() == 'POST' && $auth->get('status')->level <= self::ADMIN_LEVEL) {
            $account = new Account();
            $response = $request->validate('createRules');
            if ($response === true) {
                $values = $request->values();
                // on pousse les variables dans la db
                /** @var array|boolean $response */
                $values['state'] = (int)$values['state'];
                $response = Account::repo()->update('create', $account, $values);
                return ($response === true)
                    ? ['status' => 'success', 'message' => 'Le compte a bien été créé', 'resource' => $account]
                    : $response;
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param Auth $auth
     * @param $id
     * @return array
     */
    public function read(Auth $auth, $id)
    {
        if ($auth->get('id') != $id && $auth->get('status')->level == self::USER_LEVEL) {
            return ['status' => 'error', 'message' => 'Vous n\'avez pas les permissions'];
        }
        $account = Account::findOneById($id);
        if (!is_null($account)) {
            return ['status' => 'success', 'resource' => $account];
        }
        return ['status' => 'error', 'message' => 'Compte inexistant'];
    }

    /**
     * @param AccountRequest $request
     * @param Auth $auth
     * @param $id
     * @return array
     */
    public function update(AccountRequest $request, Auth $auth, $id)
    {
        if ($request->method() == 'PUT' && ($auth->get('status')->level <= self::ADMIN_LEVEL || $auth->get('id') == $id)) {
            /** @var Account $account */
            $account = Account::findOneById($id);
            if (!is_null($account)) {
                $response = $request->validate('updateRules');
                if ($response === true) {
                    $values = $request->values();
                    if (isset($values['status']) && !empty($values['status']) && isset($values['status']['level'])) {
                        if($auth->get('status')->level > self::SUPER_ADMIN_LEVEL && (int)$values['status']['level'] < $auth->get('status')->level){
                            return ['status' => 'error', 'message' => 'Impossible de mettre un status plus haut'];
                        }
                    }
                    // on pousse les variables dans la db
                    /** @var array|boolean $response */
                    $response = Account::repo()->update($id, $account, $values);
                    if ($response === true) {
                        if ($id == $auth->get('id')) $auth->update($account);
                        $response = ['status' => 'success', 'message' => 'Le compte a été mis à jour', 'resource' => $account];
                    }
                    return $response;
                }
                return $response;
            }
            return ['status' => 'error', 'message' => 'Compte inexistant'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param AccountRequest $request
     * @param Auth $auth
     * @return array
     */
    public function delete(AccountRequest $request, Auth $auth)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            $accounts = Account::repo()->findById($request->get('ids'));
            $ids = [];
            foreach ($accounts as $account) {
                if ($auth->get('id') == $account['id']) return ['status' => 'error', 'message' => 'Vous ne pouvez pas supprimer votre compte. Veuillez contacter votre administrateur pour faire la manipulation.'];
                if ($auth->get('status')->level < 2 && $account['status']['level'] > $auth->get('status')->level) {
                    $ids[] = $account['id'];
                } else
                    return ['status' => 'error', 'message' => 'Vous n\'avez pas les droits nécessaire pour supprimer le(s) compte(s) suivant(s)'];
            }
            return (Account::destroy($ids))
                ? ['status' => 'success', 'message' => 'Le(s) compte(s) ont bien été supprimé(s)']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param int $account
     * @return array
     */
    public function getSocieties($account)
    {
        $societies = Society::repo()->getAccountSocieties($account);
        if (!empty($societies))
            return ['status' => 'success', 'resource' => $societies];
        return ['status' => 'error', 'message' => 'Aucunes sociétés trouvées pour ce compte'];
    }

    /**
     * @param AccountRequest $request
     * @param Auth $auth
     * @return array
     */
    public function changeState(AccountRequest $request, Auth $auth)
    {
        if ($request->method() == 'PUT' && $auth->get('status')->level <= self::ADMIN_LEVEL && $request->exists(['ids', 'state'])) {
            $accounts = Account::findBy(['id' => $request->get('ids')]);
            foreach ($accounts as $account) {
                if ($auth->get('id') != $account->getId()) {
                    $account->setState($request->get('state'));
                    Account::watch($account);
                }
            }
            return (Account::save())
                ? ['status' => 'success', 'message' => 'Le(s) compte(s) ont bien été mis à jour']
                : ['status' => 'error', 'message' => 'Erreur lors de la mise à jour'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param AccountRequest $request
     * @return array
     */
    public function listBetweenDates(AccountRequest $request)
    {
        if ($request->has('start') && $request->has('end')) {
            $months = ['01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril', '05' => 'Mai', '06' => 'Juin', '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre'];
            $dates = $labels = [];
            $start = new \DateTime($request->get('start'));
            $end = new \DateTime($request->get('end'));
            $month_interval = $start->diff($end)->m + ($start->diff($end)->y * 12);
            for ($i = 0; $i <= $month_interval; ++$i) {
                $start = new \DateTime($request->get('start'));
                $end = new \DateTime($request->get('start'));
                $start->add(new \DateInterval('P' . $i . 'M'));
                $end->add(new \DateInterval('P' . ($i + 1) . 'M'));
                $labels[] = $months[$start->format('m')] . ' ' . $start->format('Y');
                $dates[] = Account::repo()->listBetweenDates($start->format('Y-m-d H:i:s'), $end->format('Y-m-d H:i:s'));
            }
            return compact('dates', 'labels');
        }
        return ['status' => 'error', 'message' => 'Paramètres manquants'];
    }

} 