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

namespace App\Modules\Customer;

use Phalcon\Config\Adapter\Ini as IniConfig;
use Phalcon\Loader;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Mvc\View;
use App\Avent\Modules\AbstractModule;

class Module extends AbstractModule
{

    public function registerAutoloaders()
    {
        $loader = new Loader();

        $loader->registerNamespaces(
            [
                "App\\Modules\\Customer\\Controllers" => dirname(__FILE__) . "/controllers/"
            ],
            true
        );

        $loader->register();
    }

    public function registerServices($di)
    {
        $config = $di->get("config");

        $di->set(
            "dispatcher",
            function () use ($di) {
                $dispatcher = new Dispatcher();
                $dispatcher->setDefaultNamespace("App\\Modules\\Customer\\Controllers");
                return $dispatcher;
            }
        );

        $di->set(
            "view",
            function () use ($config) {
                $view = new View();
                $view->setViewsDir($config->application->templatePath . $config->application->theme . "/customer/");
                $view->registerEngines(
                    array(
                        ".volt" => 'voltService'
                    )
                );
                return $view;
            }
        );

        $this->initPlugin($di);
    }
}

// end of Module.php
