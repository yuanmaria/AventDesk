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


namespace App\Avent\Plugins;

use Phalcon\Mvc\User\Plugin;

abstract class AbstractPlugin extends Plugin
{

    protected $_di;
    protected $_event;

    protected function registerServiceEventManager(array $services, $object)
    {
        $di = $this->getDI();
        $eventManager = $this->getEventsManager();

        $this->attachServiceEvent($services, $object);

        foreach ($di->getServices() as $service) {
            $name = $service->getName();
            foreach ($services as $key => $value) {
                if ($name == $value['service']) {
                    if ($value['shared']) {
                        $service->setShared(true);
                    }
                    $di->get($name)->setEventsManager($eventManager);
                    break;
                }
            }
        }
    }

    private function attachServiceEvent(array $services, $object)
    {
        $eventManager = $this->getEventsManager();

        foreach (array_keys($services) as $eventName) {
            $eventManager->attach($eventName, $object);
        }
    }

    public function setDI($di)
    {
        $this->_di = $di;
    }

    public function getDI()
    {
        return $this->_di;
    }

    public function setEventsManager($eventManager)
    {
        $this->_event= $eventManager;
    }

    public function getEventsManager()
    {
        return $this->_event;
    }
}

// end of AbstractPlugin.php
