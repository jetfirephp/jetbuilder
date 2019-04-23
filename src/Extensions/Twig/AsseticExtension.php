<?php

namespace Jet\Extensions\Twig;

use Assetic\AssetWriter;
use Assetic\Extension\Twig\AsseticFilterFunction;
use Assetic\Extension\Twig\AsseticFilterInvoker;
use Assetic\Extension\Twig\AsseticTokenParser;
use Assetic\Extension\Twig\ValueContainer;
use Assetic\Factory\AssetFactory;
use Assetic\ValueSupplierInterface;
use JetFire\Framework\App;
use Twig_SimpleFunction;

/**
 * Class AsseticExtension
 * @package Jet\Extensions\Twig
 */
class AsseticExtension extends \Twig_Extension implements \Twig_Extension_GlobalsInterface
{
    /**
     * @var
     */
    protected $factory;
    /**
     * @var
     */
    protected $functions;
    /**
     * @var
     */
    protected $valueSupplier;

    /**
     * @var App
     */
    private $app;

    /**
     * DefaultExtension constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->init($this->app->get('asset')->getFactory());

    }

    /**
     * @param AssetFactory $factory
     * @param array $functions
     * @param ValueSupplierInterface|null $valueSupplier
     */
    public function init(AssetFactory $factory, $functions = array(), ValueSupplierInterface $valueSupplier = null)
    {
        $this->factory = $factory;
        $this->functions = array();
        $this->valueSupplier = $valueSupplier;

        foreach ($functions as $function => $options) {
            if (is_integer($function) && is_string($options)) {
                $this->functions[$options] = array('filter' => $options);
            } else {
                $this->functions[$function] = $options + array('filter' => $function);
            }
        }

        assetic_init($this->factory);
    }

    /**
     * @return array|\Twig_TokenParserInterface[]
     */
    public function getTokenParsers()
    {
        return array(
            new AsseticTokenParser($this->factory, 'javascripts', 'js/*.js'),
            new AsseticTokenParser($this->factory, 'stylesheets', 'css/*.css'),
            new AsseticTokenParser($this->factory, 'image', 'images/*', true),
        );
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        $functions = array(
            new Twig_SimpleFunction('asset', function ($value, $full_path = false) {
                return $this->app->get('Jet\Services\Asset')->getPublicPath($value, $full_path);
            }),
            new Twig_SimpleFunction('assetic_javascripts', function ($inputs = array(), $filters = array(), array $options = array()) {
                return assetic_javascripts($inputs, $filters, $options);
            }),
            new Twig_SimpleFunction('assetic_stylesheets', function ($inputs = array(), $filters = array(), array $options = array()) {
                return assetic_stylesheets($inputs, $filters, $options);
            }),
            new Twig_SimpleFunction('assetic_image', function ($input, $filters = array(), array $options = array()) {
                return assetic_image($input, $filters, $options);
            }),
            new Twig_SimpleFunction('assets', function ($libs = [], $type) {
                $this->app->get('Jet\Services\Asset')->combineAsset($libs, $type);
                if($this->app->data['setting']['environment'] == 'dev'){
                    echo ($type == 'css')
                        ? $this->app->get('debug_toolbar')->getDebugBarRenderer()->renderHead()
                        : $this->app->get('debug_toolbar')->getDebugBarRenderer()->render();
                }
            }),
        );

        foreach ($this->functions as $function => $filter) {
            $functions[] = new AsseticFilterFunction($function);
        }

        return $functions;
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return array(
            'assetic' => array(
                'debug' => $this->factory->isDebug(),
                'vars' => null !== $this->valueSupplier ? new ValueContainer($this->valueSupplier) : array(),
            ),
        );
    }

    /**
     * @param $function
     * @return AsseticFilterInvoker
     */
    public function getFilterInvoker($function)
    {
        return new AsseticFilterInvoker($this->factory, $this->functions[$function]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'assetic';
    }
}
