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
use Phalcon\Config\Adapter\Ini as Config;

abstract class AbstractServiceProvider
{

    abstract public function init(DI $di, $provide, $configKey);

    protected static function loadConfig($className)
    {
        return new Config(APP_PATH . "Config/" . strtolower($className) . ".ini");
    }
}

// end of AbstractServiceProvider.php
