<?php

namespace Jet\ApiBlock\Controllers;

use Jet\Models\Account;
use Jet\Models\Log;
use JetFire\Framework\Providers\LogProvider;
use JetFire\Framework\System\Controller;
use JetFire\Framework\System\Request;

/**
 * Class LogController
 * @package Jet\ApiBlock\Controllers
 */
class LogController extends Controller
{

    /**
     * @param Request $request
     */
    public function create(Request $request)
    {
        if($request->exists(['auth','message'])) {
            $account = Account::findOneById($request->get('auth'));
            if (!is_null($account)) {
                $log = new Log();
                $log->setChannel('activity');
                $log->setLevelName('INFO');
                $log->setLevel(200);
                $log->setMessage($request->get('message'));
                $log->setAccount($account);
                Log::watchAndSave($log);
            }
        }
    }

    /**
     * @param LogProvider $logProvider
     */
    public function clean(LogProvider $logProvider)
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval('P3M'));
        if(!Log::repo()->clean($date->format('Y-m-d H:i:s'))){
            $logProvider->getLogger('main')->error('Impossible de nettoyer les logs avant la date : ' . $date->format('Y-m-d H:i:s'));
        }
    }

}