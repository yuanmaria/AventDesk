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
use Phalcon\Mvc\Router as Router;

class MvcRouter extends AbstractServiceProvider
{
    public function init(DI $di, $provide, $configKey)
    {
        $config = self::loadConfig($configKey);

        $modules = $di->get("config")->module;

        $di->set(
            $provide,
            function () use ($config, $modules) {
                $router = new Router();
                $router->setDefaultModule($config->mvcrouter->defaultModule);
                $router->setDefaultController('index');
                $router->setDefaultAction('index');
                $router->removeExtraSlashes(true);

                $files = array_diff(scandir(APP_PATH . "Config/routing/"), array('..', '.'));

                foreach ($files as $file) {
                    $explode = explode(".", $file);
                    $class = "App\\Config\\Routing\\" . $explode[0];
                    $router->mount(new $class());
                }

                return $router;
            }
        );
    }
}

// end of Router.php
