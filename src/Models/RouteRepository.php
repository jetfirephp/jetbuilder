<?php

namespace Jet\Models;

use Doctrine\ORM\QueryBuilder;

/**
 * Class RouteRepository
 * @package Jet\Models
 */
class RouteRepository extends AppRepository
{

    /**
     * @param array $websites
     * @param array $options
     * @return array
     */
    public function listAll($websites = [], $options = [])
    {
        $query = Route::queryBuilder()
            ->select('r', 'partial w.{id}')
            ->from('Jet\Models\Route', 'r')
            ->leftJoin('r.website', 'w');

        $query = $this->getQueryWithParams($query, ['websites' => $websites, 'options' => $options]);

        return $query->getQuery()
            ->getArrayResult();
    }

    /**
     * @param array $website
     * @return array
     */
    public function getWebsiteRoutes($website)
    {
        $query = Route::queryBuilder()
            ->select('partial r.{id,url,method,name,subdomain,arguments,middlewares}')
            ->from('Jet\Models\Route', 'r')
            ->leftJoin('r.website', 'w');
        
        $query->where($query->expr()->eq('w.id', ':website'))
            ->setParameter('website', $website);         
        
        $query->orderBy('r.position', 'ASC');
        
        return $query->getQuery()
            ->getArrayResult();
    }

    /**
     * @param array $websites
     * @param array $options
     * @return array
     */
    public function getWebsiteRoutesWithLayout($websites = [], $options = [])
    {
        $query = Route::queryBuilder()
            ->select(['r.id as route_id', 'r.url as url', 'r.name as name', 'l.id as layout_id', 'l.content as content', 'l.type as type', 'l.name as title'])
            ->from('Jet\Models\Route', 'r')
            ->leftJoin('r.website', 'w')
            ->leftJoin('p.layout', 'l');

        $query = $this->getQueryWithParams($query, ['websites' => $websites, 'options' => $options]);

        return $query->orderBy('r.position', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }


    /**
     * @param $route
     * @param $websites
     * @param $options
     * @return array
     */
    public function getRouteByName($route, $websites, $options)
    {
        $query = Route::queryBuilder()
            ->select(['r.id as id', 'r.url as url', 'r.name as name', 'w.id as website_id'])
            ->from('Jet\Models\Route', 'r')
            ->leftJoin('r.website', 'w');

        $query->where('r.name = :route')
            ->setParameter('route', $route);

        $query = $this->getQueryWithParams($query, ['websites' => $websites, 'options' => $options]);

        $result = $query->addSelect('CASE WHEN w.id = :id THEN 1 ELSE 0 END AS HIDDEN sortCondition')
            ->setParameter('id', $websites[0])
            ->addOrderBy('sortCondition', 'ASC')
            ->getQuery()
            ->getArrayResult();

        return (isset($result[0])) ? $result[0] : null;
    }

    /**
     * @param QueryBuilder $query
     * @param $params
     * @return QueryBuilder
     */
    private function getQueryWithParams(QueryBuilder $query, $params)
    {

        if (isset($params['websites'])) {
            if(!empty($params['websites'])) {
                $query->andWhere($query->expr()->orX(
                    $query->expr()->in('w.id', ':websites'),
                    $query->expr()->isNull('w.id')
                ))->setParameter('websites', $params['websites']);
            }else
                $query->andWhere($query->expr()->isNull('w.id'));
        }
        
        if (isset($params['options'])){
            $query = $this->excludeData($query, $params['options'], 'routes');
        }

        return $query;
    }
}