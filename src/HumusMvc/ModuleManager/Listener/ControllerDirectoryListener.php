<?php

namespace HumusMvc\ModuleManager\Listener;

use Zend\ModuleManager\ModuleEvent;

class ControllerDirectoryListener
{
    /**
     * @param \Zend\ModuleManager\ModuleEvent $e
     */
    public function __invoke(ModuleEvent $e)
    {
        $module = $e->getModule();
        echo get_class($module);exit;
    }
}
