<?php

namespace Jet\Models;

use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Class AccountRepository
 * @package Jet\Models
 */
class AccountRepository extends EntityRepository
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
        $query = Account::queryBuilder();

        $query->select(['partial a.{id,first_name,last_name,email,phone,state,registered_at}'])
            ->addSelect('partial p.{id,path,alt,title}')
            ->addSelect('partial s.{id,role}')
            ->from('Jet\Models\Account', 'a')
            ->leftJoin('a.photo', 'p')
            ->leftJoin('a.status', 's')
            ->setFirstResult(($page - 1) * $max)
            ->setMaxResults($max);

        if (isset($params['role']) && is_array($params['role']) && !empty($params['role'])) {
            foreach ($params['role'] as $op => $value){
                $query->andWhere('s.level '.$op.' :level_'.$value)
                    ->setParameter('level_'.$value, $value);
            }
        }

        if (!empty($params['search'])) {
            $countSearch = true;
            $query->andWhere($query->expr()->orX(
                $query->expr()->like('a.first_name', ':search'),
                $query->expr()->like('a.last_name', ':search'),
                $query->expr()->like('a.email', ':search'),
                $query->expr()->like('a.phone', ':search')
            ))->setParameter('search', '%' . $params['search'] . '%');
        }

        if (!empty($params['filter']) && !empty($params['filter']['column'])) {
            $countSearch = true;
            $query->andWhere($query->expr()->eq($params['filter']['column'], ':value'))
                ->setParameter('value', $params['filter']['value']);
        }

        (!empty($params['order']) && !empty($params['order']['column']))
            ? $query->addOrderBy($params['order']['column'], strtoupper($params['order']['dir']))
            : $query->orderBy('a.id', 'DESC');

        $pg = new Paginator($query);
        $data = $pg->getQuery()->getArrayResult();
        return ['data' => $data, 'total' => ($countSearch) ? count($data) : (int)$this->countAccount($params)];
    }

    /**
     * @param $params
     * @return array
     */
    public function store($params)
    {
        $checkAccount = Account::where('email', $params['account']['email'])->count();
        if ($checkAccount == 0) {
            $checkWebsite = Website::where('domain', $params['society']['slug'])->count();
            if ($checkWebsite == 0) {
                $xy = geocode($params['address']['address'] . ', ' . $params['address']['postal_code'] . ' ' . $params['address']['city']);
                $params['address']['latitude'] = $xy[0];
                $params['address']['longitude'] = $xy[1];
                $address = new Address();
                $address->store($params['address'], $address);
                if (Address::watch($address)) {
                    $params['account']['status'] = Status::findOneByRole($params['status']);
                    if (is_null($params['status'])) return ['status' => 'error', 'message' => 'Impossible de trouver le role de l\'utilisateur'];

                    $params['account']['photo'] = (isset($params['account']['photo']))
                        ? Media::findOneById($params['account']['photo'])
                        : Media::findOneByPath('/public/media/default/user-photo.png');
                    if (is_null($params['account']['photo'])) return ['status' => 'error', 'message' => 'Impossible de trouver la photo'];

                    $account = new Account();
                    $account->setToken($params['token']);
                    $to = new \DateTime();
                    $to->add(new \DateInterval('P1D'));
                    $account->setTokenTime($to);
                    $account->store($params['account'], $account);
                    if (Account::watch($account)) {
                        $society = new Society();
                        $society->setName($params['society']['name']);
                        $society->setEmail($params['account']['email']);
                        $society->setPhone($params['account']['phone']);
                        $society->setAccount($account);
                        $society->setAddress($address);
                        $address->setAccount($account);
                        return (Society::watchAndSave($society))
                            ? ['status' => 'success', 'account' => $account->getId(), 'society' => $society->getId()]
                            : ['status' => 'error', 'message' => 'Erreur lors de la création du compte'];
                    }
                    return ['status' => 'error', 'message' => 'Erreur lors de la création du compte'];
                }
                return ['status' => 'error', 'message' => 'Erreur lors de la création de l\'adresse'];
            }
            return ['status' => 'error', 'message' => 'La société existe déjà'];
        }
        return ['status' => 'error', 'message' => 'L\'e-mail est déjà utilisé par un autre compte'];
    }

    /**
     * @param $id
     * @param $account
     * @param $params
     * @return array|bool
     */
    public function update($id, $account, $params)
    {
        $count = ($id == 'create')
            ? Account::where('email', $params['email'])->count()
            : Account::whereRaw('a.id <> ?1 AND a.email = ?2', [$account->getId(), $params['email']])->count();
        if ($count == 0) {

            if (isset($params['status']) && !empty($params['status']) && isset($params['status']['id'])) {
                $params['status'] = Status::findOneById($params['status']['id']);
                if (is_null($params['status'])) return ['status' => 'error', 'message' => 'Le status n\'a pas été trouvé'];
            }else
                return ['status' => 'error', 'message' => 'Impossible de trouver le status'];


            if (isset($params['photo']) && !empty($params['photo']) && isset($params['photo']['id'])) {
                $params['photo'] = Media::findOneById($params['photo']['id']);
                if (is_null($params['photo']))
                    return ['status' => 'error', 'message' => 'La photo n\'a pas été trouvée'];
            } else
                $params['photo'] = null;

            if (empty($params['password'])) unset($params['password']);

            return Account::store($params, $account)->watchAndSave($account);
        }
        return ['status' => 'error', 'message' => 'L\'e-mail est déjà utilisé'];
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function countAccount($params = [])
    {
        $query = Account::queryBuilder();
        $query->select('COUNT(a.id)')
            ->from('Jet\Models\Account', 'a');

        if (isset($params['role']) && is_array($params['role']) && !empty($params['role'])) {
            $query->leftJoin('a.status', 's');
            foreach ($params['role'] as $op => $value){
                $query->andWhere('s.level '.$op.' :level_'.$value)
                    ->setParameter('level_'.$value, $value);
            }
        }

        return $query->getQuery()->getSingleScalarResult();
    }

    /**
     * @return int
     */
    public function countUser()
    {
        $query = Account::queryBuilder();
        $query->select('COUNT(a.id)')
            ->from('Jet\Models\Account', 'a')
            ->leftJoin('a.status', 's')
            ->where($query->expr()->eq('s.role', ':role'))
            ->setParameter('role', 'user');
        return (int)$query->getQuery()->getSingleScalarResult();
    }

    /**
     * @param $website
     * @return mixed
     */
    public function getWebsiteAccount($website)
    {
        $query = Account::queryBuilder()
            ->select('partial a.{id, first_name, last_name, phone, email, state, registered_at}')
            ->from('Jet\Models\Account', 'a')
            ->join('Jet\Models\Society', 's', Join::WITH, 'a.id = s.account')
            ->join('Jet\Models\Website', 'w', Join::WITH, 's.id = w.society');
        return $query->where($query->expr()->eq('w', ':website'))
            ->setParameter('website', $website)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @param $ids
     * @return array
     */
    public function findById($ids)
    {
        $query = Account::queryBuilder()
            ->select('partial a.{id}')
            ->addSelect('partial s.{id,role,level}')
            ->from('Jet\Models\Account', 'a')
            ->leftJoin('a.status', 's');
        return $query->where($query->expr()->in('a.id', ':ids'))
            ->setParameter('ids', $ids)
            ->getQuery()->getArrayResult();
    }
    
    /**
     * @param $start
     * @param $end
     * @return int
     */
    public function listBetweenDates($start, $end)
    {
        $query = Account::queryBuilder();
        $query->select('COUNT(a.id)')
            ->from('Jet\Models\Account', 'a')
            ->leftJoin('a.status', 's')
            ->where($query->expr()->eq('s.level', 4));
        $query->andWhere($query->expr()->between('a.registered_at', ':start', ':end'))
            ->setParameter('start', $start)
            ->setParameter('end', $end);
        return (int)$query->getQuery()->getSingleScalarResult();
    }


    /**
     * @description method for template
     * @param Website $website
     * @param $keys
     * @return mixed
     */
    public function retrieveData(Website $website, $keys)
    {
        return Account::queryBuilder()->select($keys)
            ->from('Jet\Models\Account', 'a')
            ->join('Jet\Models\Society', 's', Join::WITH, 'a.id = s.account')
            ->join('Jet\Models\Website', 'w', Join::WITH, 's.id = w.society')
            ->where('w.id = :id')
            ->setParameter('id', $website->getId())
            ->getQuery()->getOneOrNullResult();
    }

} 