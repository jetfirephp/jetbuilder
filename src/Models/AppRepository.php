<?php

namespace Jet\Models;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

/**
 * Class AppRepository
 * @package Jet\Models
 */
class AppRepository extends EntityRepository
{

    /**
     * @param QueryBuilder $query
     * @param $params
     * @param $table
     * @param $alias
     * @param int $index
     * @return mixed
     */
    protected function excludeData(QueryBuilder $query, $params, $table, $alias = null, $index = 0)
    {
        if (isset($params['parent_exclude'][$table]) && !empty($params['parent_exclude'][$table])) {
            if (is_null($alias)) $alias = $table[0];
            $query->andWhere($query->expr()->notIn($alias . '.id', ':' . $table . '_exclude_ids_' . $index))
                ->setParameter($table . '_exclude_ids_' . $index, $params['parent_exclude'][$table]);
        }
        return $query;
    }


    /**
     * @param QueryBuilder $query
     * @param $params
     * @param $table
     * @param $alias
     * @param int $index
     * @return mixed
     */
    protected function replaceData(QueryBuilder $query, $params, $table, $alias = null, $index = 0)
    {
        if (isset($params['parent_replace'][$table]) && !empty($params['parent_replace'][$table])) {
            if (is_null($alias)) $alias = $table[0];
            $query->orWhere($query->expr()->in($alias . '.id', ':' . $table . '_replace_ids_' . $index))
                ->setParameter($table . '_replace_ids_' . $index, array_values($params['parent_replace'][$table]));
        }
        return $query;
    }
}