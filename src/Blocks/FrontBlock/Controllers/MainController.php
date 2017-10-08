<?php

namespace Jet\FrontBlock\Controllers;

use Jet\Models\Template;
use JetFire\Framework\System\Controller;
use JetFire\Framework\System\View;

/**
 * Class MainController
 * @package Jet\FrontBlock\Controllers
 */
class MainController extends Controller
{
    
    /**
     * @param $current_url
     * @param $website_url
     * @return array|bool
     */
    protected function urlMatchWebsiteUrl($current_url, $website_url)
    {
        $url = preg_replace('#:([\w]+)#', '([^/]+)', $website_url);
        if (preg_match('#^' . $url . '$#', $current_url, $args)) {
            array_shift($args);
            $params = [];
            preg_replace_callback('#:([\w]+)#', function ($matches) use (&$args, &$params) {
                $params[$matches[0]] = $args[0];
                return array_shift($args);
            }, $website_url);
            return $params;
        }
        return false;
    }


    /**
     * @param Template $template
     * @param array $data
     * @return mixed
     */
    public function _render(Template $template, $data = [])
    {
        /** @var View $view */
        return ($template->getType() == "file")
            ? $this->_renderView($template->getContent(), $template->getScope(), $data)
            : $this->_renderView(['content' => $template->getContent()], $template->getScope(), $data);
    }

    /**
     * @param $path
     * @param $scope
     * @param array $data
     * @return mixed
     */
    private function _renderView($path, $scope, $data = [])
    {
        $dir = ($scope == 'global') ? ROOT . '/src/Blocks/FrontBlock/Views/' : ROOT . '/src/Themes/';
        $view = $this->app->get('response')->getView();
        $view->setPath($dir);
        $view->setExtension($this->app->data['template_extension']);
        (!is_array($path) && is_file($dir . $path . $this->app->data['template_extension']))
            ? $view->setTemplate($path)
            : $view->setContent($path);
        $flash = $this->app->get('session')->getSession()->allFlash();
        foreach ($flash as $key => $content)
            $data[$key] = $content;
        $view->addData($data);
        return $this->app->get('template')->getTemplate()->render($view);
    }

    /**
     * @param Template $template
     * @param $path
     * @param array $data
     */
    public function _renderContent(Template $template, $path, $data = [])
    {
        /** @var View $view */
        $view = $this->app->get('response')->getView();
        if ($template->getType() == "file") {
            $path = (is_file(ROOT . '/' . $path . $template->getContent() . $this->app->data['template_extension']))
                ? $path : 'src/Themes/';
            $view->setContent(null);
            $view->setTemplate($template->getContent());
        } else {
            $path = ($template->getScope() == 'specified') ? 'src/Themes/' : $path;
            $view->setTemplate(null);
            $view->setContent(['content' => $template->getContent()]);
        }

        $view->setPath($path);
        $view->setExtension($this->app->data['template_extension']);

        $flash = $this->app->get('session')->getSession()->allFlash();
        foreach ($flash as $key => $content)
            $data[$key] = $content;
        $view->addData($data);
        return $this->app->get('template')->getTemplate()->render($view);
    }
}