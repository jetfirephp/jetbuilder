<?php

namespace Jet\Models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

/**
 * Class AddressRepository
 * @package Jet\Models
 */
class AddressRepository extends EntityRepository{

    /**
     * @param Website $website
     * @param $keys
     * @return mixed
     */
    public function retrieveData(Website $website, $keys){
        return Address::queryBuilder()->select($keys)
            ->from('Jet\Models\Address','a')
            ->join('Jet\Models\Society', 's', Join::WITH, 'a.id = s.address')
            ->join('Jet\Models\Website', 'w', Join::WITH, 's.id = w.society')
            ->where('w.id = :id')
            ->setParameter('id', $website->getId())
            ->getQuery()->getOneOrNullResult();
    }


} 