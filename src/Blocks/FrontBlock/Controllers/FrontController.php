<?php

namespace Jet\FrontBlock\Controllers;

use Jet\FrontBlock\Requests\FrontRequest;
use Jet\Models\Content;
use Jet\Models\Page;
use Jet\Models\Route;
use Jet\Models\Website;

/**
 * Class FrontController
 * @package Jet\FrontBlock\Controllers
 */
class FrontController extends MainController
{

    /**
     * @var Website
     */
    public $website;
    /**
     * @var Page
     */
    public $page;
    /**
     * @var array
     */
    protected $data = [];
    /**
     * @var array
     */
    private $routes = [];
    /**
     * @var null
     */
    private $contents = null;
    /**
     * @var array
     */
    private $fields = [];
    /**
     * @var array
     */
    private $custom_field_callback = [];

    /**
     * @param FrontRequest $request
     * @return mixed|null
     */
    public function dispatch(FrontRequest $request)
    {
        $websites = Website::select('id', 'url')->get();
        if(!is_null($websites)) {
            foreach ($websites as $website) {
                if (!empty($website['url'])) {
                    $response = $this->urlMatchWebsiteUrl($request->getCurrentUrl(), rtrim($website['url'], '/'));
                    if (is_array($response)) {
                        $request->getAttributes()->add('request_parameters', $response);
                        $this->website = Website::findOneById($website['id']);
                        return $this->findPage($request, $this->website);
                    }
                }
            }
        }
        return $this->notFound();
    }

    /**
     * @param FrontRequest $request
     * @param Website $website
     * @return mixed|null
     */
    private function findPage(FrontRequest $request, Website $website)
    {
        if ($website->getState() != 0) {
            /** @var Page $page */
            $page = $this->getCurrentPage($request->getRoute()->getUrl(), $request->getMethod());
            if (!is_null($page)) {
                $middlewares = $page->getRoute()->getMiddlewares();
                $this->callMiddleware('before', $request, $middlewares);
                $response = $this->renderView($page);
                $this->callMiddleware('after', $request, $middlewares);
                return $response;
            }
        }
        return $this->notFound();
    }

    /**
     * @param $request
     * @param $action
     * @param array $middlewares
     */
    private function callMiddleware($request, $action, $middlewares = [])
    {
        if (isset($middlewares[$action])) {
            foreach ($middlewares[$action] as $middleware) {
                $callback = explode('@', $middleware);
                if (class_exists($callback[0]) && method_exists($callback[0], $callback[1])) {
                    $this->callMethod($callback[0], $callback[1], null, ['request' => $request]);
                }
            }
        }
    }

    /**
     * @param $request_url
     * @param $method
     * @return array|null
     */
    private function getCurrentPage($request_url, $method)
    {
        $request_url = str_replace('/@site@/' . $this->website->getUrl(), '', $request_url);
        if ($request_url == '') $request_url = '/';
        $this->routes = Route::repo()->getWebsiteRoutes($this->website);
        return $this->urlMatchRoute($request_url, $method, $this->routes);
    }

    /**
     * @param $request_url
     * @param $method
     * @param $routes
     * @return Page|null
     */
    private function urlMatchRoute($request_url, $method, $routes)
    {
        foreach ($routes as $route) {
            $args = [];
            $url = preg_replace_callback('#:([\w]+)#', function ($match) use ($route, &$args) {
                array_push($args, $match[1]);
                if (!is_null($route['argument']) && isset($route['argument'][$match[1]])) {
                    $route['argument'][$match[1]] = str_replace('(', '(?:', $route['argument'][$match[1]]);
                    return '(' . $route['argument'][$match[1]] . ')';
                }
                return '([^/]+)';
            }, $route['url']);
            if (substr($route['url'], -1) == '*') {
                $pos = strpos($route['url'], '*');
                if (substr($request_url, 0, $pos) == substr($route['url'], 0, $pos)) {
                    $page = Page::repo()->getPageByRoute($this->website, $route['id']);
                    $this->data['route_params'] = [];
                    return $page;
                }
            }
            if (preg_match('#^' . $url . '$#', $request_url, $params) && in_array($method, $route['method'])) {
                array_shift($params);
                $page = Page::repo()->getPageByRoute($this->website, $route['id']);
                $this->data['route_params'] = array_combine($args, $params);
                return $page;
            }

        }
        return null;
    }

