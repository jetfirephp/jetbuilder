<?php

namespace Jet\Models;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class PageRepository
 * @package Jet\Models
 */
class PageRepository extends AppRepository
{

    /**
     * @param $page
     * @param $max
     * @param array $params
     * @return array
     */
    public function listAll($page, $max, $params = [])
    {

        $countSearch = false;
        $query = Page::queryBuilder();

        /* Add DateFormat support for dql */
        $config = Page::em()->getConfiguration();
        $config->addCustomStringFunction('DATE_FORMAT', 'DoctrineExtensions\Query\Mysql\DateFormat');

        $query->select('partial p.{id,title,type,published,updated_at}')
            ->addSelect('partial r.{id,url}')
            ->addSelect('partial w.{id,domain}')
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.route', 'r')
            ->leftJoin('p.website', 'w')
            ->setFirstResult(($page - 1) * $max)
            ->setMaxResults($max);

        if (!empty($params['search'])) {
            $countSearch = true;
            $query->andWhere($query->expr()->orX(
                $query->expr()->like('p.title', ':search'),
                $query->expr()->like('p.type', ':search'),
                $query->expr()->like('r.url', ':search')
            ))->setParameter('search', '%' . $params['search'] . '%');
        }

        if (!empty($params['filter']) && !empty($params['filter']['column'])) {
            $countSearch = true;
            $op = (isset($params['filter']['operator'])) ? $params['filter']['operator'] : 'eq';
            if ($op == 'isNull')
                $query->andWhere($query->expr()->isNull($params['filter']['column']));
            else
                $query->andWhere($query->expr()->$op($params['filter']['column'], ':value'))
                    ->setParameter('value', $params['filter']['value']);
        }

        (!empty($params['order']) && !empty($params['order']['column']))
            ? $query->addOrderBy($params['order']['column'], strtoupper($params['order']['dir']))
            : $query->orderBy('p.id', 'DESC');

        $query = $this->getQueryWithParams($query, $params);

        $pg = new Paginator($query);
        $data = $pg->getQuery()->getArrayResult();
        return ['data' => $data, 'total' => ($countSearch) ? count($data) : $this->countPage($params)];
    }

