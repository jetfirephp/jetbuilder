<?php

namespace Jet\PublicBlock\Controllers;

use Cocur\Slugify\Slugify;
use Jet\Models\Account;
use Jet\Models\ModuleCategory;
use Jet\Models\Society;
use Jet\Models\Theme;
use Jet\Models\Website;
use Jet\PublicBlock\Requests\AccountRequest;
use Jet\Services\Recaptcha;
use JetFire\Framework\System\Controller;
use JetFire\Framework\System\Mail;
use JetFire\Framework\System\View;

/**
 * Class RegistrationController
 * @package Jet\PublicBlock\Controllers
 */
class RegistrationController extends Controller
{
    /**
     * @param Recaptcha $captcha
     * @param $theme
     * @return array
     */
    public function index(Recaptcha $captcha, $theme)
    {
        $data = [
            'captcha' => $captcha,
            'theme' => Theme::repo()->getThumbnail($theme)
        ];

        return $this->render('/Registration/index', $data);
    }

    /**
     * @param AccountRequest $request
     * @param View $view
     * @param Mail $mail
     * @param Slugify $slugify
     * @param Recaptcha $captcha
     * @param $theme
     * @return array
     */
    public function register(AccountRequest $request, View $view, Mail $mail, Slugify $slugify, Recaptcha $captcha, $theme)
    {
        if ($request->method() == 'POST') {
            $response = $request->validate();
            if ($response === true) {
                if ($captcha->isValid($request->get('captcha'))) {
                    $values = $request->values();
                    $values['society']['slug'] = (isset($values['society']) && isset($values['society']['name']))
                        ? $slugify->slugify($values['society']['name']) : null;
                    $response = Account::repo()->store($values);
                    if ($response['status'] == 'success') {
                        $content = $this->render('Mail/validate_account', [
                            'link' => $view->path('public.registration.validate', ['_locale' => 'fr', 'theme_id' => $theme, 'account_id' => $response['account'], 'society_id' => $response['society']]) . '?token=' . $values['token'],
                        ]);
                        return (!$mail->sendTo($values['account']['email'], 'Activer mon compte', $content))
                            ? ['status' => 'error', 'message' => 'Erreur lors de l\'envoie du mail']
                            : ['status' => 'success', 'message' => 'Merci de votre inscription ! Vous allez recevoir un mail pour valider votre inscription'];
                    }
                    return $response;
                }
                $response = ['status' => 'error', 'message' => 'Captcha invalide !'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée !'];
    }

    /**
     * @param AccountRequest $request
     * @param Mail $mail
     * @param Slugify $slugify
     * @param $theme
     * @param $account
     * @param $society
     * @return array
     */
    public function validateAccount(AccountRequest $request, Mail $mail, Slugify $slugify, $theme, $account, $society)
    {
        if ($request->has('token')) {
            $account = Account::select('id', 'first_name', 'last_name', 'email', 'token_time')->where('id', $account)->where('token', $request->get('token'))->get(true);
            $today = new \DateTime();
            if (!is_null($account) && isset($account['id']) && $today <= $account['token_time']) {
                /** @var Society $society */
                $society = Society::findOneById($society);
                if (is_null($society)) return ['status' => 'error', 'message' => 'Société non trouvée'];
                /** @var Theme $theme */
                $theme = Theme::findOneById($theme);
                if (is_null($theme)) return ['status' => 'error', 'Thème non trouvé'];
                $theme_website = $theme->getWebsite();
                $website = new Website();
                $website->setSociety($society);
                $website->setTheme($theme);
                $website->setLayout($theme_website->getLayout());

                $slug = $slugify->slugify($society->getName());
                $sub_domains = isset($this->app->data['app']['settings']['exclude_sub_domain']) ? $this->app->data['app']['settings']['exclude_sub_domain']: [];
                if (in_array($slug, $sub_domains)) return ['status' => 'error', 'message' => 'Le nom de la société n\'est pas valide. Veuillez choisir un autre nom'];

                $url = ($this->app->data['setting']['sub_domain'] == true)
                    ? 'http://' . $slug . '.' . $request->server->get('SERVER_NAME')
                    : $slug;

                $website->setDomain($url);
                $website->setModules($theme_website->getModules());

                $payment_module = ModuleCategory::select('id')->where('slug', 'payment')->get(true);
                if(!is_null($payment_module) && isset($payment_module['id'])) $website->addModule($payment_module['id']);

                $website->setState(-1);
                $website->setRenderSystem('php');
                $website->setData($theme_website->getData());

                $trial_days = isset($this->app->data['app']['settings']['trial_days'])
                    ? $this->app->data['app']['settings']['trial_days']
                    : '+15days';
                $website->setExpirationDate($today->modify($trial_days));

                if (Website::watchAndSave($website)) {
                    if (Account::where('id', $account['id'])->set(['state' => 1, 'token' => NULL, 'token_time' => NULL])) {
                        $domain = (isset($this->app->data['setting']['domain'])) ? $this->app->data['setting']['domain'] : '';
                        $full_url = ($url != $slug) ? $url : $domain . WEBROOT . 'site/' . $url;
                        $content = $this->render('Mail/account_validated', compact('account', 'full_url'));
                        $mail->sendTo($account['email'], 'Confirmation d\'inscription', $content);
                        return ['status' => 'success', 'message' => 'Félicitation ! Votre site vient d\'être créé', 'url' => $full_url];
                    }
                    return ['status' => 'error', 'message' => 'Erreur lors de la mise à jour du compte'];
                }
                return ['status' => 'error', 'message' => 'Erreur lors de la création du site'];
            }
            return ['status' => 'error', 'message' => 'Aucun compte trouvé'];
        }
        return ['status' => 'error', 'message' => 'Token introuvable'];
    }

}