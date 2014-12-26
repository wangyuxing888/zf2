<?php

namespace Star\Model;

use Zend\Db\Adapter\Adapter as DbAdapter;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Authentication\AuthenticationService;

class Myauth {

    protected $adapter;

    public function __construct() {
        $this->adapter = new DbAdapter(array(
            'driver' => 'Pdo_Mysql',
            'database' => 'zf2',
            'host' => '127.0.0.1',
            'username' => 'root',
            'password' => '123456',
        ));
    }

    /**
     * 进行认证
     * @return boolean
     */
    public function auth() {
        //实例为一个认证适配器
        $authAdapter = new AuthAdapter($this->adapter);
        $authAdapter->setTableName('user') //认证的数据表
                ->setIdentityColumn('username') //认证的字段 
                ->setCredentialColumn('password'); //校验字段
        $authAdapter->setIdentity('123')//认证值
                ->setCredential('123'); //校验值
        //实例化一个认证服务，以实现持久性认证
        $auth = new AuthenticationService();
        $result = $auth->authenticate($authAdapter);
        if ($result->isValid()) {
            $auth->getStorage()->write($authAdapter->getResultRowObject());
            return TRUE;
        }
        return FALSE;
    }

    /**
     * 通过持久性判断是否已经通过验证
     * @return boolean
     */
    public function isAuth() {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            return TRUE;
        }
        return FALSE;
    }

}
