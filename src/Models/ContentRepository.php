<?php

namespace Jet\Models;

use Doctrine\ORM\QueryBuilder;

class ContentRepository extends AppRepository
{
    /**
     * @param $websites
     * @param $options
     * @return mixed
     */
    public function getGlobalContents($websites, $options)
    {
        $query = Content::queryBuilder();

        $query = $this->selectQueries($query);

        $query->where($query->expr()->isNull('c.page'));

        $query = $this->getQueryParams($query, ['websites' => $websites, 'options' => $options]);

        return $query->getQuery()
            ->getArrayResult();
    }

    /**
     * @param $page
     * @param $websites
     * @param $options
     * @return array
     */
    public function getPageContents($page, $websites, $options)
    {
        $query = Content::queryBuilder();

        $query = $this->selectQueries($query);

        $query->where('p.id = :page')
            ->setParameter('page', $page);

        $query = $this->getQueryParams($query, ['websites' => $websites, 'options' => $options]);

        return $query->getQuery()
            ->getArrayResult();
    }

    /**
     * @param $page
     * @param $website
     * @return array
     */
    public function frontRender($page, $website)
    {
        $query = Content::queryBuilder();

        $query = $this->selectQueries($query);

        $query->where($query->expr()->orX(
            $query->expr()->eq('p.id', ':page'),
            $query->expr()->isNull('c.page')
        ))
            ->setParameter('page', $page);
        
        $query->andWhere($query->expr()->eq('w.id', ':website'))
            ->setParameter('website', $website);

        return $query->getQuery()
            ->getResult();
    }

    /**
     * @param $ids
     * @return array
     */
    public function findContentsById($ids)
    {
        $query = Content::queryBuilder()
            ->select('partial c.{id}')
            ->addSelect('partial w.{id}')
            ->from('Jet\Models\Content', 'c')
            ->leftJoin('c.website', 'w');
        return $query->where($query->expr()->in('c.id', ':ids'))
            ->setParameter('ids', $ids)
            ->getQuery()->getArrayResult();
    }

    /**
     * @param QueryBuilder $query
     * @return QueryBuilder
     */
    private function selectQueries(QueryBuilder $query)
    {
        $query->select('c')
            ->addSelect('partial w.{id}')
            ->addSelect('partial m.{id,name,callback,slug}')
            ->addSelect('partial cat.{id,title,slug}')
            ->addSelect('partial p.{id}')
            ->addSelect('partial t.{id,name,title,category,type,content,scope}')
            ->from('Jet\Models\Content', 'c')
            ->leftJoin('c.page', 'p')
            ->leftJoin('c.website', 'w')
            ->leftJoin('c.module', 'm')
            ->leftJoin('c.template', 't')
            ->leftJoin('m.category', 'cat');
        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @param $params
     * @return QueryBuilder
     */
    private function getQueryParams(QueryBuilder $query, $params)
    {

        if (isset($params['websites']) && !empty($params['websites'])) {
            $query->andWhere($query->expr()->in('w.id', ':websites'))
                ->setParameter('websites', $params['websites']);
        }

        if(isset($params['options'])) $query = $this->excludeData($query, $params['options'], 'contents');

        return $query;
    }
}