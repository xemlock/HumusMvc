<?php

namespace HumusMvc\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RouterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \HumusMvc\Bootstrap\Bootstrap $bootstrap */
        $bootstrap = $serviceLocator->get('Bootstrap');
        if (($resource = $bootstrap->getPluginResource('Router')) !== null) {
            return $resource->init();
        }
        return \Zend_Controller_Front::getInstance()->getRouter();
    }
}