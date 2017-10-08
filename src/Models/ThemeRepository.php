<?php

namespace Jet\Models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class ThemeRepository
 * @package Jet\Models
 */
class ThemeRepository extends EntityRepository{

    /**
     * @param $page
     * @param $max
     * @param array $params
     * @return array
     */
    public function listAll($page, $max, $params = []){
        
        $countSearch = false;
        $query = Theme::queryBuilder();
        
        $query->select(['t'])
            ->addSelect('partial p.{id,title,path,alt}')
            ->addSelect('partial w.{id,domain}')
            ->addSelect('partial pro.{id,name}')
            ->addSelect('partial s.{id,name}')
            ->from('Jet\Models\Theme','t')
            ->leftJoin('t.thumbnail','p')
            ->leftJoin('t.professions','pro')
            ->leftJoin('t.website','w')
            ->leftJoin('w.society','s')
            ->setFirstResult(($page-1)*$max)
            ->setMaxResults($max);

        if(!empty($params['search'])) {
            $countSearch = true;
            $query->andWhere( $query->expr()->orX(
                $query->expr()->like('t.name', ':search'),
                $query->expr()->like('p.title', ':search'),
                $query->expr()->like('p.path', ':search'),
                $query->expr()->like('pro.name', ':search')
            ))->setParameter('search', '%'.$params['search'].'%');
        }
        
        if(!empty($params['filter']) && !empty($params['filter']['column'])){
            $countSearch = true;
            $query->andWhere($query->expr()->eq($params['filter']['column'], ':value'))
                ->setParameter('value', $params['filter']['value']);
        }

        (!empty($params['order']) && !empty($params['order']['column']))
            ? $query->addOrderBy($params['order']['column'],strtoupper($params['order']['dir']))
            : $query->orderBy('t.id','DESC');

        $pg = new Paginator($query);
        $data = $pg->getQuery()->getArrayResult();
        return ['data' => $data, 'total' => ($countSearch)?count($data):(int)Theme::count()];
    }

    /**
     * @param array $profession
     * @param null $max
     * @return array
     */
    public function frontList($profession = [], $max = null){
        $query = Theme::queryBuilder();

        $query->select(['t'])
            ->addSelect('partial p.{id,title,path,alt}')
            ->addSelect('partial w.{id,domain}')
            ->addSelect('partial pro.{id,slug}')
            ->from('Jet\Models\Theme','t')
            ->leftJoin('t.thumbnail','p')
            ->leftJoin('t.professions','pro')
            ->leftJoin('t.website','w')
            ->where('t.state = :state')
            ->setParameter('state', 1)
            ->orderBy('t.id', 'DESC');

        if(!is_null($max)){
            $query->setMaxResults($max);
        }

        if(!empty($profession)){
            $query->andWhere($query->expr()->in('pro.slug', ':profession'))
                ->setParameter(':profession', $profession);
        }

        return $query->getQuery()->getArrayResult();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function read($id){
         return Theme::queryBuilder()
            ->select(['t'])
            ->addSelect('partial p.{id,title,path,alt}')
            ->addSelect('partial pro.{id,name,slug,icon}')
            ->addSelect('partial w.{id,domain}')
            ->addSelect('partial s.{id,name}')
            ->from('Jet\Models\Theme','t')
            ->leftJoin('t.thumbnail','p')
            ->leftJoin('t.professions','pro')
            ->leftJoin('t.website','w')
            ->leftJoin('w.society','s')
            ->where('t.id = :id')
            ->setParameter('id',$id)
            ->getQuery()->getArrayResult()[0];
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getThumbnail($id){
        return Theme::queryBuilder()
            ->select('partial t.{id}')
            ->addSelect('partial p.{id,title,path,alt}')
            ->from('Jet\Models\Theme','t')
            ->leftJoin('t.thumbnail','p')
            ->where('t.id = :id')
            ->setParameter('id',$id)
            ->getQuery()->getOneOrNullResult();
    }

} 