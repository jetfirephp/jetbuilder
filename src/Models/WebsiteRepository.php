<?php

namespace Jet\Models;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class WebsiteRepository
 * @package Jet\Models
 */
class WebsiteRepository extends EntityRepository
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

        $query = Website::queryBuilder();

        /* Query */
        $query->select(['w.id AS id', 's.name as society', 'concat(a.first_name, \' \', a.last_name) as full_name', 'a.email as email', 'a.registered_at as registered_at', 'w.domain as website', 'w.state as state'])
            ->from('Jet\Models\Website', 'w')
            ->leftJoin('w.society', 's')
            ->leftJoin('s.account', 'a')
            ->setFirstResult($start);

        if ($max >= 0) $query->setMaxResults($max);

        /* If user is logged */
        if (isset($params['account']))
            $query->andWhere('a.id = :account')
                ->setParameter('account', $params['account']);

        /* Search params */
        if (!empty($params['search'])) {
            $countSearch = true;
            $query->andWhere($query->expr()->orX(
                $query->expr()->like('s.name', ':search'),
                $query->expr()->like('a.first_name', ':search'),
                $query->expr()->like('a.last_name', ':search'),
                $query->expr()->like('a.email', ':search'),
                $query->expr()->like('w.domain', ':search')
            ))->setParameter('search', '%' . $params['search'] . '%');
        }

        /* Order params */
        if (!empty($params['order'])) {
            $columns = ['w.id', 's.name', 'a.first_name', 'a.email', 'w.domain', 'w.state', 'a.registered_at'];
            foreach ($params['order'] as $order) {
                if (isset($columns[$order['column']]))
                    $query->addOrderBy($columns[$order['column']], strtoupper($order['dir']));
            }
        } else {
            $query->orderBy('s.id', 'DESC');
        }

        $pg = new Paginator($query);
        $data = $pg->getQuery()->getArrayResult();
        return ['data' => $data, 'total' => ($countSearch) ? count($data) : $this->countWebsite($params)];
    }

    /**
     * @param $params
     * @return mixed
     */
    public function countWebsite($params = [])
    {
        $query = Website::queryBuilder()
            ->select('COUNT(w)')
            ->from('Jet\Models\Website', 'w')
            ->leftJoin('w.society', 's')
            ->leftJoin('s.account', 'a');
        /* If user is logged */
        if (isset($params['account']))
            $query->where($query->expr()->eq('a.id', ':account'))
                ->setParameter('account', $params['account']);
        return (int)$query->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return int
     */
    public function countActiveWebsite()
    {
        $query = Website::queryBuilder()
            ->select('COUNT(w)')
            ->from('Jet\Models\Website', 'w');
        $query->where($query->expr()->eq('w.state', ':state'))
            ->setParameter('state', 1);
        return (int)$query->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param $account
     * @return mixed
     */
    public function listAuthWebsite($account)
    {
        $query = Website::queryBuilder()
            ->select(['w.id'])
            ->from('Jet\Models\Website', 'w')
            ->leftJoin('w.society', 's')
            ->leftJoin('s.account', 'a');
        $query->where($query->expr()->eq('a.id', ':account'))
            ->setParameter('account', $account);
        return $query->andWhere($query->expr()->neq('w.state', 0))
            ->andWhere($query->expr()->eq('a.state', 1))
            ->getQuery()
            ->getArrayResult();
    }

    /**
     * @param $society_name
     * @return mixed
     */
    public function getSocietyWebsite($society_name)
    {
        $query = Website::queryBuilder()
            ->select('w')
            ->from('Jet\Models\Website', 'w')
            ->leftJoin('w.society', 's');
        return $query->where($query->expr()->eq('s.name', ':society_name'))
            ->setParameter('society_name', $society_name)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $account
     * @return mixed
     */
    public function getAccountWebsites($account)
    {
        $query = Website::queryBuilder()->select(['w.id as id', 'w.domain as website'])
            ->from('Jet\Models\Website', 'w')
            ->leftJoin('w.society', 's')
            ->leftJoin('s.account', 'a');
        return $query->where($query->expr()->eq('a.id',':id'))
            ->setParameter('id', $account)
            ->getQuery()->getArrayResult();
    }

    /**
     * @param DateTime $date
     * @param string $action
     * @return array
     */
    public function getWebsite(\DateTime $date, $action = 'eq')
    {
        $query = Website::queryBuilder()
            ->select('partial w.{id, domain, expiration_date}')
            ->addSelect('partial s.{id}')
            ->addSelect('partial a.{id, first_name, last_name, email}')
            ->from('Jet\Models\Website', 'w')
            ->leftJoin('w.society', 's')
            ->leftJoin('s.account', 'a');
        return $query->where($query->expr()->$action('w.expiration_date', ':date'))
            ->setParameter('date', $date)
            ->getQuery()->getArrayResult();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getSummary($id)
    {
        $query = Website::queryBuilder()
            ->select('partial w.{id,domain,state,modules,expiration_date}')
            ->addSelect('partial s.{id,name}')
            ->addSelect('partial a.{id,email}')
            ->addSelect('partial t.{id,name}')
            ->from('Jet\Models\Website', 'w')
            ->leftJoin('w.society', 's')
            ->leftJoin('s.account', 'a')
            ->leftJoin('w.theme', 't');
        $result = $query->where($query->expr()->eq('w.id',':id'))
            ->setParameter('id', $id)
            ->getQuery()
            ->getArrayResult();
        return (isset($result[0])) ? $result[0] : null;
    }

    /**
     * @param int $max
     * @return array
     */
    public function getLast($max = 5)
    {
        $query = Website::queryBuilder();
        $query->select('partial w.{id,domain,created_at}')
            ->addSelect('partial s.{id,name}')
            ->from('Jet\Models\Website', 'w')
            ->leftJoin('w.society', 's')
            ->leftJoin('s.account', 'a')
            ->leftJoin('a.status', 'st');

        $query->where('st.level = 4');

        $query->setMaxResults($max)
            ->orderBy('w.id', 'DESC');

        return $query->getQuery()->getArrayResult();
    }
    
}