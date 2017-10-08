<?php

namespace Jet\Models;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class CustomFieldRepository
 * @package Jet\Models
 */
class CustomFieldRepository extends AppRepository
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

        $query = CustomField::queryBuilder();

        /* Query */
        $query->select('c')
            ->addSelect('partial w.{id, domain}')
            ->from('Jet\Models\CustomField', 'c')
            ->leftJoin('c.website', 'w')
            ->setFirstResult(($page - 1) * $max)
            ->setMaxResults($max);

        /* Search params */
        if (!empty($params['search'])) {
            $countSearch = true;
            $query->andWhere($query->expr()->like('c.title', ':search'))->setParameter('search', '%' . $params['search'] . '%');
        }

        /* Filter params */
        if (!empty($params['filter']) && !empty($params['filter']['column'])) {
            $countSearch = true;
            $query->andWhere($query->expr()->eq($params['filter']['column'], ':value'))
                ->setParameter('value', $params['filter']['value']);
        }

        /* Order params */
        (!empty($params['order']) && !empty($params['order']['column']))
            ? $query->addOrderBy($params['order']['column'], strtoupper($params['order']['dir']))
            : $query->orderBy('c.id', 'ASC');

        $query = $this->getQueryParams($query, $params);

        $pg = new Paginator($query);
        $data = $pg->getQuery()->getArrayResult();

        return ['data' => $data, 'total' => ($countSearch) ? count($data) : $this->countCustomField($params)];
    }

    /**
     * @param array $params
     * @return int
     */
    public function countCustomField($params = [])
    {
        $query = CustomField::queryBuilder();

        $query->select('COUNT(c)')
            ->from('Jet\Models\CustomField', 'c')
            ->leftJoin('c.website', 'w');

        $query = $this->getQueryParams($query, $params);

        return (int)$query->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $params
     * @return array
     */
    public function adminRender($params)
    {

        $query = CustomField::queryBuilder();

        /* Query */
        $query->select('c')
            ->addSelect('r')
            ->addSelect('partial p.{id}')
            ->addSelect('partial w.{id,domain}')
            ->addSelect('partial f1.{id,title,name,description,type,required,data,content,position,access_level}')
            ->addSelect('partial f2.{id,title,name,description,type,required,data,content,position,access_level}')
            ->addSelect('partial f3.{id,title,name,description,type,required,data,content,position,access_level}')
            ->from('Jet\Models\CustomField', 'c')
            ->leftJoin('c.rule', 'r')
            ->leftJoin('c.website', 'w')
            ->leftJoin('c.fields', 'f1')
            ->leftJoin('f1.parent', 'p')
            ->leftJoin('f1.children', 'f2')
            ->leftJoin('f2.children', 'f3');

        if (isset($params['rules'])) $query = $this->getQueryRules($query, $params['rules']);

        $query->andWhere($query->expr()->isNull('p.id'));

        $query = $this->getQueryParams($query, $params);

        $query->orderBy('c.title', 'ASC')
            ->addOrderBy('f1.position', 'ASC')
            ->addOrderBy('f2.position', 'ASC')
            ->addOrderBy('f3.position', 'ASC');

        return $query->getQuery()->getArrayResult();
    }

    /**
     * @param $websites
     * @param $options
     * @param $rules
     * @return mixed
     */
    public function frontRender($websites, $options, $rules)
    {

        $query = CustomField::queryBuilder();
        /* Query */
        $query->select('c')
            ->addSelect('r')
            ->addSelect('partial p.{id}')
            ->addSelect('partial f1.{id,title,name,data,type,content}')
            ->addSelect('partial f2.{id,title,name,data,type,content}')
            ->addSelect('partial f3.{id,title,name,data,type,content}')
            ->from('Jet\Models\CustomField', 'c')
            ->leftJoin('c.rule', 'r')
            ->leftJoin('c.website', 'w')
            ->leftJoin('c.fields', 'f1')
            ->leftJoin('f1.parent', 'p')
            ->leftJoin('f1.children', 'f2')
            ->leftJoin('f2.children', 'f3');

        $query = $this->getQueryRules($query, $rules);

        $query->andWhere($query->expr()->isNull('p.id'));

        $query = $this->getQueryParams($query, ['websites' => $websites, 'options' => $options]);

        $query->orderBy('f1.position', 'ASC')
            ->addOrderBy('f2.position', 'ASC')
            ->addOrderBy('f3.position', 'ASC');

        $query->addSelect('CASE WHEN w.id = :id THEN 1 ELSE 0 END AS HIDDEN sortCondition')
            ->setParameter('id', $websites[0])
            ->addOrderBy('sortCondition', 'ASC');

        return $query->getQuery()->getArrayResult();
    }

    /**
     * @param $id
     * @param $params
     * @return mixed
     */
    public function read($id, $params)
    {

        $query = CustomField::queryBuilder();
        $query->select('c')
            ->addSelect('r')
            ->addSelect('partial p.{id}')
            ->addSelect('partial w.{id,domain}')
            ->addSelect('partial f1.{id,title,name,description,type,required,data,position,access_level}')
            ->addSelect('partial f2.{id,title,name,description,type,required,data,position,access_level}')
            ->addSelect('partial f3.{id,title,name,description,type,required,data,position,access_level}')
            ->from('Jet\Models\CustomField', 'c')
            ->leftJoin('c.rule', 'r')
            ->leftJoin('c.website', 'w')
            ->leftJoin('c.fields', 'f1')
            ->leftJoin('f1.parent', 'p')
            ->leftJoin('f1.children', 'f2')
            ->leftJoin('f2.children', 'f3')
            ->where('c.id = :id')
            ->setParameter('id', $id)
            ->andWhere($query->expr()->isNull('p.id'));

        $query = $this->getQueryParams($query, $params);

        $query->orderBy('f1.position', 'ASC')
            ->addOrderBy('f2.position', 'ASC')
            ->addOrderBy('f3.position', 'ASC');

        $result = $query->getQuery()->getArrayResult();
        return (isset($result[0])) ? $result[0] : null;
    }

    /**
     * @param $ids
     * @return array
     */
    public function findById($ids)
    {
        $query = CustomField::queryBuilder()
            ->select('partial c.{id}')
            ->addSelect('partial w.{id}')
            ->from('Jet\Models\CustomField', 'c')
            ->leftJoin('c.website', 'w');
        return $query->where($query->expr()->in('c.id', ':ids'))
            ->setParameter('ids', $ids)
            ->getQuery()->getArrayResult();
    }

    /**
     * @param $ids
     * @return array
     */
    public function findFieldsById($ids)
    {
        $query = AdminCustomField::queryBuilder()
            ->select('partial f.{id}')
            ->addSelect('partial c.{id}')
            ->addSelect('partial w.{id}')
            ->from('Jet\Models\AdminCustomField', 'f')
            ->leftJoin('f.custom_field', 'c')
            ->leftJoin('c.website', 'w');
        return $query->where($query->expr()->in('f.id', ':ids'))
            ->setParameter('ids', $ids)
            ->getQuery()->getArrayResult();
    }

    /**
     * @param $query
     * @param $params
     * @return mixed
     */
    private function getQueryParams(QueryBuilder $query, $params)
    {
        if (isset($params['access_level'])) {
            $query->andWhere($query->expr()->gte('c.access_level', ':access_level'))
                ->setParameter('access_level', $params['access_level']);
        }

        if (isset($params['websites']) && !empty($params['websites'])) {
            $query->andWhere($query->expr()->in('w.id', ':websites'))
                ->setParameter('websites', $params['websites']);
        }

        if(isset($params['options'])) $query = $this->excludeData($query, $params['options'], 'custom_fields');

        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @param $rules
     * @return QueryBuilder
     */
    private function getQueryRules(QueryBuilder $query, $rules)
    {
        $orX = $query->expr()->orX();

        foreach ($rules as $rule => $param) {
            $op = (is_array($param)) ? 'in' : 'eq';
            $orX->add($query->expr()->orX(
                $query->expr()->andX(
                    $query->expr()->eq('r.name', "'$rule'"),
                    $query->expr()->eq('c.operation', "'='"),
                    (empty($param) || is_null($param)) ? $query->expr()->isNull('c.value') : $query->expr()->$op('c.value', ':' . $rule)
                ),
                $query->expr()->andX(
                    $query->expr()->eq('r.name', "'$rule'"),
                    $query->expr()->eq('c.operation', "'!='"),
                    $query->expr()->notIn('c.value', ':_' . $rule)
                )
            ));
            if (!empty($param) && !is_null($param)) $query->setParameter($rule, $param);
            $query->setParameter('_' . $rule, $param);
        }

        $query->add('where', $orX);
        return $query;
    }

}