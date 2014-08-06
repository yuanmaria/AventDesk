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


namespace App\Avent\Plugins\Acl;

use App\Avent\Plugins\AbstractPlugin;
use Phalcon\Events\Event;
use Phalcon\Mvc\Dispatcher;
use Phalcon\Acl;

class Plugin extends AbstractPlugin
{
    protected $_serviceNames = array("dispatch" => array("service" => "dispatcher", "shared" => true));

    public function __construct($di)
    {
        $this->_di = $di;
        $this->_event = $di->getShared('eventsManager');

        $this->registerServiceEventManager($this->_serviceNames, $this);
    }

    public function getAcl()
    {
        $connection = $this->_di['db'];
        $acl = new \Phalcon\Acl\Adapter\Database(array(
            'db' => $connection,
            'roles' => 'roles',
            'rolesInherits' => 'roles_inherits',
            'resources' => 'resources',
            'resourcesAccesses' => 'resources_accesses',
            'accessList' => 'access_list',
        ));

        $acl->setDefaultAction(Acl::DENY);

        return $acl;
    }

    public function beforeDispatch(Event $event, Dispatcher $dispatcher)
    {
        $auth = $this->_di['session']->get("auth");

        if (!$auth) {
            $role = "guest";
        } else {
            $role = $this->_di['session']->get("role");
        }

        $module = $dispatcher->getModuleName();
        $action = $dispatcher->getActionName();
        $controller = strtolower($module) . "." . $dispatcher->getControllerName();

        $acl = $this->getAcl();

        $allowed = $acl->isAllowed($role, $controller, $action);

        if ($allowed != ACL::ALLOW) {
            $this->flash->error("You don't have access to this module");
            if ($dispatcher->getControllerName() != "index") {
                $this->_di['response']->redirect("");
                return false;
            }
        }
    }
}

// end of Plugin.php