    /**
     * @param Page $page
     * @return mixed|null
     */
    public function renderView(Page $page)
    {
        if ($page && $page->isPublished()) {
            $this->data = $this->getPageData($page);
            $theme = $this->website->getTheme();

            $path = $this->website->getLayout()->getPath();
            if (is_file($file = ROOT . '/src/Blocks/PublicBlock/' . $theme->getDirectory() . $path)) {
                $this->data['website_layout'] = file_get_contents($file);
            } elseif (is_file($file = ROOT . '/src/Themes/' . $theme->getDirectory() . $path)) {
                $this->data['website_layout'] = file_get_contents($file);
            }

            $this->page = $page;
            return $this->_render($page->getLayout(), ['data' => $this->data]);
        }
        return $this->notFound();
    }

    /**
     * @param $page
     * @return array
     */
    private function getPageData(Page $page)
    {
        return [
            'route_params' => $this->data['route_params'],
            'website' => $this->website,
            'website_layout' => '',
            'page' => $page,
            'contents' => function () use ($page) {
                if (is_null($this->contents)) {
                    $this->contents = Content::repo()->frontRender($page->getId(), $this->website);
                }
                return $this->contents;
            },
            'libraries' => $page->getLibraries(),
            'stylesheets' => $page->getStylesheets(),
        ];
    }

    /*
     * Page render functions
     */

    /**
     * @param string $args
     * @param string $type
     * @return mixed|string
     */
    public function renderPage($args = '', $type = 'all')
    {
        if (!empty($args)) {
            if (!$this->page instanceof Page || ($args != $this->page->getId() && $args != $this->page->getTitle())) {
                $this->page = is_numeric($args) ? Page::findOneById($args) : Page::findOneByTitle($args);
                $this->website = $this->page->getWebsite();
                $this->getPageData($this->page);
            }
        }
        return str_replace(
            ['<< ', ' >>', '<% ', ' %>'],
            ['{{ ', ' }}', '{% ', ' %}'],
            $this->getPageView(call_user_func($this->data['contents']), $type)
        );
    }

    /**
     * @param $contents
     * @param string $type
     * @return string
     */
    private function getPageView($contents, $type = 'all')
    {
        $view = '';
        $oldSection = 0;
        $count = count($contents);
        foreach ($contents as $key => $content) {
            if ($type == 'all' || ($type == 'page' && !is_null($content->getPage())) || ($type == 'global' && is_null($content->getPage()))) {
                $section = $content->getSection();

                if (!is_null($section) && $section->getId() != $oldSection)
                    $view .= ($oldSection == 0)
                        ? '<div class="' . $section->getContainer() . '"><div id="' . $section->getSectionId() . '" class="' . $section->getSectionClass() . '" style="' . $section->getStyle() . '">'
                        : '</div></div><div class="' . $section->getContainer() . '"><div id="' . $section->getSectionId() . '" class="' . $section->getSectionClass() . '" style="' . $section->getStyle() . '">';

                $data = array_merge(['id' => '', 'class' => '', 'style' => ''], $content->getData());
                $view .= (empty($data['class'])) ? $this->callCallback($content) : '<div id="' . $data['id'] . '" class="' . $data['class'] . '" style="' . $data['style'] . '">' . $this->callCallback($content) . '</div>';

                $oldSection = !is_null($section) ? $section->getId() : null;
                if ($count - 1 == $key && !is_null($section) && $section->getId() != $oldSection)
                    $view = $view . '</div></div>';
            }
        }
        return (empty($view)) ? '' : $view;
    }

    /*
     * Content render functions
     */

    /**
     * @param $block
     * @param array $options
     * @return mixed
     */
    public function renderContent($block, $options = [])
    {
        return str_replace(
            ['<< ', ' >>', '<% ', ' %>'],
            ['{{ ', ' }}', '{% ', ' %}'],
            $this->getContentView(call_user_func($this->data['contents']), $block, $options)
        );
    }

