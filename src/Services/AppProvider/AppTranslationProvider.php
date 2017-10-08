<?php

namespace Jet\Services\AppProvider;

use JetFire\Framework\App;
use JetFire\Framework\Providers\Provider;
use JetFire\Framework\System\Request;
use Symfony\Component\Translation\Loader\ArrayLoader;
use Symfony\Component\Translation\MessageSelector;
use Symfony\Component\Translation\Translator;

/**
 * Class AppTranslationProvider
 * @package Jet\Services\AppProvider
 */
class AppTranslationProvider extends Provider
{

    /**
     * @var Translator
     */
    private $translator;

    /**
     * @var array
     */
    private $locales;

    /**
     * @param App $app
     * @param Request $request
     * @param MessageSelector $selector
     * @param $locales
     * @param string $default_locale
     */
    public function __construct(App $app, Request $request, MessageSelector $selector, $locales, $default_locale = 'en_GB')
    {
        parent::__construct($app);
        $this->locales = $locales;
        $this->translator = new Translator($default_locale, $selector);
        $this->translator->addLoader('array', new ArrayLoader());
        $this->setLoaders();
        $this->setLocale($request, $default_locale);
    }

    /**
     * @param Request $request
     * @param string $locale
     */
    public function setLocale(Request $request, $locale = 'en_GB')
    {
        $languages = explode(',', $request->getServer()->get('HTTP_ACCEPT_LANGUAGE'));
        $this->app->data['_browser_locale'] = isset($languages[0]) ? str_replace('-', '_', $languages[0]) : 'en_GB';
        $this->app->data['_browser_lang_code'] = isset($languages[1]) ? $languages[1] : 'en';
        $this->app->data['_default_locale'] = $locale;
    }

    /**
     *
     */
    protected function setLoaders()
    {
        foreach ($this->locales as $domain => $locales) {
            foreach ($locales as $locale => $path) {
                if (is_file($path)) {
                    $this->translator->addResource('array', include $path, $locale, $domain);
                }
            }
        }
    }

    /**
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }


    /**
     * @param $id
     * @param array $parameters
     * @param null $domain
     * @param null $locale
     * @return string
     */
    public function translate($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $domain, $locale);
    }
}