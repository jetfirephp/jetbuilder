<?php

namespace Jet\Services;

use Assetic\Asset\AssetCollection;
use Assetic\Asset\FileAsset;
use Assetic\Asset\GlobAsset;
use Assetic\Asset\HttpAsset;
use Assetic\AssetManager;
use Assetic\AssetWriter;
use Assetic\Factory\AssetFactory;
use Assetic\FilterManager;
use JetFire\Framework\App;
use JetFire\Framework\Providers\Provider;

/**
 * Class AssetProvider
 * @package Jet\Services
 */
class AssetProvider extends Provider
{

    /**
     * @var AssetManager
     */
    private $asset_manager;
    /**
     * @var FilterManager
     */
    private $filter_manager;
    /**
     * @var AssetFactory
     */
    private $factory;

    /**
     * AssetProvider constructor.
     * @param App $app
     */
    public function __construct(App $app)
    {
        parent::__construct($app);
        $this->asset_manager = new AssetManager();
        $this->filter_manager = new FilterManager();
    }


    /**
     * @return AssetManager
     */
    public function getAssetManager()
    {
        return $this->asset_manager;
    }

    /**
     * @param AssetManager $asset_manager
     */
    public function setAssetManager($asset_manager)
    {
        $this->asset_manager = $asset_manager;
    }

    /**
     * @return FilterManager
     */
    public function getFilterManager()
    {
        return $this->filter_manager;
    }


    /**
     * @param $filters
     */
    private function setFilterManager($filters)
    {
        foreach ($filters as $key => $filter) {
            $this->filter_manager->set($key, $filter);
        }
    }


    /**
     * @return AssetFactory
     */
    public function getFactory()
    {
        return $this->factory;
    }

    /**
     * @param AssetFactory $factory
     */
    public function setFactory($factory)
    {
        $this->factory = $factory;
    }

    /**
     * @param $config
     * @param $assets
     */
    public function init($config, $assets)
    {
        foreach ($assets as $key => $block) {
            $this->asset_manager->set($key . '_css', $this->setCollection($block, 'css', $key, $config['debug']));
            $this->asset_manager->set($key . '_js', $this->setCollection($block, 'js', $key, $config['debug']));
        }

        $this->setFilterManager($config['filters']);

        $this->factory = new AssetFactory(ROOT);
        $this->factory->setAssetManager($this->asset_manager);
        $this->factory->setFilterManager($this->filter_manager);
        $this->factory->setDebug($config['debug']);

        foreach ($config['workers'] as $worker) {
            $this->factory->addWorker($worker);
        }
        $writer = new AssetWriter($config['dist']);
        $writer->writeManagerAssets($this->asset_manager);
    }

    /**
     * @param $block
     * @param $type
     * @param $key
     * @param bool $debug
     * @return AssetCollection
     */
    private function setCollection($block, $type, $key, $debug = false)
    {
        $collection = [];
        if ($debug) {
            $collection[] = new AssetCollection($this->app->get('debug_toolbar')->getDebugBarRenderer()->getAsseticCollection($type));
        }
        if (isset($block[$type])) {
            foreach ($block[$type] as $asset) {
                if (strpos($asset, '*') !== false) {
                    $collection[] = new GlobAsset(ROOT . '/' . ltrim($asset, '/'));
                } elseif (substr($asset, 0, 4) === 'http') {
                    $collection[] = new HttpAsset($asset);
                } elseif (is_file(ROOT . '/' . ltrim($asset, '/'))) {
                    $collection[] = new FileAsset(ROOT . '/' . ltrim($asset, '/'));
                } else {
                    $collection[] = $asset;
                }
            }
        }
        $collection = new AssetCollection($collection, isset($block['filters']) ? $block['filters'] : []);
        $collection->setTargetPath(isset($block['targetPath'][$type]) ? $block['targetPath'][$type] : '/' . $key . '.' . $type);
        return $collection;
    }


    /**
     * @param $key
     * @param array $filters
     */
    public function render($key, $filters = [])
    {
        $key = !is_array($key) ? [$key] : $key;
        $asset = $this->factory->createAsset($key, $filters);
    }
}