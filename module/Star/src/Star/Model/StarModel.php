<?php

namespace Star\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;

class StarModel {

    protected $adapter;

    /**
     * 构造函数
     * @param Array $config 数据库连接配置
     */
    public function __construct($config = null) {
        if ($config == null) {
            $this->adapter = new Adapter(array(
                'driver' => 'Pdo_Mysql',
                'database' => 'zf2',
                'hostname' => 'localhsot',
                'username' => 'root',
                'password' => '123456'
            ));
        } else {
            $this->adapter = new Adapter($config);
        }
    }

    /**
     * 
     * @param type 操作的数据表名称
     * @param type 查询条件
     * @return Array
     */
    public function fetchRow($table, $where = null) {
        $sql = "SELECT * FROM {$table}";
        if ($where != null) {
            $sql .= "WHERE {$where}";
        }
        $statement = $this->adapter->createStatement($sql);
        $result = $statement->execute();
        return $result;
    }

    /**
     * 返回查询的所有结果
     * @param type $table 数据表名称
     * @param type $where 查询条件
     * @return Array
     */
    public function fetchAll($table, $where = null) {
        $sql = "SELECT　* FROM {$table}";
        if ($where != null) {
            $sql .= "WHERE {$where}";
        }
        $statement = $this->adapter->createStatement($sql);
        $statement->prepare();
        $result = $statement->execute();

        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $row = $resultSet->toArray();
        return $row;
    }

    /**
     * 返回指定表的所有数据
     * @param type $table 表明称
     * @return Array
     */
    public function getTableRecords($table) {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from($table);
        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        return $resultSet->toArray();
    }

    /**
     * 插入数据到数据表
     * @param String $table 表名称
     * @param Array $data 插入的数据
     * @return Int 返回影响的行数
     */
    public function insert($table, $data) {
        $sql = new Sql($this->adapter);
        $insert = $sql->insert($table);
        $insert->values($data);
        return $sql->prepareStatementForSqlObject($insert)->execute()->getAffectedRows();
    }

    /**
     * 
     * 更新数据表 
     * @param String $table 数据表名称
     * @param Array $data 需要更新的数据
     * @param String $where 更新的条件
     * @return Int 返回受影响的条数
     */
    public function update($table, $data, $where) {
        $sql = new Sql($this->adapter);
        $update = $sql->update($table);
        $update->set($data);
        $update->where($where);
        return $sql->prepareStatementForSqlObject($update)->execute()->getAffectedRows();
    }

    /**
     * 删除数据
     * @param String $table 删除的表名称
     * @param String $where 删除的条件
     * @return Int 返回受影响的条数
     */
    public function delete($table, $where) {
        $sql = new Sql($this->adapter);
        $delete = $sql->delete($table)->where($where);
        return $sql->prepareStatementForSqlObject($delete)->execute()->getAffectedRows();
    }

    /**
     * 返回最后插入的主键的值
     * @return Int
     */
    public function lastInsertId() {
        return $this->adapter->getDriver()->getLastGeneratedValue();
    }

}