    /**
     * @param $contents
     * @param $block
     * @param array $options
     * @return string
     */
    private function getContentView($contents, $block, $options = [])
    {
        /** @var Content $content */
        foreach ($contents as $content) {
            if ($content->getBlock() == $block) {
                $data = array_merge($content->getData(), ['id' => '', 'class' => '', 'style' => '']);
                return (empty($data['class'])) ? $this->callCallback($content, $options) : '<div id="' . $data['id'] . '" class="' . $data['class'] . '" style="' . $data['style'] . '">' . $this->callCallback($content) . '</div>';
            }
        }
        return '';
    }

    /*
     * Data render function
     */

    /**
     * @param $table
     * @param $keys
     * @return mixed
     * @internal param $block
     */
    public function renderData($table, $keys)
    {
        if (class_exists($table) && method_exists(($repo = call_user_func([$table, 'repo'])), 'retrieveData'))
            return call_user_func_array([$repo, 'retrieveData'], [$this->website, $keys]);
        return '';
    }

    /**
     * @param $table
     * @param $id
     * @param $keys
     * @return array|mixed
     */
    public function getTable($table, $id, $keys)
    {
        if (class_exists($table) && method_exists(($repo = call_user_func([$table, 'repo'])), 'retrieveData'))
            return call_user_func_array([$repo, 'retrieveData'], [$id, $keys]);
        return '';
    }

    /*
     * Custom field render function
     */

    /**
     * @param $name
     * @param string $key
     * @return string
     */
    public function renderCustomField($name, $key = 'value')
    {
        $pt = (isset($this->app->data['app']['settings']['publication_type'])) ? $this->app->data['app']['settings']['publication_type'] : [];
        $cft = array_merge([
            'value' => ['id' => 'global', 'renderCallback' => '\\Jet\\AdminBlock\\Controllers\\WebsiteController@listCustomFields']
        ], $pt);

        if ($key == 'page' && $this->page instanceof Page) $key = 'page@' . $this->page->getId();
        $row_key = ($key == 'value') ? 'rows' : 'rows@' . $key;
        $call_type = explode('@', $key);
        $this->callCustomFieldCallback($cft, $call_type[0], $key);
        if (!empty($this->fields) && isset($this->fields[$call_type[0]]) && !empty($this->fields[$call_type[0]])) {
            $result = $this->renderField($this->fields[$call_type[0]], $name, $key, $row_key);
            if (!is_null($result)) return $result;
        }
        return '';
    }

    /**
     * @param array $types
     * @param $type
     * @param $key
     */
    private function callCustomFieldCallback($types = [], $type, $key)
    {
        if (!isset($this->fields[$type]) || empty($this->fields[$type])) {
            if (isset($types[$type]) && isset($types[$type]['renderCallback'])) {
                $callback = explode('@', $types[$type]['renderCallback']);
                $this->fields[$type] = $this->callContent($callback[0], $callback[1], null, ['key' => $key]);
            }
        }
    }

    /**
     * @param $custom_fields
     * @param $name
     * @param $key
     * @param $row_key
     * @return array|string
     */
    private function renderField($custom_fields, $name, $key, $row_key)
    {
        $this->custom_field_callback = isset($this->app->data['app']['settings']['custom_field_callback']) ? $this->app->data['app']['settings']['custom_field_callback'] : [];
        foreach ($custom_fields as $custom_field) {
            foreach ($custom_field['fields'] as $field) {
                if ($field['name'] == $name) {
                    if ($field['type'] == 'repeater') {
                        $contents = [];
                        $this->getSub($contents, $field['content'][$row_key], $field['children'], $key, $row_key);
                        return $contents;
                    }
                    return $this->callFieldCallback($field, $key, isset($field['content'][$key]) ? $field['content'][$key] : null);
                }
            }
        }
        return null;
    }

    /**
     * @param $field
     * @param $key
     * @param $value
     * @return mixed|null
     */
    private function callFieldCallback($field, $key, $value = null)
    {
        if (isset($this->custom_field_callback[$field['type']])) {
            $callback = explode('@', $this->custom_field_callback[$field['type']]);
            if (isset($callback[1]) && !is_null($value))
                return $this->callContent($callback[0], $callback[1], null, ['field' => $field, 'key' => $key, 'value' => $value]);
        }
        return $value;
    }

