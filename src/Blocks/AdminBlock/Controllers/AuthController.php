<?php

namespace Jet\AdminBlock\Controllers;

use Jet\Models\Account;
use Jet\AdminBlock\Requests\AuthRequest;
use Jet\Models\Website;
use Jet\Services\Admin;
use Jet\Services\Auth;
use JetFire\Framework\System\Controller;
use JetFire\Framework\System\Mail;
use JetFire\Framework\System\Request;

/**
 * Class AuthController
 * @package Jet\AdminBlock\Controllers
 */
class AuthController extends Controller
{

    use Admin;

    /**
     * @param Auth $auth
     * @return mixed
     */
    public function index(Auth $auth)
    {
        $admin_url = $this->getAdminUrl($this->app);
        $app_name = (isset($this->app->data['setting']['name'])) ? $this->app->data['setting']['name'] : 'JetBuilder';
        if ($auth->hasRemember()) {
            $account = Account::where('id', $auth->getRemember('id'))->where('token', $auth->getRemember('token'))->get(true);
            if (!is_null($account) && isset($account['id'])) {
                $auth->log($account->_getTable());
                /*$auth->getSession()->set('_auth_websites', Website::repo()->getAccountWebsites($account['id']));*/
                return $this->redirect()->url($admin_url);
            }
        }
        return $this->render('auth_layout', [
            'app_name' => $app_name,
            'admin_url' => $admin_url,
            'locale' => $this->app->data['_locale']
        ]);
    }

    /**
     * @param AuthRequest $request
     * @param Auth $auth
     * @return array
     */
    public function login(AuthRequest $request, Auth $auth)
    {
        $admin_domain = (isset($this->app->data['setting']['admin_domain'])) ? $this->app->data['setting']['admin_domain'] : '';
        $target = ($auth->getSession()->has('redirect_url')) ? '#/' . $auth->getSession()->get('redirect_url') : '';
        if ($auth->check()) {
            return ['status' => 'success', 'target' => $admin_domain . $target];
        } elseif ($auth->guest() && $request->method() == 'POST') {
            $value = $request->only('email', 'password', 'remember');
            $response = $request->validate('loginRules');
            if ($response === true) {
                $account = Account::where('email', $value['email'])->where('state', 1)->get(true);
                if (!is_null($account) && isset($account['id'])) {
                    $response = $request->validate(['password' => 'equal:' . $account['password'] . ',password_verify'], ['equal' => 'Mot de passe incorrect']);
                    if ($response === true) {
                        $auth->log($account->_getTable());
                        $auth->getSession()->set('_auth_websites', Website::repo()->getAccountWebsites($account['id']));
                        if ($value['remember'] == "true") {
                            $account['token'] = md5(uniqid(rand(), true));
                            $auth->setRemember($account, 604800 * 4);
                            $account->save();
                        }
                        return ['status' => 'success', 'target' => $admin_domain . $target];
                    }
                    return $response;
                }
                $response = ['status' => 'error', 'message' => 'Account not found'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Erreur technique'];
    }


    /**
     * @param Auth $auth
     * @param $email
     * @return array|bool
     */
    public function loginAsUser(Auth $auth, $email)
    {
        $account = Account::where('email', $email)->get(true);
        if (!is_null($account) && isset($account['id'])) {
            $auth->removeRemember();
            $auth->log($account->_getTable());
            $auth->getSession()->set('_auth_websites', Website::repo()->getAccountWebsites($account['id']));
            return ['status' => 'success'];
        }
        return ['status' => 'error', 'message' => 'Impossible de se connecter en tant qu\'utilisateur'];
    }

    /**
     * @param Auth $auth
     * @return array
     */
    public function logout(Auth $auth)
    {
        $admin_domain = (isset($this->app->data['setting']['admin_domain'])) ? $this->app->data['setting']['admin_domain'] : '';
        $auth->logout();
        $auth->getSession()->clear();
        return ['status' => 'success', 'target' => $admin_domain . '/auth'];
    }

    /**
     * @param AuthRequest $request
     * @param Mail $mail
     * @return array
     */
    public function lostPassword(AuthRequest $request, Mail $mail)
    {
        if ($request->method() == 'POST' && $request->has('email')) {
            $email = $request->input('email');
            $account = Account::where('email', $email)->get(true);
            if (!is_null($account) && isset($account['id'])) {
                
                if($account['state'] == 0) return ['status' => 'error', 'message' => 'Votre compte n\'est pas actif. Veuillez contacter un administrateur.'];
                
                $token = md5(uniqid(rand(), true));
                $account['token'] = $token;
                $account['token_time'] = new \DateTime('tomorrow');
                $admin_domain = (isset($this->app->data['setting']['admin_domain'])) ? $this->app->data['setting']['admin_domain'] : '';
                $content = $this->render('Mail/login_lost_password', ['link' => $admin_domain . '/auth#/reset-password?id=' . $account['id'] . '&token=' . $token]);
                if (!$mail->sendTo($account['email'], 'Reinitialisation mot de passe ' . $this->app->data['setting']['name'], $content))
                    return ['status' => 'error', 'message' => 'Erreur technique lors de l\'envoie du mail'];
                $account->save();
                return ['status' => 'success', 'message' => 'Un e-mail vient de vous être envoyé ! Celui-ci contient un lien pour générer un nouveau mot de passe'];
            }
            return ['status' => 'error', 'message' => 'Désolé ! Nous n\'avons trouvé aucun compte associé à cette adresse e-mail'];
        }
        return ['status' => 'error', 'message' => 'Entrez votre adresse e-mail'];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function resetPassword(Request $request)
    {
        $response = $request->validate([
            'account' => 'required|integer',
            'token' => 'required',
            'confirm_pass' => 'required',
            'password' => 'required|same:confirm_pass|assign:crypt,password_hash'
        ], [
            'required' => 'Les 2 champs sont obligatoires',
            'same' => 'Les 2 mots de passe ne sont pas identiques'
        ]);
        if ($response === true) {
            $params = $request->values();
            $account = Account::select('id')->where('id', $params['account'])
                ->where('token', $params['token'])
                ->where('token_time', '>=', new \DateTime('now'))->get(true);
            if (!is_null($account) && isset($account['id'])) {
                if (Account::update($account['id'], [
                    'password' => $params['password'],
                    'token' => null,
                    'token_time' => null
                ])
                ) {
                    return ['status' => 'success', 'message' => 'Votre mot de passe a été réinitialisé avec succés'];
                }
                return ['status' => 'error', 'message' => 'Token invalide ou compte inexistant'];
            }
            return ['status' => 'error', 'message' => 'Erreur durant la réinitialisation du mot de passe'];
        }
        return $response;
    }

    /**
     * @param Auth $auth
     * @return array
     */
    public function getAuth(Auth $auth)
    {
        return $auth->get();
    }

} 