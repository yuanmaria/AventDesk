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
 
 

namespace Nanomites\Modules\Admin;


class Module implements \Phalcon\Mvc\ModuleDefinitionInterface
{

    public function registerAutoloaders()
    {
        $loader = new \Phalcon\Loader();

        $loader->registerNamespaces(
            [
                "Nanomites\\Modules\\Admin\\Controllers" => dirname(__FILE__) . "/controllers/"
            ]
        );

        $loader->register();
    }

    public function registerServices($di)
    {
        $config = $di->get("config");

        $di->set(
            "dispatcher",
            function () use ($di) {
                $dispatcher = new \Phalcon\Mvc\Dispatcher();
                $dispatcher->setDefaultNamespace("Nanomites\\Modules\\Admin\\Controllers");
                return $dispatcher;
            }
        );

        $di->set(
            "view",
            function () use ($config) {
                $view = new \Phalcon\Mvc\View();
                $view->setViewsDir(dirname(__FILE__) . "/views/");
                $view->registerEngines(array(
                        ".volt" => 'voltService'
                    ));
                return $view;
            }
        );
    }
}
// end of Module.php 