<?php

namespace Jet\Events;

use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Doctrine\Common\EventSubscriber;
use Jet\Services\Auth;
use JetFire\Framework\Providers\EventProvider;

/**
 * Class ActivityListener
 * @package Jet\Events
 */
class ActivityListener implements EventSubscriber
{

    /**
     * @var Auth
     */
    private $auth;
    /**
     * @var EventProvider
     */
    private $event;
    /**
     * @var array
     */
    private $tables = [
        'Jet\Models\Account' => [
            'message' => 'getAccountDetail',
            'table' => 'un utilisateur'
        ],
        'Jet\Models\Address' => [
            'message' => 'getDefaultDetail',
            'table' => 'une adresse'
        ],
        'Jet\Models\AdminCustomField' => [
            'message' => 'getDefaultDetail',
            'table' => 'un champ personnalisé'
        ],
        'Jet\Models\Media' => [
            'message' => 'getDefaultDetail',
            'table' => 'un média'
        ],
        'Jet\Models\Page' => [
            'message' => 'getTitle',
            'table' => 'une page'
        ],
        'Jet\Models\Society' => [
            'message' => 'getDefaultDetail',
            'table' => 'une société'
        ],
        'Jet\Models\Website' => [
            'message' => 'getDefaultDetail',
            'table' => 'un site'
        ],
        'Jet\Models\Template' => [
            'message' => 'getTitle',
            'table' => 'un template'
        ],
        'Jet\Modules\Post\Models\Post' => [
            'message' => 'getTitle',
            'table' => 'un article'
        ],
        'Jet\Modules\Post\Models\PostCategory' => [
            'message' => 'getName',
            'table' => 'une catégorie d\'article'
        ],
        'Jet\Modules\Navigation\Models\NavigationItem' => [
            'message' => 'getDefaultDetail',
            'table' => 'le menu'
        ],
    ];

    /**
     * ActivityListener constructor.
     * @param Auth $auth
     * @param EventProvider $event
     */
    public function __construct(Auth $auth, EventProvider $event)
    {
        $this->auth = $auth;
        $this->event = $event;
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postPersist(LifecycleEventArgs $eventArgs)
    {
        $this->log($eventArgs, 'a créé');
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $this->log($eventArgs, 'a mis à jour');
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     */
    public function postRemove(LifecycleEventArgs $eventArgs)
    {
        $this->log($eventArgs, 'a supprimé');
    }

    /**
     * @param LifecycleEventArgs $eventArgs
     * @param $action
     */
    private function log(LifecycleEventArgs $eventArgs, $action)
    {
        if($this->auth->check()) {
            $entity = $eventArgs->getEntity();
            $class = get_class($entity);
            if (isset($this->tables[$class])) {
                $message = call_user_func_array([$this, $this->tables[$class]['message']], [$entity]);
                $message = ' ' . $action . ' <span class="text-primary">' . $this->tables[$class]['table'] . '</span> ' . $message;

                /* Asynchronous */
                $this->event->emit('createLog', ['auth' => $this->auth->get('id'), 'message' => $message]);

                /* Or Synchronous */
                /* $account = Account::findOneById($this->auth->get('id'));
                 if (!is_null($account)) {
                     $log = new Log();
                     $log->setChannel('activity');
                     $log->setLevelName('INFO');
                     $log->setLevel(200);
                     $log->setMessage($message);
                     $log->setAccount($account);
                     $eventArgs->getEntityManager()->persist($log);
                     $eventArgs->getEntityManager()->flush($log);
                 }*/
            }
        }
    }

    /**
     * @param $entity
     * @return string
     */
    private function getDefaultDetail($entity)
    {
        return ': (' . $entity->getId() . ')';
    }

    /**
     * @param $entity
     * @return string
     */
    private function getAccountDetail($entity)
    {
        return ': (' . $entity->getEmail() . ')';
    }

    /**
     * @param $entity
     * @return string
     */
    private function getTitle($entity)
    {
        return ': (' . $entity->getTitle() . ')';
    }

    /**
     * @param $entity
     * @return string
     */
    private function getName($entity)
    {
        return ': (' . $entity->getName() . ')';
    }

    /**
     * @return array
     */
    public function getSubscribedEvents()
    {
        return [Events::postPersist, Events::postUpdate, Events::postRemove];
    }

}