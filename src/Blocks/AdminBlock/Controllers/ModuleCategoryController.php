<?php

namespace Jet\AdminBlock\Controllers;

use Doctrine\Common\DataFixtures\Executor\ORMExecutor;
use Doctrine\Common\DataFixtures\Loader;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\Tools\SchemaTool;
use Github\Client;
use Jet\Models\ModuleCategory;
use JetFire\Db\Model;
use JetFire\Framework\System\Request;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use ZipArchive;

/**
 * Class ModuleCategoryController
 * @package Jet\AdminBlock\Controllers
 */
class ModuleCategoryController extends AdminController
{

    /**
     * @param Request $request
     * @return array
     */
    public function all(Request $request)
    {
        /* Check for new module in folder without upload in admin */
        if (isset($this->app->data['app']['settings']['module_check']) && $this->app->data['app']['settings']['module_check'])
            $this->checkModuleInFolder();

        $max = ($request->has('length')) ? (int)$request->query('length') : 10;
        $page = ($request->has('page')) ? (int)$request->query('page') : 1;
        $params = [
            'search' => ($request->has('params') && isset($request->query('params')['search'])) ? $request->query('params')['search'] : '',
            'order' => ($request->has('params') && isset($request->query('params')['order'])) ? $request->query('params')['order'] : [],
            'filter' => ($request->has('params') && isset($request->query('params')['filter'])) ? $request->query('params')['filter'] : [],
        ];
        $response = ModuleCategory::repo()->listAll($page, $max, $params);
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
     * @param Request $request
     * @param Loader $loader
     * @return array
     */
    public function create(Request $request, Loader $loader)
    {
        if ($request->method() == 'POST') {
            $file = $request->files->get('module');
            $module = str_replace('.zip', '', $file->getClientOriginalName());
            $module = str_replace(' ', '', ucwords(str_replace('-', ' ', $module)));
            $dir = ROOT . '/src/Modules/';
            if (is_dir($dir)) {
                $new_file_name = rand(0000, 9999);
                try {
                    $file->move($dir, $new_file_name . '.zip');
                } catch (FileException $e) {
                    return 'Impossible de déplacer le module dans le dossier voulu : ' . $e;
                }
                $response = $this->extractZip($dir . $new_file_name . '.zip', $dir);
                if ($response == true) {
                    if ($this->appBlocksAction('update', $module)) {
                        $config = json_decode(file_get_contents($dir . $module . '/config.json'), true);
                        if ($this->dbSchemaAction('create', $config)) {
                            if (is_dir($dir . $module . '/Fixtures'))
                                $this->loadDbData($loader, new ORMPurger(), $dir . $module . '/Fixtures');
                            return ['status' => 'success', 'message' => 'Le module a bien été installé'];
                        }
                        return ['status' => 'error', 'message' => 'Les tables du module n\'ont pas pu être importées dans la base'];
                    }
                    return ['status' => 'error', 'message' => 'Le module n\'a pas pu être ajouté dans le fichier de configuration "app.inc.php"'];
                }
                return $response;
            }
            return ['status' => 'error', 'message' => 'Le module n\'a pas pu être installé'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisé'];
    }


    /**
     * @param $id
     * @return array
     */
    public function getWithReadme($id)
    {
        /** @var ModuleCategory $module */
        $module = ModuleCategory::findOneById($id);
        if (!is_null($module)) {
            $dir = ROOT . '/src/Modules/' . str_replace(' ', '', $module->getName());
            $readme = (is_file($dir . '/readme.md'))
                ? nl2br(file_get_contents($dir . '/readme.md'))
                : '';
            return ['status' => 'success', 'resource' => $module, 'readme' => $readme];
        }
        return ['status' => 'error', 'message' => 'Module inexistant'];
    }

    /**
     * @param $id
     * @return array
     */
    public function read($id)
    {
        /** @var ModuleCategory $module */
        $module = ModuleCategory::findOneById($id);
        if (!is_null($module))
            return ['status' => 'success', 'resource' => $module];
        return ['status' => 'error', 'message' => 'Module inexistant'];
    }

    /**
     *
     */
    public function checkUpdate()
    {
        $modules = ModuleCategory::orm('pdo')->all();
        /** @var ModuleCategory $module */
        foreach ($modules as $module) {
            if ($this->updateAvailable($module['name'])['status'] === 'success')
                ModuleCategory::update($module['id'], ['update_available' => 1]);
        }
    }

    /**
     * @param Request $request
     * @param Filesystem $fs
     * @param Loader $loader
     * @param $id
     * @return array
     */
    public function update(Request $request, Filesystem $fs, Loader $loader, $id)
    {
        if ($request->method() == 'PUT') {
            /** @var ModuleCategory $module */
            $module = ModuleCategory::findOneById($id);
            if (!is_null($module)) {
                $response = $this->updateAvailable($module->getName());
                if ($response['status'] === 'success') {
                    $dir = $response['dir'];
                    $uploaded = file_put_contents($dir . '/Update.zip', fopen($response['new_config']['download-url'], 'r'));
                    if ($uploaded != false) {
                        if ($this->extractZip($dir . '/Update.zip', $dir) == true) {
                            try {
                                $fs->chmod($dir . '/' . $module->getName(), 0755);
                            } catch (IOException $e) {
                                return ['status' => 'error', 'message' => 'Impossible de changer les permissions pour le module. Exception levée : ' . $e];
                            }
                            $this->removeFolder($dir . '/' . $module->getName());
                            try {
                                $fs->rename($dir . '/' . $response['new_config']['name'] . '-master', $dir . '/' . $module->getName());
                                if ($this->dbSchemaAction('update', $response['new_config'])) {
                                    if ($fs->exists($dir . '/' . $module->getName() . '/Fixtures'))
                                        $this->loadDbData($loader, new ORMPurger(), $dir . '/' . $module->getName() . '/Fixtures');
                                    return ['status' => 'success', 'message' => 'Le module a bien été mis à jour', 'resource' => ModuleCategory::findOneById($id)];
                                }
                                return ['status' => 'error', 'message' => 'Le schéma de la base de donnée n\' pas pu être mis à jour'];
                            } catch (IOException $e) {
                                return ['status' => 'error', 'message' => 'L\'import du module a échoué. Exception levée : ' . $e];
                            }
                        }
                    }
                    return ['status' => 'error', 'message' => 'L\'import du module a échoué.'];
                }
                return $response;
            }
            return ['status' => 'error', 'message' => 'Module inconnu'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param Request $request
     * @return array
     */
    public function delete(Request $request)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {
            $modules = ModuleCategory::findById($request->get('ids'));
            foreach ($modules as $module) {
                $dir = ROOT . '/src/Modules/' . $module->getName();
                if (is_file($dir . '/config.json')) {
                    $config = json_decode(file_get_contents($dir . '/config.json'), true);
                    if ($this->dbSchemaAction('drop', $config)) {
                        if ($this->appBlocksAction('drop', $module->getName())) {
                            $this->removeFolder($dir);
                            ModuleCategory::removeWatch($module);
                        } else {
                            return ['status' => 'error', 'message' => 'Le fichier app.inc.php n\'a pas pu être mis à jour'];
                        }
                    } else {
                        return ['status' => 'error', 'message' => 'Les données des modules n\'ont pa pu être supprimées de la base'];
                    }
                } else {
                    return ['status' => 'error', 'message' => 'Le fichier de config n\'a pas été trouvé dans le module : ' . $module->getName()];
                }
            }
            return (ModuleCategory::save())
                ? ['status' => 'success', 'message' => 'Les modules ont bien été supprimés']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     *
     */
    private function checkModuleInFolder()
    {
        $files = scandir(ROOT . '/src/Modules');
        if (count($files) - 2 != ModuleCategory::count()) {
            for ($i = 2; $i < count($files); ++$i) {
                if (ModuleCategory::where('name', $files[$i])->count() == 0) {
                    $dir = ROOT . '/src/Modules';
                    if ($this->appBlocksAction('update', $files[$i])) {
                        $config = json_decode(file_get_contents($dir . '/' . $files[$i] . '/config.json'), true);
                        if ($this->dbSchemaAction('create', $config)) {
                            $fixtures = $dir . '/' . $files[$i] . '/Fixtures';
                            if (is_dir($fixtures))
                                $this->loadDbData(new Loader(), new ORMPurger(), $fixtures);
                        }
                    }
                }
            }
        }
    }

    /**
     * @param Loader $loader
     * @param ORMPurger $purger
     * @param $dir
     */
    private function loadDbData(Loader $loader, ORMPurger $purger, $dir)
    {
        $loader->loadFromDirectory($dir);
        $executor = new ORMExecutor(Model::em(), $purger);
        $executor->execute($loader->getFixtures(), true);
    }

    /**
     * @param $action
     * @param $config
     * @return bool
     * @throws \Doctrine\ORM\Tools\ToolsException
     */
    private function dbSchemaAction($action, $config)
    {
        if (count($config['models']) > 0) {
            $tool = new SchemaTool(Model::em());
            $classes = [];
            foreach ($config['models'] as $model)
                $classes[] = Model::em()->getClassMetadata($model);
            switch ($action) {
                case 'create':
                    $tool->createSchema($classes);
                    break;
                case 'update':
                    $tool->updateSchema($classes, true);
                    break;
                case 'drop':
                    $tool->dropSchema($classes);
            }
        }
        return true;
    }

    /**
     * @param $action
     * @param $block
     * @return bool
     */
    private function appBlocksAction($action, $block)
    {
        $app_file = $this->app->data['app'];
        $has_init = false;
        if (is_file($module_file = ROOT . '/src/Modules/' . $block . '/Config/init.php')) {
            $module_file = include($module_file);
            if (isset($module_file['app'])) $has_init = true;
        }
        switch ($action) {
            case 'update':
                if ($has_init)
                    $app_file = array_merge($app_file, $module_file['app']);
                else {
                    $app_file['blocks'] = [$block . 'Module' =>
                            [
                                'path' => 'src/Modules/' . $block . '/',
                                'namespace' => '\\Jet\\Modules\\' . $block,
                                'view_dir' => 'src/Modules/' . $block . '/Views/',
                            ]
                        ] + $app_file['blocks'];
                }
                isset($app_file['blocks']['Admin']['prefix'])
                    ? $app_file['blocks'][$block . 'Module']['prefix'] = $app_file['blocks']['Admin']['prefix']
                    : $app_file['blocks'][$block . 'Module']['subdomain'] = $app_file['blocks']['Admin']['subdomain'];
                break;
            case 'drop':
                if ($has_init) $app_file = array_diff($app_file, $module_file['app']);
                else unset($app_file['blocks'][$block . 'Module']);
                break;
        }
        if (isset($app_file['scripts']) && isset($app_file['scripts']['npm']) && $app_file['scripts']['npm']['enable'])
            exec($app_file['scripts']['npm']['build']);
        return (file_put_contents(ROOT . '/config/app.inc.php', '<?php return ' . var_export($app_file, true) . ';'))
            ? true : false;
    }

    /**
     * @param $module
     * @return array
     */
    private function updateAvailable($module)
    {
        $dir = ROOT . '/src/Modules';
        if (is_file($dir . '/' . $module . '/' . 'config.json')) {
            $config = json_decode(file_get_contents($dir . '/' . $module . '/' . 'config.json'), true);
            $client = new Client();
            $client->authenticate('a73530211dd714986b3176dea6b3587afc3e3760', null, Client::AUTH_HTTP_TOKEN);
            $files = $client->api('repo')->contents()->show($config['username'], $config['name']);
            $file = $client->api('repo')->contents()->show($config['username'], $config['name'], $this->getConfigPath($files));
            if (!is_null($file)) {
                $new_config = json_decode(base64_decode($file['content']), true);
                return ($new_config['version'] != $config['version'])
                    ? ['status' => 'success', 'dir' => $dir, 'new_config' => $new_config]
                    : ['status' => 'error', 'message' => 'Le module est à jour'];
            }
            return ['status' => 'error', 'message' => 'Erreur sur le fichier de configuration distant'];
        }
        return ['status' => 'error', 'message' => 'Fichier de configuration introuvable'];
    }

    /**
     * @param $files
     * @return null
     */
    private function getConfigPath($files)
    {
        foreach ($files as $key => $file) {
            if ($file['name'] == 'config.json')
                return $file['path'];
        }
        return null;
    }

    /**
     * @param $file
     * @param $dir
     * @return array|bool
     */
    private function extractZip($file, $dir)
    {
        $zip = new ZipArchive();
        $res = $zip->open($file);
        if ($res === true) {
            $zip->extractTo($dir . '/');
            $zip->close();
            unlink($file);
            return true;
        }
        return ['status' => 'error', 'message' => 'Impossible de dézipper le fichier'];
    }

    /**
     * @param $dir
     */
    private function removeFolder($dir)
    {
        if (is_dir($dir)) {
            $it = new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS);
            $files = new RecursiveIteratorIterator($it, RecursiveIteratorIterator::CHILD_FIRST);
            foreach ($files as $file) {
                ($file->isDir())
                    ? rmdir($file->getRealPath())
                    : unlink($file->getRealPath());
            }
            rmdir($dir);
        }
    }
}