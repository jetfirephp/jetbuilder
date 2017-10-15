<?php

namespace Jet\AdminBlock\Controllers;

use Jet\Models\Account;
use Jet\Models\Theme;
use Jet\Models\Website;
use Jet\Services\Auth;
use JetFire\Framework\System\Controller;

/**
 * Class AdminController
 * @package Jet\AdminBlock\Controllers
 */
class AdminController extends Controller
{
    /**
     * @param Auth $auth
     * @return mixed
     */
    public function index(Auth $auth)
    {
        if ($auth->hasRemember()) {
            $account = Account::where('id', $auth->getRemember('id'))->where('token', $auth->getRemember('token'))->get(true);
            if (!is_null($account) && isset($account['id'])) $auth->log($account->_getTable());
        }

        $env = (isset($this->app->data['setting']['environment'])) ? $this->app->data['setting']['environment'] : 'prod';
        $app_name = (isset($this->app->data['setting']['name'])) ? $this->app->data['setting']['name'] : 'JetFire';
        $domain = (isset($this->app->data['setting']['domain'])) ? $this->app->data['setting']['domain'] : '';
        $admin_domain = (isset($this->app->data['setting']['admin_domain'])) ? $this->app->data['setting']['admin_domain'] : '';

        $modules = ModuleCategory::select('id','name')->get();
        if(is_null($modules)) $modules = [];
        $custom_field_type = (isset($this->app->data['app']['settings']['custom_field_type'])) ? $this->app->data['app']['settings']['custom_field_type'] : [];

        return $this->render('admin_layout', [
            'app_name' => $app_name,
            'env' => $env,
            'auth' => $auth->get(),
            'domain' => rtrim($domain, '/'),
            'admin_domain' => rtrim($admin_domain, '/'),
            'public_path' => rtrim(WEBROOT, '/'),
            'modules' => $modules->getResults(),
            'field_types' => $custom_field_type,
            'settings' => $this->getSettings(),
            'libs' => $this->app->data['app']['admin_libs']
        ]);
    }

    /**
     * @return mixed
     */
    private function getSettings()
    {
        $trial_days = isset($this->app->data['app']['settings']['trial_days'])
            ? new \DateTime($this->app->data['app']['settings']['trial_days'])
            : new \DateTime('+15days');
        $today = new \DateTime();
        $settings['trial_days'] = $today->diff($trial_days);
        return $settings;
    }

    /**
     * @return array
     */
    public function getPanelSummary()
    {
        $users = Account::repo()->countUser();
        $websites = Website::repo()->countActiveWebsite();
        $themes = Theme::where('state', 1)->count();
        $modules = ModuleCategory::count();
        return compact('users', 'websites', 'themes', 'modules');
    }

} 