<?php
/**
 * Created by PhpStorm.
 * User: Yuan
 * Date: 8/6/14
 * Time: 5:33 PM
 */

namespace App\Avent\Repository;


class PersonCreator {

    public function createAdmin()
    {
        return new Admin();
    }
    public function createAgent()
    {
        return new Agent();
    }
    public function createCustomer()
    {
        return new Customer();
    }
} 