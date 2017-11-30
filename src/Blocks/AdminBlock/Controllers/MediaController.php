<?php


namespace Jet\AdminBlock\Controllers;

use Cocur\Slugify\Slugify;
use Jet\AdminBlock\Requests\MediaRequest;
use Jet\Models\Account;
use Jet\Models\Media;
use Jet\Models\Website;
use Jet\AdminBlock\Services\Auth;
use JetFire\Framework\Providers\EventProvider;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class MediaController
 * @package Jet\AdminBlock\Controllers
 */
class MediaController extends AdminController
{

    /**
     * @param MediaRequest $request
     * @param Auth $auth
     * @param $website_id
     * @return array
     */
    public function all(MediaRequest $request, Auth $auth, $website_id)
    {
        $max = ($request->query('max')) ? (int)$request->query('max') : 10;
        $page = ($request->query('page')) ? (int)$request->query('page') : 1;

        $website = null;
        /** @var Account $account */
        $account = Account::findOneById($auth->get('id'));
        if(is_null($account)) return ['status' => 'error', 'message' => 'Impossible de trouver le compte'];
        $exclude = ['account' => $account->getData()];

        if ($website_id != 'global') {
            $this->getWebsite($website_id);
            $exclude['website'] = (!is_null($this->website)) ? $this->getWebsiteData($this->website) : [];
        }

        $params = [
            'search' => ($request->has('params') && isset($request->query('params')['search'])) ? $request->query('params')['search'] : '',
            'order' => ($request->has('params') && isset($request->query('params')['order'])) ? $request->query('params')['order'] : [],
            'filter' => ($request->has('params') && isset($request->query('params')['filter'])) ? $request->query('params')['filter'] : [],
            'status' => $auth->get('status'),
            'account' => $auth->get('id'),
            'level' => $auth->get('status')->level,
            'options' => $exclude
        ];

        if($website_id != 'global'){
            $params['websites'] = $this->websites;
        }

        /* Check if user has permission to get images of this website */
        if ($auth->get('status')->level == 4 && $website_id != 'global' && !$this->isWebsiteOwner($auth, $website_id))
            return ['status' => 'error', 'message' => 'Vous n\'êtes pas le propriétaire du site'];

        $response = Media::repo()->listAll($page, $max, $params);
        $pages_count = ceil($response['total'] / $max);

        $medias = [
            'current_page' => $page,
            'count_pages' => $pages_count,
            'count_all' => $response['total'],
            'data' => $response['data']
        ];
        return ['status' => 'success', 'content' => $medias];
    }

