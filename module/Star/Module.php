<?php
namespace Star;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

class Module implements AutoloaderProviderInterface, ConfigProviderInterface
{
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Star\Model\StarTable' => function($sm) {
                    $tableGateway = $sm->get('StarTableGateway');
                    $table = new Model\StarTable($tableGateway);
                    return $table;
                },
                'StarTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new \Zend\Db\ResultSet\ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Star\Model\Star());
                    return new \Zend\Db\TableGateway\TableGateway('star', $dbAdapter, null, $resultSetPrototype);
                },
            ),
            
        );
    }
}
