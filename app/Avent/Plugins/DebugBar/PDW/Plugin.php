<?php

namespace App\Avent\Plugins\DebugBar\PDW;

use Phalcon\Db\Profiler as Profiler,
    Phalcon\Escaper as Escaper,
    Phalcon\Mvc\Url as URL,
    Phalcon\Mvc\View as View,
    Phalcon\DiInterface as DI,
    App\Avent\Plugins\AbstractPlugin;

class Plugin extends AbstractPlugin
{
    private $startTime;
    private $endTime;
    private $queryCount = 0;
    protected $_profiler;
    protected $_viewsRendered = array();
    protected $_serviceNames = array(
        "db" => array("service" => "db", "shared" => true),
        "dispatch" => array("service" => "dispatcher", "shared" => true),
        "view" => array("service" => "view", "shared" => true)
    );

    public function __construct(DI $di)
    {
        $this->_di = $di;
        $this->_event = $di->getShared('eventsManager');

        $this->startTime = microtime(true);
        $this->_profiler = new Profiler();

        $this->registerServiceEventManager($this->_serviceNames, $this);
    }

    public function getServices($event)
    {
        return $this->_serviceNames[$event];
    }

    public function beforeQuery($event, $connection)
    {
        $this->_profiler->startProfile($connection->getRealSQLStatement());
    }

    public function afterQuery($event, $connection)
    {
        $this->_profiler->stopProfile();
        $this->queryCount++;
    }

    /**
     * Gets/Saves information about views and stores truncated viewParams.
     *
     * @param unknown $event
     * @param unknown $view
     * @param unknown $file
     */
    public function beforeRenderView($event, $view, $file)
    {
        $params = array();
        $toView = $view->getParamsToView();
        $toView = !$toView ? array() : $toView;
        foreach ($toView as $k => $v) {
            if (is_object($v)) {
                $params[$k] = get_class($v);
            } elseif (is_array($v)) {
                $array = array();
                foreach ($v as $key => $value) {
                    if (is_object($value)) {
                        $array[$key] = get_class($value);
                    } elseif (is_array($value)) {
                        $array[$key] = 'Array[...]';
                    } else {
                        $array[$key] = $value;
                    }
                }
                $params[$k] = $array;
            } else {
                $params[$k] = (string)$v;
            }
        }

        $this->_viewsRendered[] = array(
            'path' => $view->getActiveRenderPath(),
            'params' => $params,
            'controller' => $view->getControllerName(),
            'action' => $view->getActionName(),
        );
    }


    public function afterRender($event, $view, $viewFile)
    {
        $this->endTime = microtime(true);
        $content = $view->getContent();
        $scripts = $this->getInsertScripts();
        $scripts .= "</head>";
        $content = str_replace("</head>", $scripts, $content);
        $rendered = $this->renderToolbar();
        $rendered .= "</body>";
        $content = str_replace("</body>", $rendered, $content);

        $view->setContent($content);
    }

    /**
     * Returns scripts to be inserted before <head>
     * Since setBaseUri may or may not end in a /, double slashes are removed.
     *
     * @return string
     */
    public function getInsertScripts()
    {
        $escaper = new Escaper();
        $url = $this->getDI()->get('url');
        $scripts = "";

        $css = array('/pdw-assets/style.css', '/pdw-assets/lib/prism/prism.css');
        foreach ($css as $src) {
            $link = $url->get($src);
            $link = str_replace("//", "/", $link);
            $scripts .= "<link rel='stylesheet' type='text/css' href='" . $escaper->escapeHtmlAttr($link) . "' />";
        }

        $js = array(
            '/pdw-assets/jquery.min.js',
            '/pdw-assets/lib/prism/prism.js',
            '/pdw-assets/pdw.js'
        );
        foreach ($js as $src) {
            $link = $url->get($src);
            $link = str_replace("//", "/", $link);
            $scripts .= "<script tyle='text/javascript' src='" . $escaper->escapeHtmlAttr($link) . "'></script>";
        }

        return $scripts;
    }

    public function renderToolbar()
    {
        $view = new View();
        $viewDir = dirname(__FILE__) . '/views/';
        $view->setViewsDir($viewDir);

        // set vars
        $view->debugWidget = $this;

        $content = $view->getRender('toolbar', 'index');
        return $content;
    }

    public function getStartTime()
    {
        return $this->startTime;
    }

    public function getEndTime()
    {
        return $this->endTime;
    }

    public function getRenderedViews()
    {
        return $this->_viewsRendered;
    }

    public function getQueryCount()
    {
        return $this->queryCount;
    }

    public function getProfiler()
    {
        return $this->_profiler;
    }
}