    /**
     * @param $contents
     * @param $rows
     * @param $children
     * @param $key
     * @param $row_key
     */
    private function getSub(&$contents, $rows, $children, $key, $row_key)
    {
        foreach ($rows as $row)
            $contents[$row] = $this->recursiveMerge($children, [$row], $key, $row_key);
    }

    /**
     * @param $children
     * @param $old_row
     * @param $key
     * @param $row_key
     * @return array
     */
    private function recursiveMerge($children, $old_row, $key, $row_key)
    {
        $contents = [];
        foreach ($children as $field) {
            if ($field['type'] == 'repeater') {
                $contents[$field['name']] = [];
                $rows = $field['content'][$row_key];
                foreach ($old_row as $index) $rows = $rows[$index];
                foreach ($rows as $row) {
                    $x = $old_row;
                    $x[] = $row;
                    $contents[$field['name']][$row] = $this->recursiveMerge($field['children'], $x, $key, $row_key);
                }
            } else if (isset($field['content'][$key])) {
                $val = $field['content'][$key];
                foreach ($old_row as $index) $val = $val[$index];
                $contents[$field['name']] = $this->callFieldCallback($field, $key, $val);
            }
        }
        return $contents;
    }

    /*
     * Other functions
     */

    /**
     * @param null $path
     * @param array $params
     * @param string $subdomain
     * @return mixed|null
     */
    public function link($path = null, $params = [], $subdomain = '')
    {
        if (empty($this->routes))
            $this->routes = Route::repo()->getWebsiteRoutes($this->websites, $this->getWebsiteData($this->website));
        foreach ($this->routes as $route) {
            if ($route['name'] == $path) {
                $url = $route['url'];
                if (isset($route['subdomain']) && !is_null($route['subdomain']))
                    $url = str_replace('{subdomain}', $subdomain, $route['subdomain']);
                foreach ($params as $key => $value) $url = str_replace(':' . $key, $value, $url);
                return (substr($this->website->getDomain(), 0, 4) === 'http')
                    ? $url
                    : WEBROOT . 'site/' . $this->website->getDomain() . $url;
            }
        }
        return '';
    }

    /**
     * @param $url
     * @param null $website
     * @return string
     */
    public function fullUrl($url, $website = null)
    {
        $website = (is_null($website) || !$website instanceof Website) ? $this->website : $website;
        $domain = (isset($this->app->data['setting']['domain'])) ? $this->app->data['setting']['domain'] : '';
        if (filter_var($url, FILTER_VALIDATE_URL) || substr($url, 0, 4) === "http") return $url;
        return (substr($website->getDomain(), 0, 4) === "http")
            ? rtrim($website->getDomain(), '/') . '/' . ltrim($url, '/')
            : $domain . WEBROOT . 'site/' . $website->getDomain() . $url;
    }


    /**
     * @param Content $content
     * @param array $options
     * @return mixed|null
     */
    protected function callCallback(Content $content, $options = [])
    {
        if (!$content instanceof Content)
            throw new \UnexpectedValueException('Argument 1 passed to Jet\FrontBlock\Controllers\FrontController::callCallback() must be an instance of Jet\Models\Content');
        $callback = explode('@', $content->getModule()->getCallback());
        if (class_exists($callback[0]) && method_exists($callback[0], $callback[1]))
            return $this->callContent($callback[0], $callback[1], $content, $options);
        return null;
    }

    /**
     * @param $controller
     * @param $method
     * @param $content
     * @param array $args
     * @return mixed
     */
    public function callContent($controller, $method, $content = null, $args = [])
    {
        $classInstances = ['Jet\Models\Page' => $this->page, 'Jet\Models\Route' => $this->page->getRoute(), 'Jet\Models\Website' => $this->website];
        $args = array_merge(['content' => $content, 'data' => $this->data], $args);
        return $this->callMethod($controller, $method, $args, $args, $classInstances);
    }
}