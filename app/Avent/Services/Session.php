<?php
/**
 * Copyright (C) 2014 Nanomites, Inc.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */


namespace App\Avent\Services;

use Phalcon\DiInterface as DI;
use Phalcon\Session\Adapter\Redis as RedisSession;

class Session extends AbstractServiceProvider
{
    public function init(DI $di, $provide, $configKey)
    {
        $di->setShared(
            $provide,
            function () {
                $session = new RedisSession(array(
                    'path' => "tcp://127.0.0.1:6379?weight=1"
                ));

                $session->start();

                return $session;
            }
        );
    }
}

// end of Session.php 