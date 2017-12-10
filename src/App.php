<?php

namespace Jet;

/**
 * Class App
 * @package Jet
 */
class App
{

    /**
     * @var \JetFire\Framework\App
     */
    private $systemApp;

    /**
     * App constructor.
     */
    public function __construct()
    {
        $this->systemApp = new \JetFire\Framework\App();
    }

    /**
     * @description run main application
     */
    public function run()
    {
        $this->init();
        $this->systemApp->fire();
    }

    /**
     * @param array $config
     * @return array
     */
    public function load($config = [])
    {
        foreach ($config['include_files'] as $key => $file) {
            $config['include_files'][$key] = (!is_array($file) && is_file($file)) ? $this->systemApp->parseFile($file) : $file;
        }
        return $config;
    }

    /**
     * @return mixed
     */
    public function getConsole()
    {
        $this->init();
        return $this->systemApp->get('console');
    }

    /**
     *
     */
    private function init()
    {
        $config = $this->load(include ROOT . '/config/boot.inc.php');
        $config['include_files'] = $this->initExternalConfig($config['include_files'], ROOT . '/src/Blocks/');
        $config['include_files'] = $this->initExternalConfig($config['include_files'], ROOT . '/src/Themes/');
        $config['include_files'] = $this->initExternalConfig($config['include_files'], ROOT . '/src/Modules/');
        $this->systemApp->load($config);
        $this->systemApp->boot();
    }

    /**
     * @param array $include_files
     * @param $path
     * @return array
     */
    protected function initExternalConfig($include_files = [], $path)
    {
        $folders = scandir($path);
        foreach ($folders as $folder) {
            $dir = $path . $folder;
            if (is_dir($dir) && is_file($dir . '/Config/init.php')) {
                $init = include $dir . '/Config/init.php';
                $include_files = $this->mergeConfig($include_files, $init);
            }
        }
        return $include_files;
    }

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public function mergeConfig(array &$array1, array &$array2)
    {
        $merged = $array1;
        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset ($merged [$key]) && is_array($merged [$key])) {
                if (is_int(current(array_keys($value)))) $value = array_merge($merged [$key], $value);
                $merged [$key] = $this->mergeConfig($merged [$key], $value);
            } else {
                $merged [$key] = $value;
            }
        }
        return $merged;
    }

}