    /**
     * @param array $params
     * @return int
     */
    public function countPage($params = [])
    {
        $query = Page::queryBuilder();

        $query->select('COUNT(p)')
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.website', 'w');

        $query = $this->getQueryWithParams($query, $params);

        return (int)$query->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $id
     * @param $options
     * @return mixed
     */
    public function read($id, $options)
    {
        $query = Page::queryBuilder()
            ->select('p', 'r', 'partial w.{id,domain}', 'partial l.{id,title}', 's', 'lib')
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.route', 'r')
            ->leftJoin('p.website', 'w')
            ->leftJoin('p.layout', 'l')
            ->leftJoin('p.stylesheets', 's')
            ->leftJoin('p.libraries', 'lib')
            ->where('p.id = :id')
            ->setParameter('id', $id);

        $query = $this->getQueryWithParams($query, ['options' => $options]);

        return $query->getQuery()->getOneOrNullResult();
    }

    /**
     * @param $ids
     * @return array
     */
    public function findById($ids)
    {
        $query = Page::queryBuilder()
            ->select('partial p.{id}')
            ->addSelect('partial w.{id}')
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.website', 'w');
        return $query->where($query->expr()->in('p.id', ':ids'))
            ->setParameter('ids', $ids)
            ->getQuery()->getArrayResult();
    }

    /**
     * @param $websites
     * @param $options
     * @return mixed
     */
    public function getWebsitePages($websites, $options)
    {
        $query = Page::queryBuilder()
            ->select(['partial p.{id, title, published}'])
            ->addSelect(['partial r.{id, url, name, subdomain, method, argument}'])
            ->addSelect(['partial l.{id, content, category, scope, type}'])
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.route', 'r')
            ->leftJoin('p.layout', 'l')
            ->leftJoin('p.website', 'w');

        $query = $this->getQueryWithParams($query, ['websites' => $websites, 'options' => $options]);

        return $query->addSelect('CASE WHEN w.id = :id THEN 1 ELSE 0 END AS HIDDEN sortCondition')
            ->setParameter('id', $websites[0])
            ->addOrderBy('sortCondition', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $websites
     * @return mixed
     */
    public function getWebsiteHomePage($websites)
    {
        $query = Page::queryBuilder()
            ->select('p')
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.route', 'r')
            ->leftJoin('p.website', 'w');

        $query->where($query->expr()->eq('r.url', ':url'))
            ->setParameter('url', '/');

        $query = $this->getQueryWithParams($query, ['websites' => $websites]);

        return $query->getQuery()
            ->getOneOrNullResult();
    }


    /**
     * @param $website
     * @param $route
     * @return mixed
     */
    public function getPageByRoute($website, $route)
    {
        $query = Page::queryBuilder()
            ->select('p')
            ->addSelect('r')
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.route', 'r')
            ->leftJoin('p.website', 'w');

        $query->where($query->expr()->eq('r.id', ':route_id'))
            ->andWhere($query->expr()->eq('p.published', 1))
            ->andWhere($query->expr()->eq('w.id', ':website_id'))
            ->setParameter('route_id', $route)
            ->setParameter('website_id', $website);

        $result = $query->getQuery()->getResult();
        return (isset($result[0])) ? $result[0] : null;
    }

    /**
     * @param $websites
     * @param $route
     * @return mixed
     */
    public function getPageByRouteUrl($websites, $route)
    {
        $query = Page::queryBuilder()
            ->select('p')
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.route', 'r')
            ->leftJoin('p.website', 'w');

        $query->where($query->expr()->eq('r.url', ':url'))
            ->setParameter('url', $route);

        $query = $this->getQueryWithParams($query, ['websites' => $websites]);

        $result = $query->getQuery()->getResult();
        return (isset($result[0])) ? $result[0] : null;
    }

    /**
     * @param $page
     * @param $options
     * @return array
     */
    public function getPageContents($page, $options)
    {
        $query = Page::queryBuilder()
            ->select('partial p.{id}')
            ->addSelect('c')
            ->addSelect('partial w.{id}')
            ->addSelect('partial m.{id,name,slug}')
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.contents', 'c')
            ->leftJoin('c.website', 'w')
            ->leftJoin('c.module', 'm')
            ->where('p.id = :page')
            ->setParameter('page', $page);
        
        $query = $this->excludeData($query, $options, 'contents');

        return $query->getQuery()
            ->getArrayResult();
    }

    /**
     * @param $websites
     * @param $exclude
     * @param array $select
     * @return array
     */
    public function getPageRules($websites, $exclude, $select = ['p.id as id', 'p.title as name'])
    {
        $query = Page::queryBuilder()
            ->select($select)
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.website', 'w');

        $query = $this->getQueryWithParams($query, ['websites' => $websites, 'options' => $exclude]);

        return $query->getQuery()
            ->getArrayResult();
    }

    /**
     * @param $websites
     * @param $options
     * @return array
     */
    public function getStaticPages($websites, $options)
    {
        $query = Page::queryBuilder()
            ->select(['p.id as id', 'p.title as name', 'p.type as type'])
            ->from('Jet\Models\Page', 'p')
            ->leftJoin('p.website', 'w');

        $query = $this->getQueryWithParams($query, ['websites' => $websites, 'options' => $options]);

        $query->andWhere($query->expr()->eq('p.type', ':type'))
            ->setParameter('type', 'static');

        return $query->getQuery()
            ->getArrayResult();
    }

    /**
     * @param QueryBuilder $query
     * @param $params
     * @return QueryBuilder
     */
    private function getQueryWithParams(QueryBuilder $query, $params)
    {

        if (isset($params['websites']) && !empty($params['websites'])) {
            $query->andWhere($query->expr()->in('w.id', ':websites'))
                ->setParameter('websites', $params['websites']);
        }

        if(isset($params['static']) && $params['static'] == true){
            $query->andWhere($query->expr()->eq('p.type', ':type'))
            ->setParameter('type', 'static');
        }
        
        if(isset($params['options'])){
            $query = $this->excludeData($query, $params['options'], 'pages');
        }

        return $query;
    }
}