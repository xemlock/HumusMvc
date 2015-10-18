<?php

namespace HumusMvc\Service;

use HumusMvc\Exception;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FrontControllerFactory implements FactoryInterface
{
    /**
     * Create front controller service
     *
     * As a side-effect if a Layout service is present in the Service
     * Locator it will be retrieved
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend_Controller_Front
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \HumusMvc\Bootstrap\Bootstrap $bootstrap */
        $bootstrap = $serviceLocator->get('Bootstrap');

        if (!$bootstrap->hasPluginResource('FrontController')) {
            $bootstrap->registerPluginResource('FrontController');
        }

        /** @var \Zend_Application_Resource_Frontcontroller $resource */
        $resource = $bootstrap->getPluginResource('FrontController');

        /** @var \HumusMvc\Dispatcher $dispatcher */
        $dispatcher = $serviceLocator->get('Dispatcher');

        /** @var \Zend_Controller_Front $frontController */
        $frontController = $resource->getFrontController();
        $frontController->setDispatcher($dispatcher);

        // Initialize front controller resource _after_ setting dispatcher,
        // otherwise the dispatcher will not be properly configured
        $resource->init();

        // Retrieve controller paths from loaded modules and add them to dispatcher:
        // - if a module provides getControllerDirectory() method, its return value
        //   is used as a controller path for this module
        // - otherwise a default controller path will be used (module/controllers)
        $moduleManager = $serviceLocator->get('ModuleManager');

        foreach ($moduleManager->getLoadedModules() as $module => $moduleObj) {
            if (method_exists($moduleObj, 'getControllerDirectory')) {
                $dir = $moduleObj->getControllerDirectory();
            } else {
                $ref = new \ReflectionClass($moduleObj);
                $dir = dirname($ref->getFileName()) . '/' . $frontController->getModuleControllerDirectoryName();
            }
            $dispatcher->addControllerDirectory($dir, $module);
        }

        // Zend_Layout requires eager initialization - otherwise a controller
        // plugin that drives it will not be registered
        if ($bootstrap->hasPluginResource('Layout')) {
            $serviceLocator->get('Layout');
        }

        return $frontController;
    }
}
