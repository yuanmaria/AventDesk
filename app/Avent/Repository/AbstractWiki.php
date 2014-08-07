<?php
/**
 * Created by PhpStorm.
 * User: Yuan
 * Date: 8/6/14
 * Time: 3:13 PM
 */

namespace App\Avent\Repository;


abstract class AbstractWiki
{
    abstract protected function add();
    abstract protected function update();
    abstract protected function delete();
}