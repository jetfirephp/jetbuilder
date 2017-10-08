<?php

namespace Jet\ApiBlock\Controllers;

use Jet\Models\ModuleCategory;
use JetFire\Framework\System\Controller;
use Github\Client;

class ModuleController extends Controller
{
    /**
     *
     */
    public function checkUpdate(){
        $modules = ModuleCategory::orm('pdo')->all();
        /** @var ModuleCategory $module */
        foreach ($modules as $module) {
            if ($this->updateAvailable($module['name'])['status'] === 'success') {
                ModuleCategory::update($module['id'], ['update_available' => 1]);
            }
        }
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

}