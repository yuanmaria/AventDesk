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


namespace App\Avent\Modules;

use Phalcon\Mvc\ModuleDefinitionInterface;
use Phalcon\Config\Adapter\Ini as IniConfig;

abstract class AbstractModule implements ModuleDefinitionInterface
{

    abstract public function registerAutoloaders();

    abstract public function registerServices($di);

    protected function initPlugin($di)
    {
        $plugins = new IniConfig(APP_PATH . "Avent/Plugins/plugins.ini");

        foreach ($plugins as $plugin) {
            if ($plugin->enable == "1") {
                if ($plugin->environment == ENV || $plugin->environment == "all") {
                    $class = $plugin->namespace . "\\Plugin";
                    new $class($di);
                }
            }
        }
    }
}

// end of AbstractModule.php
