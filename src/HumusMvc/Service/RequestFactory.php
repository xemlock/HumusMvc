<?php

namespace HumusMvc\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RequestFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \HumusMvc\Bootstrap\Bootstrap $bootstrap */
        $bootstrap = $serviceLocator->get('Bootstrap');
        if (($resource = $bootstrap->getPluginResource('Request')) !== null) {
            return $resource->init();
        }
        return new \Zend_Controller_Request_Http();
    }
}