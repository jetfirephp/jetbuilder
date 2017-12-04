<?php
namespace Jet\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Jet\Models\Account;
use Jet\Models\Address;
use Jet\Models\AdminCustomField;
use Jet\Models\Content;
use Jet\Models\CustomField;
use Jet\Models\CustomFieldRule;
use Jet\Models\Language;
use Jet\Models\Media;
use Jet\Models\Page;
use Jet\Models\Role;
use Jet\Models\Route;
use Jet\Models\Section;
use Jet\Models\Template;
use Jet\Models\Theme;
use Jet\Models\Website;

/**
 * Class LoadFixture
 * @package Jet\Services
 */
trait LoadFixture{

    protected $customFieldCallback = [
        'repeater' => 'getCustomFieldRepeater',
        'media' => 'getCustomFieldMedia'
    ];

    protected $contentCallback = [];

    /**
     * @return array
     */
    private function getCustomFieldCallback()
    {
        return $this->customFieldCallback;
    }

    /**
     * @param $key
     * @param $callback
     */
    protected function addCustomFieldCallback($key, $callback)
    {
        $this->customFieldCallback[$key] = $callback;
    }

    /**
     * @return array
     */
    private function getContentCallback()
    {
        return $this->contentCallback;
    }

    /**
     * @param $key
     * @param $callback
     */
    protected function addContentCallback($key, $callback)
    {
        $this->contentCallback[$key] = $callback;
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadRole(ObjectManager $manager)
    {
        foreach($this->data as $key => $role){
            $status = (Role::where('title', $role['title'])->count() == 0) ? new Role() : Role::findOneByTitle($role['title']);
            $status->setTitle($role['title']);
            $status->setLevel($role['level']);
            $manager->persist($status);
            $this->addReference($key, $status);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadAccount(ObjectManager $manager)
    {
        foreach ($this->data as $key => $data) {
            $account = (Account::where('email', $data['email'])->count() == 0)
                ? new Account() : Account::findOneByEmail($data['email']);
            $account->setFirstName($data['firstName']);
            $account->setLastName($data['lastName']);
            $account->setPassword(password_hash($data['password'], PASSWORD_BCRYPT));
            $account->setEmail($data['email']);
            $account->setPhone($data['phone']);
            $account->setPhoto($this->getReference($data['photo']));
            $account->setState($data['state']);

            if (isset($data['medias'])) {
                foreach ($data['medias'] as $media) {
                    /** @var Media $md */
                    $md = ($this->hasReference($media)) ? $this->getReference($media) : Media::findOneByPath($media);
                    $md->setAccount($account);
                    $manager->persist($md);
                }
            }

            $this->addReference($data['email'], $account);
            $manager->persist($account);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadAddress(ObjectManager $manager)
    {
        foreach ($this->data as $key => $data) {
            $address = (Address::where('address', $data['address'])->where('city', $data['city'])->where('postal_code', $data['postal_code'])->where('country', $data['country'])->count() == 0)
                ? new Address()
                : Address::findOneBy(['address' => $data['address'], 'city' => $data['city'], 'postal_code' => $data['postal_code'], 'country' => $data['country']]);
            $account = ($this->hasReference($data['account'])) ? $this->getReference($data['account']) : Account::findOneByEmail($data['account']);
            if(isset($data['alias'])) {
                $address->setAlias($data['alias']);
            }
            $address->setAddress($data['address']);
            $address->setCity($data['city']);
            $address->setPostalCode($data['postal_code']);
            $address->setAccount($account);
            $this->setReference($key, $address);
            $manager->persist($address);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadWebsite(ObjectManager $manager)
    {
        foreach ($this->data as $key => $data) {
            /** @var Website $website */
            $website = (Website::where('domain', $data['domain'])->count() == 0)
                ? new Website() : Website::findOneByDomain($data['domain']);
            $website->setTheme($this->getReference($data['theme']));
            $website->setDefaultLanguage($this->getReference($data['default_language']));

            if ($this->hasReference($data['layout']))
                $website->setLayout($this->getReference($data['layout']));
            else {
                $layout = Template::findOneByName($data['layout']);
                $website->setLayout($layout);
            }

            foreach ($data['templates'] as $template) {
                /** @var Template $tpl */
                $tpl = ($this->hasReference($template))
                    ? $this->getReference($template) : Template::findOneByName($template);
                $tpl->setWebsite($website);
                $manager->persist($tpl);
            }

            foreach ($data['medias'] as $media) {
                /** @var Media $md */
                $md = ($this->hasReference($media)) ? $this->getReference($media) : Media::findOneByPath($media);
                $md->setWebsite($website);
                $manager->persist($md);
            }

            foreach ($data['languages'] as $language) {
                /** @var Language $lang */
                $lang = ($this->hasReference($language))
                    ? $this->getReference($language) : Language::findOneByCode($language);
                $website->addLanguage($lang);
            }

            if (isset($data['data'])) {
                $data['data'] = $this->replaceData($data['data']);
                $website->setData($data['data']);
            }

            $website->setUrl($data['url']);
            (isset($data['state'])) ? $website->setState($data['state']) : $website->setState(-1);
            $this->setReference($key, $website);
            $manager->persist($website);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadTheme(ObjectManager $manager)
    {
        foreach ($this->data as $data) {
            $theme = (Theme::where('name', $data['name'])->count() == 0) ? new Theme() : Theme::findOneByName($data['name']);
            $theme->setName($data['name']);
            $theme->setDirectory($data['directory']);
            $this->setReference($data['name'], $theme);
            $manager->persist($theme);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadSection(ObjectManager $manager)
    {
        foreach($this->data as $key => $data) {
            $section = (Section::where('container', $data['container'])->count() == 0)
                ? new Section() : Section::findOneByContainer($data['container']);
            $section->setSectionId($data['section_id']);
            $section->setSectionClass($data['section_class']);
            $section->setStyle($data['style']);
            $section->setContainer($data['container']);
            $this->addReference($key, $section);
            $manager->persist($section);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadTemplate(ObjectManager $manager)
    {
        foreach ($this->data as $key => $data) {
            $template = (Template::where('name', $data['name'])->count() == 0)
                ? new Template()
                : Template::findOneByName($data['name']);
            $template->setName($data['name']);
            $template->setTitle($data['title']);
            $template->setPath($data['path']);
            if (isset($data['website']) && !is_null($data['website'])) {
                $template->setWebsite($this->getReference($data['website']));
            }
            $this->setReference($key, $template);
            $manager->persist($template);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadMedia(ObjectManager $manager)
    {
        foreach ($this->data as $data) {
            $media = (Media::where('path', $data['path'])->count() == 0)
                ? new Media() :
                Media::findOneByPath($data['path']);
            $media->setTitle($data['title']);
            $media->setPath($data['path']);
            $media->setType($data['type']);
            $media->setSize($data['size']);
            $media->setAlt($data['alt']);
            if (isset($data['website']) && !is_null($data['website'])) {
                $media->setWebsite($this->getReference($data['website']));
            }
            $manager->persist($media);
            $this->addReference($data['path'], $media);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadRoute(ObjectManager $manager)
    {
        foreach ($this->data as $key => $data) {
            $route = (Route::where('name', $data['name'])->count() == 0)
                ? new Route() : Route::findOneByName($data['name']);
            $route->setUrl($data['url']);
            $route->setName($data['name']);
            $route->setArguments($data['argument']);
            $route->setMiddlewares($data['middleware']);
            $route->setSubdomain($data['subdomain']);
            if (isset($data['website']) && !is_null($data['website'])) {
                $website = ($this->hasReference($data['website'])) ? $this->getReference($data['website']) : Website::findOneByDomain($data['website']);
                $route->setWebsite($website);
            }
            if (isset($data['position'])) $route->setPosition($data['position']);
            $this->addReference($data['name'], $route);
            $manager->persist($route);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadPage(ObjectManager $manager)
    {
        foreach ($this->data as $key => $data) {
            $route = ($this->hasReference($data['route'])) ? $this->getReference($data['route']) : Route::findOneByName($data['route']);
            $website = ($this->hasReference($data['website'])) ? $this->getReference($data['website']) : Website::findOneByDomain($data['website']);

            $page = (Page::where('title', $data['title'])->where('type', $data['type'])->where('route', $route)->where('website', $website)->count() == 0)
                ? new Page() : Page::findOneBy(['title' => $data['title'], 'type' => $data['type'], 'route' => $route, 'website' => $website]);
            $page->setTitle($data['title']);
            $page->setRoute($route);
            $page->setWebsite($website);

            $layout = ($this->hasReference($data['layout'])) ? $this->getReference($data['layout']) : Template::findOneByName($data['layout']);
            $page->setLayout($layout);
            $page->setType($data['type']);

            if (isset($data['builder'])) $page->setBuilder($data['builder']);
            if (isset($data['published'])) $page->setPublished($data['published']);

            $this->addReference($key, $page);
            $manager->persist($page);
        }

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadCustomFieldRule(ObjectManager $manager)
    {
        foreach($this->data as $key => $data) {
            $cf = (CustomFieldRule::where('name', $data['name'])->count() == 0) ? new CustomFieldRule() : CustomFieldRule::findOneByName($data['name']);
            $cf->setTitle($data['title']);
            $cf->setName($data['name']);
            $cf->setCallback($data['callback']);
            $cf->setType($data['type']);
            $cf->setReplaceTable($data['table']);
            $this->setReference($key, $cf);
            $manager->persist($cf);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadCustomField(ObjectManager $manager)
    {
        foreach ($this->data as $key => $data) {

            $rule = ($this->hasReference($data['rule'])) ? $this->getReference($data['rule']) : CustomFieldRule::findOneByName($data['rule']);
            $website = ($this->hasReference($data['website'])) ? $this->getReference($data['website']) : Website::findOneById($data['website']);
            $cf = (CustomField::where('title', $data['title'])->where('website', $website)->count() == 0)
                ? new CustomField() : CustomField::findOneBy(['title' => $data['title'], 'website' => $this->getReference($data['website'])]);
            $cf->setTitle($data['title']);
            $cf->setRule($rule);
            $cf->setOperation($data['operation']);
            if (isset($data['access_level']))
                $cf->setAccessLevel($data['access_level']);
            if (isset($data['value']))
                $cf->setValue($data['value']);
            elseif (isset($data['reference'])) {
                $cf->setValue($this->getReference($data['reference'])->getId());
            }
            $cf->setWebsite($website);

            $this->addReference($key, $cf);
            $manager->persist($cf);
        }
        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadAdminCustomField(ObjectManager $manager)
    {
        foreach ($this->data as $key => $data) {

            if ($data['scope'] != 'global')
                $data['content'] = $this->getCustomFieldRows($data['type'], $data['content']);

            $callbacks = $this->getCustomFieldCallback();
            if (isset($callbacks[$data['type']]))
                $data = call_user_func_array([$this, $callbacks[$data['type']]], [$data]);

            $acf = (AdminCustomField::where('title', $data['title'])->where('custom_field', $this->getReference($data['cf']))->where('name', $data['name'])->where('type', $data['type'])->count() == 0)
                ? new AdminCustomField() : AdminCustomField::findOneBy(['title' => $data['title'], 'custom_field' => $this->getReference($data['cf']), 'name' => $data['name'], 'type' => $data['type']]);
            $acf->setTitle($data['title']);
            $acf->setName($data['name']);
            $acf->setType($data['type']);
            $acf->setPosition($data['position']);
            if (isset($data['description'])) $acf->setDescription($data['description']);
            if (isset($data['access_level'])) $acf->setAccessLevel($data['access_level']);
            $acf->setData($data['data']);
            $acf->setContent($data['content']);
            if (!is_null($data['parent'])) $acf->setParent($this->getReference($data['parent']));
            $acf->setCustomField($this->getReference($data['cf']));
            $this->addReference($key, $acf);
            $manager->persist($acf);
        }
        $manager->flush();
    }

    /**
     * @param $type
     * @param $content
     * @return array
     */
    private function getCustomFieldRows($type, $content)
    {
        $new_content = [];
        foreach ($content as $key => $item) {
            if (strpos($key, '@') !== false) {
                $keys = explode('@', $key);
                if (isset($keys[1]) && $type != 'repeater') {
                    $keys[1] = $this->getReference($keys[1])->getId();
                    $new_content[implode('@', $keys)] = $item;
                } else
                    $new_content[$key] = $item;
            } else {
                $new_content[$key] = $item;
            }
        }
        return $new_content;
    }

    /**
     * @param $data
     * @return array
     */
    protected function getCustomFieldRepeater($data)
    {
        $new_content = $data;
        foreach ($data['content'] as $key => $item) {
            if (strpos($key, '@') !== false) {
                $keys = explode('@', $key);
                if (isset($keys[2]) && $data['type'] == 'repeater') {
                    $keys[2] = $this->getReference($keys[2])->getId();
                    $new_content['content'][implode('@', $keys)] = $item;
                    unset($new_content['content'][$key]);
                } else
                    $new_content['content'][$key] = $item;
            } else {
                $new_content['content'][$key] = $item;
            }
        }
        return $new_content;
    }

    /**
     * @param $data
     * @return array
     */
    protected function getCustomFieldMedia($data)
    {
        $new_content = $data;
        $type = (isset($data['data']['media_render_type'])) ? $data['data']['media_render_type'] : 'object';
        foreach ($data['content'] as $key => $item) {
            $new_content['content'][$key] = [];
            if (is_array($item)) {
                $content = [];
                $this->recursiveSetMedia($item, $content, $type);
                $new_content['content'][$key] = $content;
            } else {
                $media = ($this->hasReference($item)) ? $this->getReference($item) : Media::findOneByPath($item);
                switch ($type) {
                    case 'id':
                        $new_content['content'][$key] = $media->getId();
                        break;
                    default:
                        $new_content['content'][$key] = ['id' => $media->getId(), 'path' => $media->getPath(), 'alt' => $media->getAlt(), 'title' => $media->getTitle()];
                        break;
                }
            }
        }
        return $new_content;
    }

    /**
     * @param $items
     * @param array $content
     * @param $type
     */
    private function recursiveSetMedia($items, &$content = [], $type)
    {
        foreach ($items as $index => $media) {
            if (is_array($media)) {
                $content[$index] = [];
                $this->recursiveSetMedia($media, $content[$index], $type);
            } else {
                $media = ($this->hasReference($media)) ? $this->getReference($media) : Media::findOneByPath($media);
                switch ($type) {
                    case 'id':
                        $content[] = $media->getId();
                        break;
                    default:
                        $content[] = ['id' => $media->getId(), 'path' => $media->getPath(), 'alt' => $media->getAlt(), 'title' => $media->getTitle()];
                        break;
                }
            }
        }
    }

    /**
     * @param ObjectManager $manager
     */
    public function loadContent(ObjectManager $manager)
    {
        $callbacks = $this->getContentCallback();
        foreach ($this->data as $key => $data) {

            $website = ($this->hasReference($data['website']))
                ? $this->getReference($data['website']) : Website::findOneByDomain($data['website']);

            if (isset($data['cat_mod'])) {
                if (isset($callbacks[$data['cat_mod']]))
                    $data = call_user_func_array([$this, $callbacks[$data['cat_mod']]], [$data, $website]);
            }

            $content = (Content::where('website', $website)->where('block', $data['block'])->where('name', $data['name'])->count() == 0)
                ? new Content()
                : Content::findOneBy(['website' => $website, 'block' => $data['block'], 'name' => $data['name']]);

            $content->setName($data['name']);
            $content->setBlock($data['block']);

            if (isset($data['page']) && !is_null($data['page'])) {
                $page = ($this->hasReference($data['page'])) ? $this->getReference($data['page']) : Page::findOneBy(['title' => $data['page'], 'website' => $website]);
                $content->setPage($page);
            }
            $content->setWebsite($website);
            $content->setModule($data['module']);

            if (!is_null($data['section']))
                $content->setSection(Section::findOneById($data['section']));

            $content->setData($data['data']);
            $this->setReference($key, $content);
            $manager->persist($content);
        }

        $manager->flush();
    }

}