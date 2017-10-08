<?php

namespace Jet\AdminBlock\Controllers;

use Jet\Models\Module;
use JetFire\Framework\System\Request;

/**
 * Class ModuleController
 * @package Jet\AdminBlock\Controllers
 */
class ModuleController extends AdminController
{

    /**
     * @param Request $request
     * @return array
     */
    public function all(Request $request)
    {
        $max = ($request->has('length')) ? (int)$request->query('length') : 10;
        $page = ($request->has('page')) ? (int)$request->query('page') : 1;
        $params = [
            'search' => ($request->has('params') && isset($request->query('params')['search'])) ? $request->query('params')['search'] : '',
            'order' => ($request->has('params') && isset($request->query('params')['order'])) ? $request->query('params')['order'] : [],
            'filter' => ($request->has('params') && isset($request->query('params')['filter'])) ? $request->query('params')['filter'] : [],
        ];
        $response = Module::repo()->listAll($page, $max, $params);
        $pages_count = ceil($response['total'] / $max);

        $modules = [
            'current_page' => $page,
            'count_pages' => $pages_count,
            'count_all' => $response['total'],
            'data' => $response['data']
        ];
        return ['status' => 'success', 'content' => $modules];
    }

    /**
     * @param $category
     * @return array
     */
    public function allByCategory($category)
    {
        return Module::findByCategory($category);
    }
}