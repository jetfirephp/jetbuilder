<?php

namespace Jet\AdminBlock\Controllers;


use Jet\AdminBlock\Requests\ContentRequest;
use Jet\Models\Content;
use Jet\Models\Page;
use Jet\Models\Section;
use Jet\Models\Template;
use Jet\Models\Website;
use Jet\AdminBlock\Services\Auth;

class ContentController extends AdminController
{

    /**
     * @param $website
     * @param $page
     * @return array
     */
    public function getPageContents($website, $page)
    {
        return (!$this->getWebsite($website))
            ? ['status' => 'error', 'message' => 'Impossible de trouver le site']
            : Content::repo()->getPageContents($page, $this->websites, $this->getWebsiteData($this->website));
    }

    /**
     * @param $website
     * @return array
     */
    public function getGlobalContents($website)
    {
        return (!$this->getWebsite($website))
            ? ['status' => 'error', 'message' => 'Impossible de trouver le site']
            : Content::repo()->getGlobalContents($this->websites, $this->getWebsiteData($this->website));
    }

    /**
     * @param ContentRequest $request
     * @param Auth $auth
     * @param $website
     * @param $page
     * @return array
     */
    public function updateOrCreate(ContentRequest $request, Auth $auth, $website, $page)
    {
        if ($request->method() == 'PUT' || $request->method() == 'POST') {

            if (!$this->isWebsiteOwner($auth, $website))
                return ['status' => 'error', 'message' => 'Vous n\'avez pas les permissions pour supprimer ces contenus'];

            if (!$request->exists('contents'))
                return ['status' => 'success', 'message' => 'Aucuns contenus trouvés'];

            $contents = $request->get('contents');
            if (count($contents) > 0) {

                if (!$this->getWebsite($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site'];

                foreach ($contents as $value) {
                    $response = $request->validate($request->updateRules(), $request::$messages, $value);
                    if ($response !== true) return $response;
                    else {
                        if (isset($value['id'])) {
                            /** @var Content $content */
                            $content = Content::findOneById($value['id']);
                            if (is_null($content)) return ['status' => 'error', 'message' => 'Contenu non trouvé'];

                            if ($content->getWebsite() != $this->website) {
                                $data = $this->excludeData($this->website->getData(), 'contents', $content->getId());
                                $this->website->setData($data);
                                Website::watch($this->website);
                                $content = new Content();
                                $content->setWebsite($this->website);
                            }

                        } else {
                            $content = new Content();
                            $content->setWebsite($this->website);
                        }

                        $content->setName($value['name']);
                        $content->setBlock($value['block']);
                        if (isset($value['data'])) $content->setData($value['data']);

                        if ($page != 'global') {
                            $page = Page::findOneById($page);
                            if (is_null($page)) return ['status' => 'error', 'message' => 'Page non trouvée'];
                            $content->setPage($page);
                        } else
                            $content->setPage(null);

                        $module = Module::findOneById($value['module']['id']);
                        if (is_null($module)) return ['status' => 'error', 'message' => 'Module non trouvé'];
                        $content->setModule($module);

                        if (isset($value['template']) && isset($value['template']['id']) && !empty($value['template']['id'])) {
                            $template = Template::findOneById($value['template']['id']);
                            if (!is_null($template)) $content->setTemplate($template);
                        }

                        if (isset($value['section']) && isset($value['section']['id'])) {
                            $section = Section::findOneById($value['section']['id']);
                            if (is_null($section)) return ['status' => 'error', 'message' => 'Section non trouvé'];
                            $content->setSection($section);
                        }
                        Content::watch($content);
                    }
                }

                if (Content::save()) {
                    $contents = ($page == 'global')
                        ? Content::repo()->getGlobalContents($this->websites, $this->getWebsiteData($this->website))
                        : Content::repo()->getPageContents($page->getId(), $this->websites, $this->getWebsiteData($this->website));
                    return ['status' => 'success', 'message' => 'Les contenus ont bien été mis à jour', 'resource' => $contents];
                } else
                    return ['status' => 'error', 'message' => 'Erreur lors de la mise à jour des contenus'];
            }
            return ['status' => 'success', 'message' => 'Aucuns contenus trouvés'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param ContentRequest $request
     * @param Auth $auth
     * @param $website
     * @return array
     */
    public function delete(ContentRequest $request, Auth $auth, $website)
    {
        if ($request->method() == 'DELETE' && $request->exists('ids')) {

            if (!$this->isWebsiteOwner($auth, $website))
                return ['status' => 'error', 'message' => 'Vous n\'avez pas les permission pour supprimer ces contenus'];

            $ids = [];
            $website = Website::findOneById($website);
            if (is_null($website)) return ['status' => 'error', 'message' => 'Impossible de trouver le site web'];
            $data = $website->getData();

            $contents = Content::repo()->findContentsById($request->get('ids'));

            foreach ($contents as $content) {
                if ($content['website']['id'] == $website->getId())
                    $ids[] = $content['id'];
                else
                    $data = $this->excludeData($data, 'contents', $content['id']);
            }

            $website->setData($data);
            Website::watchAndSave($website);

            return (Content::destroy($ids))
                ? ['status' => 'success', 'message' => 'Les contenus ont bien été supprimés']
                : ['status' => 'error', 'message' => 'Erreur lors de la suppression'];
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

}