<?php

namespace Jet\Models;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class MediaRepository
 * @package Jet\Models
 */
class MediaRepository extends AppRepository
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
        $query = Media::queryBuilder();

        $query->select('m')
            ->addSelect('partial w.{id,domain}')
            ->from('Jet\Models\Media', 'm')
            ->leftJoin('m.website', 'w')
            ->leftJoin('m.account', 'a')
            ->leftJoin('a.status', 's')
            ->setFirstResult(($page - 1) * $max)
            ->setMaxResults($max);

        $query = $this->getQueryParams($query, $params);

        if (!empty($params['filter']) && !empty($params['filter']['column'])) {
            $countSearch = true;
            $op = (isset($params['filter']['operator'])) ? $params['filter']['operator'] : 'eq';
            if ($op == 'isNull' || $op == 'isNotNull')
                $query->andWhere($query->expr()->$op($params['filter']['column']));
            elseif ($op == 'between') {
                $minmax = explode('|', $params['filter']['value']);
                $query->andWhere($query->expr()->between($params['filter']['column'], ':min', ':max'))
                    ->setParameter('min', $minmax[0])
                    ->setParameter('max', $minmax[1]);
            } else
                $query->andWhere($query->expr()->$op($params['filter']['column'], ':value'))
                    ->setParameter('value', $params['filter']['value']);
        }

        if (!empty($params['search'])) {
            $countSearch = true;
            $query->andWhere($query->expr()->orX(
                $query->expr()->like('m.title', ':search'),
                $query->expr()->like('m.path', ':search')
            ))->setParameter('search', '%' . $params['search'] . '%');
        }

        (!empty($params['order']) && !empty($params['order']['column']))
            ? $query->addOrderBy($params['order']['column'], strtoupper($params['order']['dir']))
            : $query->orderBy('m.id', 'DESC');

        $pg = new Paginator($query);
        $data = $pg->getQuery()->getArrayResult();

        return ['data' => $data, 'total' => ($countSearch) ? count($data) : $this->countMedia($params)];
    }

    /**
     * @param array $params
     * @return int
     */
    public function countMedia($params = [])
    {
        $query = Media::queryBuilder();

        $query->select('COUNT(m)')
            ->from('Jet\Models\Media', 'm')
            ->leftJoin('m.website', 'w')
            ->leftJoin('m.account', 'a')
            ->leftJoin('a.status', 's');

        $query = $this->getQueryParams($query, $params);

        return (int)$query->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $ids
     * @return array
     */
    public function findById($ids)
    {
        $query = Media::queryBuilder()
            ->select('partial m.{id, path}')
            ->addSelect('partial w.{id}')
            ->addSelect('partial a.{id}')
            ->addSelect('partial s.{id, level}')
            ->from('Jet\Models\Media', 'm')
            ->leftJoin('m.website', 'w')
            ->leftJoin('m.account', 'a')
            ->leftJoin('a.status', 's');
        return $query->where($query->expr()->in('m.id', ':ids'))
            ->setParameter('ids', $ids)
            ->getQuery()->getArrayResult();
    }

    /**
     * @param QueryBuilder $query
     * @param $params
     * @return mixed
     */
    private function getQueryParams(QueryBuilder $query, $params)
    {
        if (isset($params['account']) && isset($params['level'])) {
            $orX = $query->expr()->orX();
            $orX->addMultiple([
                $query->expr()->andX(
                    $query->expr()->isNull('m.website'),
                    $query->expr()->isNull('m.account')
                ),
                $query->expr()->andX(
                    $query->expr()->isNull('m.website'),
                    $query->expr()->gt('s.level', ':level')
                ),
                $query->expr()->eq('a.id', ':account')
            ]);

            if (isset($params['websites'])) $orX->add($query->expr()->in('w.id', ':websites'));

            $query->andWhere($orX)
                ->setParameter('account', $params['account'])
                ->setParameter('level', $params['level']);

            if (isset($params['websites'])) $query->setParameter('websites', $params['websites']);
        }

        if (isset($params['options'])) {

            if (isset($params['options']['website'])) {
                $query = $this->excludeData($query, $params['options']['website'], 'medias');
            }

            if (isset($params['options']['account'])) {
                $query = $this->excludeData($query, $params['options']['account'], 'medias', 'm', 1);
            }

        }

        return $query;
    }


}