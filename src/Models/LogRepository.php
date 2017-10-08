<?php

namespace Jet\Models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class LogRepository extends EntityRepository
{

    /**
     * @param $start
     * @param $max
     * @param array $params
     * @return array
     */
    public function listAll($start, $max, $params = [])
    {

        $countSearch = false;

        /* Add DateFormat support for dql */
        $config = Log::em()->getConfiguration();
        $config->addCustomStringFunction('DATE_FORMAT', 'DoctrineExtensions\Query\Mysql\DateFormat');

        $query = Log::queryBuilder();

        /* Query */
        $query->select(['l.id AS id', 'l.channel as channel', 'l.level_name as level_name', 'l.level as level', 'a.email as email', 's.role as role', 'DATE_FORMAT(l.date,\'%d/%m/%Y à %Hh%i\') as date', 'l.message as message'])
            ->from('Jet\Models\Log', 'l')
            ->leftJoin('l.account', 'a')
            ->leftJoin('a.status', 's')
            ->setFirstResult($start)
            ->setMaxResults($max);

        /* Search params */
        if (!empty($params['search'])) {
            $countSearch = true;
            $query->andWhere($query->expr()->orX(
                $query->expr()->like('l.channel', ':search'),
                $query->expr()->like('l.level_name', ':search'),
                $query->expr()->like('l.level', ':search'),
                $query->expr()->like('a.email', ':search'),
                $query->expr()->like('s.role', ':search')
            ))->setParameter('search', '%' . $params['search'] . '%');
        }

        /* Order params */
        if (isset($params['order']) && !empty($params['order'])) {
            $query = $this->applyOrder($query, $params['order']);
        } else {
            $query->orderBy('l.id', 'DESC');
        }

        if (isset($params['filter']) && !empty($params['filter']) && !empty($params['filter']['column'])) {
            $countSearch = true;
            $query = $this->applyFilter($query, $params['filter']);
        }


        $pg = new Paginator($query);
        $data = $pg->getQuery()->getArrayResult();
        return ['data' => $data, 'total' => ($countSearch) ? count($data) : (int)Log::count()];
    }


    /**
     * @param QueryBuilder $query
     * @param $filter
     * @param int $order
     * @return QueryBuilder
     */
    private function applyFilter(QueryBuilder $query, $filter, $order = 0)
    {
        $op = (isset($filter['operator'])) ? $filter['operator'] : 'eq';
        if ($op == 'isNull')
            $query->andWhere($query->expr()->isNull($filter['column']));
        elseif ($op == 'isNotNull')
            $query->andWhere($query->expr()->isNotNull($filter['column']));
        else
            $query->andWhere($query->expr()->$op($filter['column'], ':value_' . $order))
                ->setParameter('value_' . $order, $filter['value']);
        return $query;
    }

    /**
     * @param QueryBuilder $query
     * @param $orders
     * @return QueryBuilder
     */
    private function applyOrder(QueryBuilder $query, $orders)
    {
        $columns = ['l.id','l.channel', 'l.level_name', 'l.level', 'a.email', 's.role', 'l.date'];
        foreach ($orders as $order) {
            if(isset($columns[$order['column']]))
                $query->addOrderBy($columns[$order['column']], strtoupper($order['dir']));
        }
        return $query;
    }

    /**
     * @param array $params
     * @return array
     */
    public function listBy($params = [])
    {
        $query = Log::queryBuilder();
        $query->select('partial l.{id, message, date}')
            ->addSelect('partial a.{id, first_name, last_name, email}')
            ->addSelect('partial p.{id, path, alt}')
            ->addSelect('partial s.{id, role, level}')
            ->from('Jet\Models\Log', 'l')
            ->leftJoin('l.account', 'a')
            ->leftJoin('a.photo', 'p')
            ->leftJoin('a.status', 's');

        if (isset($params['filter']) && !empty($params['filter'])) {
            foreach ($params['filter'] as $key => $filter)
                if (is_array($filter)) $query = $this->applyFilter($query, $filter, $key);
        }

        if (isset($params['order']) && !empty($params['order'])) {
            $query = $this->applyOrder($query, $params['order']);
        }else{
            $query->orderBy('l.id', 'DESC');
        }

        if (isset($params['max']) && !empty($params['max']))
            $query->setMaxResults($params['max']);
        return $query->getQuery()->getArrayResult();
    }

    /**
     * @param $date
     * @return static
     */
    public function clean($date)
    {
        $query = Log::queryBuilder();
        return $query->delete('Jet\Models\Log', 'l')
            ->where($query->expr()->lt('l.date', ':date'))
            ->setParameter('date', $date)
            ->getQuery()->execute();
    }

}