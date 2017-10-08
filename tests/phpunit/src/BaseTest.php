<?php

namespace Jet\Test;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use JetFire\Db\Model;
use JetFire\Framework\App;
use JetFire\Framework\System\Controller;
use PHPUnit\Framework\TestCase;

/**
 * Class BaseTest
 * @package Jet\Test
 */
abstract class BaseTest extends TestCase
{

    /**
     * @var App
     */
    protected $app = null;
    /**
     * @var Controller
     */
    protected $controller = null;
    /**
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em = null;

    /**
     *
     */
    public function setUp()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/admin';
        $_SERVER['SERVER_NAME'] = 'webzy.dev';
        if(is_null($this->app)) {
            $this->app = new App();
            $this->app->load(include ROOT . '/tests/phpunit/config/boot.inc.php');
            $this->app->boot();
        }
        if(is_null($this->controller))
            $this->controller = $this->app->get('JetFire\Framework\System\Controller');
        if(is_null($this->em)) $this->em = Model::em();

    }

    /**
     * @param $controller
     * @param $method
     * @param array $methodArgs
     * @param array $ctrlArgs
     * @param array $classInstance
     * @return mixed
     */
    protected function callMethod($controller, $method, $methodArgs = [], $ctrlArgs = [], $classInstance = [])
    {
        return $this->controller->callMethod($controller, $method, $methodArgs, $ctrlArgs, $classInstance);
    }

    /**
     *
     */
    protected function loadDbData()
    {
        $loader = new Loader();
        $purger = new ORMPurger();
        $blocs = $this->app->data['app']['blocks'];
        $loader->loadFromDirectory(ROOT. '/src/DataFixtures/');
        foreach ($blocs as $bloc)
            if(is_dir(ROOT. '/' . $bloc['path'] . 'Fixtures'))
                $loader->loadFromDirectory(ROOT. '/' . $bloc['path'] . 'Fixtures/');
        $executor = new ORMExecutor(Model::em(), $purger);
        $executor->execute($loader->getFixtures(), true);
    }

    /**
     * @param $action
     * @return bool
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    protected function dbAction($action)
    {
        $tool = new SchemaTool(Model::em());
        $classes = [];
        $meta = Model::em()->getMetadataFactory()->getAllMetadata();
        foreach ($meta as $m)
            $classes[] = Model::em()->getClassMetadata($m->getName());
        switch ($action) {
            case 'create':
                $tool->createSchema($classes);
                $this->loadDbData();
                break;
            case 'update':
                $tool->updateSchema($classes, true);
                break;
            case 'drop':
                $tool->dropSchema($classes);
        }
        return true;
    }

    /**
     * @param null $table
     */
    protected function truncateTable($table = null){
        $connection = Model::em()->getConnection();
        if(is_null($table)) {
            $schemaManager = $connection->getSchemaManager();
            $tables = $schemaManager->listTables();
            $query = '';

            foreach ($tables as $table) {
                $name = $table->getName();
                $query .= 'TRUNCATE ' . $name . ';';
            }
        }else{
            $query = 'TRUNCATE ' . $table . ';';
        }
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=0;');
        $connection->executeQuery($query);
        $connection->executeQuery('SET FOREIGN_KEY_CHECKS=1;');
    }
}