<?php

namespace Jet\Services\AppProvider;

use JetFire\Framework\Providers\RoutingProvider;

class AppRoutingProvider extends RoutingProvider
{

    public function setAppRoutes()
    {
        foreach ($this->app->data['app']['blocks'] as $key => $block) {
            if (is_array($block)) {
                $path = (substr($block['path'], -4) == '.php') ? $block['path'] : rtrim($block['path'], '/') . '/routes.php';
                $block['view_dir'] = isset($block['view_dir']) ? $block['view_dir'] : rtrim($block['path'], '/') . '/Views';
                $options = isset($block['prefix'])
                    ? ['block' => $block['path'], 'view_dir' => $block['view_dir'], 'ctrl_namespace' => $block['namespace'] . '\Controllers', 'prefix' => $block['prefix']]
                    : ['block' => $block['path'], 'view_dir' => $block['view_dir'], 'ctrl_namespace' => $block['namespace'] . '\Controllers'];

                if (isset($block['subdomain'])) $options['subdomain'] = $block['subdomain'];
                if (isset($block['protocol'])) $options['protocol'] = $block['protocol'];
                if (isset($block['params'])) $options['params'] = $block['params'];

                $this->collection->addRoutes(ROOT . '/' . $path, $options);
            }
        }
    }

}