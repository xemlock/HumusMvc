<?php

namespace HumusMvc\Service;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ResourceFactory implements AbstractFactoryInterface
{
    public function canCreateServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        /** @var \HumusMvc\Bootstrap\Bootstrap $bootstrap */
        $bootstrap = $serviceLocator->get('Bootstrap');
        return $bootstrap->hasPluginResource($requestedName);
    }

    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        /** @var \HumusMvc\Bootstrap\Bootstrap $bootstrap */
        $bootstrap = $serviceLocator->get('Bootstrap');
        return $bootstrap->getPluginResource($requestedName)->init();
    }
}
