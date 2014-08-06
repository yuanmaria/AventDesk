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

namespace App\Avent;

use Phalcon\Config\Adapter\Ini as IniConfig;
use Phalcon\Loader;

trait InitApplication
{
    protected $loader;
    protected $config;

    protected function init()
    {
        $this->loader = new Loader();
        $this->config = new IniConfig(APP_PATH . "/Config/app.ini");
        $this->di->setShared("config", $this->config);
    }

    protected function initAutoload()
    {
        $this->loader->registerNamespaces(
            array(
                "App\\Avent" => APP_PATH . "Avent/",
                "App\\Config\\Routing" => APP_PATH . "/Config/routing/"
            ),
            true
        );

        $this->loader->register();
    }

    protected function initService()
    {
        $services = new IniConfig(APP_PATH . "/Config/services.ini");

        foreach ($services as $key => $value) {
            $obj = new $value["class"];
            $obj->init($this->di, $value['provide'], $key);
        }
    }

    protected function initModule()
    {
        $modules = $this->config->module;
        $modulesArray = [];

        foreach ($modules as $key => $value) {
            $modulesArray[$key] = [
                'className' => $value,
                'path' => $this->config->application->moduleDir . $key . "/Module.php"
            ];
        }

        $this->registerModules($modulesArray);
    }

    protected function initPlugin()
    {
        $plugins = new IniConfig(APP_PATH . "/plugins/plugins.ini");

        foreach ($plugins as $plugin) {
            if ($plugin->enable == "1") {
                if ($plugin->environment == ENV || $plugin->environment == "all") {
                    $class = $plugin->namespace . "\\Plugin";
                    new $class($this->di);
                }
            }
        }
    }
}


// end of TraitsApplication.php
