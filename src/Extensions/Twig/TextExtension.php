<?php
/**
 * This file is part of Twig.
 *
 * (c) 2009 Fabien Potencier
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jet\Extensions\Twig;

use JetFire\Framework\App;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

/**
 * @author Henrik Bjornskov <hb@peytz.dk>
 */
class TextExtension extends Twig_Extension
{

    /**
     * @var App
     */
    private $app;


    /**
     * FrontExtension constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('truncate', 'twig_truncate_filter', array('needs_environment' => true)),
            new Twig_SimpleFilter('wordwrap', 'twig_wordwrap_filter', array('needs_environment' => true)),
            new Twig_SimpleFilter('trans', function ($text) {
                $request = $this->app->get('request');
                $locale = isset($this->app->data['app']['locales'][$request->getLocale()]['locale'])
                    ? $this->app->data['app']['locales'][$request->getLocale()]['locale'] : 'fr_FR';
                return $this->app->get('translator')->translate($text, [], 'public', $locale);
            }),
        );
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            new Twig_SimpleFunction('str_pad', function ($input, $pad_length, $pad_string = '', $pad_type = STR_PAD_RIGHT) {
                return str_pad($input, $pad_length, $pad_string, $pad_type);
            }),
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Text';
    }
}