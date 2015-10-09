<?php

namespace HumusMvc\Loader;

use Zend\Loader\ModuleAutoloader as Zf2ModuleAutoloader;

class ModuleAutoloader extends Zf2ModuleAutoloader
{
    /**
     * Autoload a module class
     *
     * @param   $class
     * @return  mixed
     *          False [if unable to load $class]
     *          get_class($class) [if $class is successfully loaded]
     */
    public function autoload($class)
    {
        if (false !== ($classLoaded = parent::autoload($class))) {
            return $classLoaded;
        }

        // Limit scope of this autoloader
        if (substr($class, -7) !== '\Module') {
            return false;
        }

        $moduleName = substr($class, 0, -7);

        // transform CamelCase namespace to spinal-case, and try to load it
        // PHP does not execute autoloading for invalid namespaces
        $namespace = substr($moduleName, 0, $pos = strpos($moduleName, '\\'));
        $namespace = strtolower(preg_replace('/([^A-Z])([A-Z])/', '$1-$2', $namespace));

        $class = $namespace . substr($moduleName, $pos) . '\Module';

        return parent::autoload($class);
    }
}
