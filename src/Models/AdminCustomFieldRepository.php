<?php

namespace Jet\Models;

use Doctrine\ORM\EntityRepository;

/**
 * Class AdminCustomFieldRepository
 * @package Jet\Models
 */
class AdminCustomFieldRepository extends EntityRepository
{

    /**
     * @param $type
     * @param $website
     * @return mixed
     */
    public function getWebsiteTypeFields($type, $website)
    {
        $query = AdminCustomField::queryBuilder();
        $query->select('partial f.{id,data,content}')
            ->from('Jet\Models\AdminCustomField', 'f')
            ->leftJoin('f.custom_field', 'c')
            ->leftJoin('c.website', 'w')
            ->where($query->expr()->eq('w.id',':id'))
            ->setParameter('id', $website)
            ->andWhere($query->expr()->eq('f.type',$type));
        return $query->getQuery()->getArrayResult();
    }

}