    /**
     * @param MediaRequest $request
     * @param Auth $auth
     * @param Slugify $slugify
     * @param $website_id
     * @return array
     */
    public function create(MediaRequest $request, Auth $auth, Slugify $slugify, $website_id)
    {
        if ($request->method() == 'POST') {
            $response = $request->validate('createRules');
            if ($response === true) {

                $account = Account::findOneById($auth->get('id'));
                if (is_null($account)) return ['status' => 'error', 'message' => 'Impossible de trouver le compte'];

                $global = (!$request->has('global') || $request->get('global') == 'false' || $request->get('global') == false) ? false : true;

                /** @var \Symfony\Component\HttpFoundation\File\UploadedFile $file */
                $file = $request->files->get('file');

                $dir = ($website_id != 'global')
                    ? '/public/media/sites/' . $website_id . '/'
                    : '/public/media/accounts/' . $auth->get('id') . '/';

                $ext = '.' . $file->getClientOriginalExtension();
                $new_name = $slugify->slugify(str_replace($ext, '', $file->getClientOriginalName())) . $ext;

                /* Check if path is already in use */
                if (Media::where('path', $dir . $new_name)->count() > 0)
                    return ['status' => 'error', 'message' => 'Le fichier existe déjà'];

                $media = new Media();
                $media->setTitle($file->getClientOriginalName());
                $media->setPath($dir . $new_name);
                $media->setType($file->getClientMimeType());
                $media->setSize($file->getClientSize());

                if(!$global) $media->setAccount($account);

                if ($website_id != 'global' && !$global) {
                    /** @var Website $website */
                    $website = Website::findOneById($website_id);
                    if (is_null($website)) return ['status' => 'error', 'message' => 'Le site n\'a pas été trouvé'];
                    $media->setWebsite($website);

                    /* Check if user has permission to upload images for this website */
                    if ($auth->get('status')->level == self::USER_LEVEL && !$this->isWebsiteOwner($auth, $website_id)) {
                        return ['status' => 'error', 'message' => 'Vous n\'avez pas les droits pour importer vos fichiers sur un autre site'];
                    }
                }

                if (Media::watchAndSave($media) == true) {
                    $file->move(ROOT . $dir, $new_name);
                    return ['status' => 'success', 'message' => 'Le(s) fichier(s) ont bien été importé(s)'];
                }
                return ['status' => 'error', 'message' => 'Fichier(s) non importé(s)'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Vous n\'avez pas l\'autorisation'];
    }

    /**
     * @param MediaRequest $request
     * @param Auth $auth
     * @param Filesystem $fs
     * @param EventProvider $event
     * @param $website_id
     * @param $id
     * @return array
     */
    public function updateOrCreate(MediaRequest $request, Auth $auth, Filesystem $fs, EventProvider $event, $website_id, $id)
    {
        if ($request->method() == 'PUT' || $request->method() == 'POST') {
            $response = $request->validate('updateRules');
            $delete_old = true;

            if ($response === true) {
                /** @var Media $media */
                $media = Media::findOneById($id);
                if (!is_null($media)) {
                    $values = $request->values();

                    /** @var Account $account */
                    $account = Account::findOneById($auth->get('id'));
                    if (is_null($account)) return ['status' => 'error', 'message' => 'Impossible de trouver le compte'];

                    $ext = explode('.', $values['path']);
                    $path = str_replace(ROOT, '', dirname(ROOT . $values['path']));

                    /* if media website and account is null and we are not super admin, we create a new media in user folder */
                    if($website_id == 'global' && $media->getAccount() != $account && $auth->get('status')->level != self::SUPER_ADMIN_LEVEL) {
                        $path =  '/public/media/accounts/' . $account->getId();
                        $data = $this->excludeData($account->getData(), 'medias', $media->getId());
                        $account->setData($data);
                        Account::watch($account);
                        $media = new Media();
                        $delete_old = false;
                    }

                    $new_path = '/' . trim($path, '/') . '/' . trim($values['name'], '/') . '.' . end($ext);

                    if ($website_id != 'global') {
                        if (!$this->isWebsiteOwner($auth, $website_id))
                            return ['status' => 'error', 'message' => 'Vous n\'avez pas les permissions pour voir ces contenus'];

                        /** @var Website $website */
                        $website = Website::findOneById($website_id);

                        if (is_null($website))
                            return ['status' => 'error', 'message' => 'Le site du média n\'a pas été trouvé'];

                        if ($media->getWebsite() != $website) {
                            $data = $this->excludeData($website->getData(), 'medias', $media->getId());
                            $website->setData($data);
                            Website::watch($website);

                            $media = new Media();
                            $media->setWebsite($website);
                            $new_path = '/public/media/sites/' . $website->getId() . '/' . $values['name'] . '.' . end($ext);
                            $delete_old = false;
                        }
                    }

                    if ($values['path'] != $new_path) {
                        try {
                            $fs->copy(ROOT . $values['path'], ROOT . $new_path);
                        } catch (FileNotFoundException $e) {
                            return ['status' => 'error', 'message' => 'Fichier non trouvé : ' . $e->getPath()];
                        } catch (IOException $e) {
                            return ['status' => 'error', 'message' => 'Impossible de déplacer le fichier : ' . $e->getPath()];
                        }
                        if ($delete_old) {
                            try {
                                $fs->remove(ROOT . $values['path']);
                            } catch (IOException $e) {
                                return ['status' => 'error', 'message' => 'Erreur durant la suppression de l\'ancien fichier. Fichier : ' . $e->getPath()];
                            }
                        }
                        $media->setPath($new_path);
                    }

                    $media->setTitle($values['title']);
                    $media->setAlt($values['alt']);
                    $media->setType($values['type']);
                    $media->setSize($values['size']);

                    if($auth->get('status')->level != self::SUPER_ADMIN_LEVEL)
                        $media->setAccount($account);

                    if (Media::watchAndSave($media)) {
                        $event->emit('updateMedia', ['old_media' => $id, 'new_media' => $media->getId(), 'website' => $website_id]);
                        return ['status' => 'success', 'message' => 'Le fichier a bien été mis à jour', 'resource' => $request->values()];
                    }
                    return ['status' => 'error', 'message' => 'Le fichier n\'a pas pu être mis à jour'];
                }
                return ['status' => 'error', 'message' => 'Le fichier n\'a pas été trouvé'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Vous n\'avez pas l\'autorisation'];
    }

    /**
     * @param MediaRequest $request
     * @param Auth $auth
     * @param Filesystem $fs
     * @param $website_id
     * @return array
     */
    public function delete(MediaRequest $request, Auth $auth, Filesystem $fs, $website_id)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {

            $data = [];
            $website = $account = null;
            if ($website_id != 'global') {
                if ($auth->get('status')->level == self::USER_LEVEL && !$this->isWebsiteOwner($auth, $website_id))
                    return ['status' => 'error', 'message' => 'Le site du média n\'a pas été trouvé'];
                /** @var Website $website */
                $website = Website::findOneById($website_id);
                if (is_null($website))
                    return ['status' => 'error', 'message' => 'Le site du média n\'a pas été trouvé'];

                $data = $website->getData();
            }else{
                /** @var Account $account */
                $account = Account::findOneById($auth->get('id'));
                if(is_null($account))
                    return ['status' => 'error', 'message' => 'Impossible de trouver votre compte'];
                $data = $account->getData();
            }

            $medias = Media::repo()->findById($request->get('ids'));
            $ids = [];
            $delete_files = [];

            foreach ($medias as $media) {
                $data = $this->removeData($data, 'medias', $media['id']);
                if (
                    (is_null($media['account']) && $auth->get('status')->level == self::SUPER_ADMIN_LEVEL) ||
                    (!is_null($media['account']) &&
                        (
                            $media['account']['id'] == $auth->get('id') ||
                            $media['account']['status']['level'] > $auth->get('status')->level
                        )
                    )
                ) {
                    $ids[] = $media['id'];
                    $delete_files[] = $media;
                } else {
                    $data = $this->excludeData($data, 'medias', $media['id']);
                }
            }

            if (!is_null($website)) {
                $website->setData($data);
                Website::watchAndSave($website);
            }else{
                $account->setData($data);
                Account::watchAndSave($account);
            }

            if (Media::destroy($ids)) {
                foreach ($delete_files as $media) {
                    try {
                        $fs->remove(ROOT . $media['path']);
                    } catch (IOException $e) {
                        return ['status' => 'error', 'message' => 'Erreur durant la supression du fichier : ' . $e->getPath()];
                    }
                }
                return ['status' => 'success', 'message' => 'Le(s) media(s) ont bien été supprimé(s)'];
            }
            return ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Le(s) media(s) n\'ont pas pu être supprimé(s)'];
    }

    /**
     * @param MediaRequest $request
     * @param Auth $auth
     * @return array
     */
    public function compressViaTinyPng(MediaRequest $request, Auth $auth)
    {
        if ($request->method() == 'POST' && $auth->check() && $request->exists('ids')) {
            \Tinify\setKey("8OIMJdhJ2FAglexsAVPPfIFNumrKigPC");
            foreach ($request->get('ids') as $id) {
                /** @var Media $media */
                $media = Media::findOneById($id);
                if (is_null($media)) {
                    return ['status' => 'error', 'message' => 'Le fichier est introuvable'];
                } else {
                    $acceptedFormat = ['image/png', 'image/jpg', 'image/jpeg'];
                    if (in_array($media->getType(), $acceptedFormat)) {
                        if (file_exists($path = $media->getFullPath())) {
                            $source = \Tinify\fromFile($path);
                            $source->toFile($path);
                            $media->setSize(filesize($path));
                            Media::watch($media);
                        } else
                            return ['status' => 'error', 'message' => 'Le fichier "' . $media->getName() . '" est introuvable'];
                    } else
                        return ['status' => 'error', 'message' => 'Le format du fichier "' . $media->getName() . '" n\'est pas accepté'];
                }
            }
            Media::save();
            return ['status' => 'success', 'message' => 'Le(s) fichier(s) ont bien été compressé(s)'];
        }
        return ['status' => 'error', 'message' => 'Erreur Http'];
    }

    /**
     * @param MediaRequest $request
     * @return array
     */
    public function findOneBy(MediaRequest $request)
    {
        return ($request->exists('params'))
            ? ['resource' => Media::findOneBy($request->get('params'))]
            : [];
    }

    /**
     * @param $value
     * @return null
     */
    public function renderField($value)
    {
        return (!empty($value) && !is_null($value))
            ? (is_numeric($value)) ? Media::findOneById($value) : $value
            : null;
    }

}