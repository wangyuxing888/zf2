<?php

return array(
    'db' => array(
        'driver' => 'Pdo',
        'dsn' => 'mysql:dbname=zf2;host=127.0.0.1',
        'driver_options' => array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
        ),
    ),
    'service_manager' => array(
        'factories' => array(
//            'Zend\Db\Adapter\Adapter' 
//                    => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Zend\Db\Adapter\Adatper' => function($serviceManager) {
                $adapterFactory = new Zend\Db\Adapter\AdapterAbstractServiceFactory();
                $adapter = $adapterFactory->createService($serviceManager);
                \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($adapter);
                return $adapter;
            }
        ),
    ),
);
