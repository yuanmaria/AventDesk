<?php
/**
 * Created by PhpStorm.
 * User: Yuan
 * Date: 8/6/14
 * Time: 2:31 PM
 */

namespace App\Avent\Interfaces;


interface IWiki {
    public function addAction();
    public function updateAction();
    public function deleteAction();
    public function findAction();
} 