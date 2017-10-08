<?php

namespace Jet\AdminBlock\Controllers;

use Jet\AdminBlock\Requests\AddressRequest;
use Jet\Models\Account;
use Jet\Models\Address;

/**
 * Class AddressController
 * @package Jet\AdminBlock\Controllers
 */
class AddressController extends AdminController
{

    /**
     * @param AddressRequest $request
     * @param $id
     * @return array
     */
    public function updateOrCreate(AddressRequest $request, $id)
    {
        if ($request->method() == 'PUT' || $request->method() == 'POST') {
            $response = $request->validate();
            if ($response === true) {
                $values = $request->values();
                $address = ($id == 'create') ? new Address() : Address::findOneById($id);
                $account = Account::findOneById($values['account']);
                if (!is_null($account)) {
                    if (isset($values['alias'])) {
                        $address->setAlias($values['alias']);
                    }
                    $address->setAddress($values['address']);
                    $address->setCity($values['city']);
                    $address->setPostalCode($values['postal_code']);
                    $address->setCountry($values['country']);
                    $address->setAccount($account);

                    if (isset($values['latitude']) && isset($values['longitude'])) {
                        $address->setLatitude($values['latitude']);
                        $address->setLongitude($values['longitude']);
                    }
                    return (Address::watchAndSave($address))
                        ? ['status' => 'success', 'message' => 'L\'adresse a bien été enregistrée']
                        : ['status' => 'error', 'message' => 'L\'adresse n\'a pas pu être enregistrée'];
                }
                return ['status' => 'error', 'message' => 'Impossible de trouver le compte'];
            }
            return $response;
        }
        return ['status' => 'error', 'message' => 'Requête non autorisée'];
    }

    /**
     * @param AddressRequest $request
     * @return array
     */
    public function locate(AddressRequest $request)
    {
        if ($request->has('address')) {
            $address = $request->get('address');
            $xy = geocode($address['address'] . ', ' . $address['postal_code'] . ' ' . $address['city'] . ', ' . $address['country']);
            return (empty($xy) || !$xy)
                ? ['status' => 'error', 'message' => 'Impossible de localiser l\'adresse']
                : ['status' => 'success', 'latitude' => $xy[0], 'longitude' => $xy[1]];
        }
        return ['status' => 'error', 'message' => 'Adresse non trouvée'];
    }
}