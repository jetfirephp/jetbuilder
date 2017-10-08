<?php

namespace Jet\Models;

use Doctrine\ORM\Tools\Pagination\Paginator;

class TemplateRepository extends AppRepository
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
        $config = Template::em()->getConfiguration();
        $config->addCustomStringFunction('DATE_FORMAT', 'DoctrineExtensions\Query\Mysql\DateFormat');

        $query = Template::queryBuilder();

        /* Query */
        $query->select(['t.id AS id', 't.name as name', 't.title as title', 't.content as content', 't.category as category', 't.scope as scope', 't.type as type', 'DATE_FORMAT(t.updated_at,\'%d/%m/%Y à %Hh%i\') as updated_at'])
            ->from('Jet\Models\Template', 't')
            ->setFirstResult($start)
            ->setMaxResults($max);

        /* Search params */
        if (!empty($params['search'])) {
            $countSearch = true;
            $query->andWhere($query->expr()->orX(
                $query->expr()->like('t.name', ':search'),
                $query->expr()->like('t.title', ':search'),
                $query->expr()->like('t.category', ':search'),
                $query->expr()->like('t.type', ':search'),
                $query->expr()->like('t.scope', ':search')
            ))->setParameter('search', '%' . $params['search'] . '%');
        }

        /* Order params */
        if (!empty($params['order'])) {
            $columns = ['t.id','t.name', 't.title', 't.content', 't.category', 't.scope', 't.type', 't.updated_at'];
            foreach ($params['order'] as $order) {
                if(isset($columns[$order['column']]))
                    $query->addOrderBy($columns[$order['column']], strtoupper($order['dir']));
            }
        } else {
            $query->orderBy('t.id', 'DESC');
        }

        $pg = new Paginator($query);
        $data = $pg->getQuery()->getArrayResult();
        return ['data' => $data, 'total' => ($countSearch) ? count($data) : (int)Template::count()];
    }

    /**
     * @param $exclude
     * @param $websites
     * @param $category
     * @return array
     */
    public function getWebsiteTemplates($exclude, $websites, $category)
    {
        $query = Template::queryBuilder()
            ->select('partial t.{id,name,title}')
            ->from('Jet\Models\Template', 't')
            ->leftJoin('t.website', 'w');

        $query->where($query->expr()->orX(
            $query->expr()->in('w.id', ':websites'),
            $query->expr()->isNull('t.website')
        ))
            ->andWhere($query->expr()->eq('t.category', ':category'))
            ->setParameter('websites', $websites)
            ->setParameter('category', $category);
        
        $query = $this->excludeData($query, $exclude, 'templates');

        return $query->getQuery()->getArrayResult();
    }


}