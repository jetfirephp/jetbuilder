<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\LogRequest;
use Jet\Models\Log;

/**
 * Class LogController
 * @package Jet\AdminBlock\Controllers
 */
class LogController extends AdminController
{

    /**
     * @param LogRequest $request
     * @return array
     */
    public function all(LogRequest $request)
    {
        $max = ($request->has('length')) ? (int)$request->query('length') : 10;
        $start = ($request->has('start')) ? (int)$request->query('start') : 1;
        $params = [
            'order' => ($request->has('order')) ? $request->query('order') : [],
            'search' => ($request->has('search')) ? $request->query('search')['value'] : '',
            'filter' => ($request->has('filter')) ? $request->query('filter') : []
        ];
        $response = Log::repo()->listAll($start, $max, $params);
        $logs = [
            'draw' => (int)$request->query('draw'),
            'recordsTotal' => $response['total'],
            'recordsFiltered' => $response['total'],
            'data' => $response['data']
        ];
        return $logs;
    }

    /**
     * @param LogRequest $request
     * @return array
     */
    public function listBy(LogRequest $request)
    {
        $params = [
            'filter' => ($request->has('filter')) ? $request->get('filter') : [],
            'order' => ($request->has('order')) ? $request->get('order') : [],
            'max' => ($request->has('max')) ? $request->get('max') : 10
        ];
        return Log::repo()->listBy($params);
    }

    /**
     * @param LogRequest $request
     * @return array
     */
    public function delete(LogRequest $request)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            return (Log::destroy($request->get('ids')))
                ? ['status' => 'success', 'message' => 'Les logs ont bien été supprimées']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Les logs n\'ont pas pu être supprimées'];
    }


}