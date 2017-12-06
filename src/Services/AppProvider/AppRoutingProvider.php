<?php

namespace Jet\Services\AppProvider;

use JetFire\Framework\Providers\RoutingProvider;

/**
 * Class AppRoutingProvider
 * @package Jet\Services\AppProvider
 */
class AppRoutingProvider extends RoutingProvider
{

    private $block_keys = array();

    /**
     *
     */
    public function setAppRoutes()
    {
        $i = 0;
        foreach ($this->app->data['app']['blocks'] as $key => $block) {
            if (is_array($block)) {
                $this->block_keys[$key] = [
                    'index' => $i,
                    'block' => $block['path']
                ];
                $path = (substr($block['path'], -4) == '.php') ? $block['path'] : rtrim($block['path'], '/') . '/routes.php';
                $route = is_file(ROOT. '/' . $path) ? ROOT. '/' . $path : [];
                $block['view_dir'] = isset($block['view_dir']) ? $block['view_dir'] : rtrim($block['path'], '/') . '/Views';
                $options = isset($block['prefix'])
                    ? ['block' => $block['path'], 'view_dir' => $block['view_dir'], 'ctrl_namespace' => $block['namespace'] . '\Controllers', 'prefix' => $block['prefix']]
                    : ['block' => $block['path'], 'view_dir' => $block['view_dir'], 'ctrl_namespace' => $block['namespace'] . '\Controllers'];

                if (isset($block['subdomain'])) $options['subdomain'] = $block['subdomain'];
                if (isset($block['protocol'])) $options['protocol'] = $block['protocol'];
                if (isset($block['params'])) $options['params'] = $block['params'];

                $this->collection->addRoutes($route, $options);
                ++$i;
            }
        }
        $this->overrideRoutes($this->collection->getRoutes(), $this->app->data['app']['blocks']);
    }

    /**
     * @param $routes
     * @param $blocks
     */
    private function overrideRoutes($routes, $blocks)
    {
        $blocks_count = count($blocks);
        for ($i = 0; $i < $blocks_count; ++$i) {
            if (isset($routes['routes_' . $i]['::include::']['before']) || isset($routes['routes_' . $i]['::include::']['after'])) {
                $action = isset($routes['routes_' . $i]['::include::']['before'])
                    ? 'before'
                    : 'after';
                $key = $this->block_keys[$routes['routes_' . $i]['::include::'][$action]]['index'];
                foreach ($routes['routes_' . $i]['::include::']['routes'] as $route => $content){
                    if(isset($routes['routes_' . $key][$route])){
                        $routes['routes_' . $key][$route] = $content;
                        unset($routes['routes_' . $i]['::include::']['routes'][$route]);
                    }
                }
                $merge_route = ($action == 'before')
                    ? array_merge($routes['routes_' . $i]['::include::']['routes'], $routes['routes_' . $key])
                    : array_merge($routes['routes_' . $key], $routes['routes_' . $i]['::include::']['routes']);
                $to_args = [
                    'routes' => $merge_route,
                    'block' => $routes['block_' . $key],
                    'view_dir' => $routes['view_dir_' . $key],
                    'ctrl_namespace' => $routes['ctrl_namespace_' . $key],
                    'prefix' => $routes['prefix_' . $key],
                    'subdomain' => $routes['subdomain_' . $key],
                    'protocol' => $routes['protocol_' . $key],
                    'params' => $routes['params_' . $key],
                ];
                $this->collection->setRoutes($to_args, $key);
                unset($routes['routes_' . $i]['::include::']);
                $from_args = [
                    'routes' => $routes['routes_' . $i],
                    'block' => $routes['block_' . $i],
                    'view_dir' => $routes['view_dir_' . $i],
                    'ctrl_namespace' => $routes['ctrl_namespace_' . $i],
                    'prefix' => $routes['prefix_' . $i],
                    'subdomain' => $routes['subdomain_' . $i],
                    'protocol' => $routes['protocol_' . $i],
                    'params' => $routes['params_' . $i],
                ];
                $this->collection->setRoutes($from_args, $i);
            }
            $this->app->data['routes'][$routes['block_' . $i]] = $routes['routes_' . $i];
        }
    }

}