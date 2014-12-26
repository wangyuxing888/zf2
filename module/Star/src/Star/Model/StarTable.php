<?php

namespace Star\Model;

use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Select;
use Zend\Paginator\Adapter\DbSelect;
use Zend\Paginator\Paginator;

class StarTable {

    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll($paginated = false) {
        if ($paginated) { //判断是否分页、
            //实例化一个select，对指定表进行操作
            $select = new Select('star');
            //实例化一个结果集
            $resultSetPrototype = new ResultSet();
            //设置结果集的操作属性
            $resultSetPrototype->setArrayObjectPrototype(new Star());
            //实例化一个DbSelect，并通过获取网关以及对数据库进行操作，并将结果传递到$resultSetPrototype里面
            $paginatorAdapter = new DbSelect($select, $this->tableGateway->getAdapter(), $resultSetPrototype);
            //实例化一个分页导航，将DbSelect传递过去
            $paginator = new Paginator($paginatorAdapter);
            //返回分页导航实例
            return $paginator;
        }
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getStarById($id) {
        //将传递过来的id强制转换成整型
        $id = (int) $id;
        //根据id查询结果集
        $rowSet = $this->tableGateway->select(array('id' => $id));
        //取出结果集的第一行记录
        $row = $rowSet->current();
        if (!$row) {//判断是否存在指定id的记录行
            throw new \Exception("Could not find row $id");
        }
        //返回查询结果的记录行
        return $row;
    }

    public function saveStar(Star $star) {
        //将传递过来的数据保存到数组中，因为ZF2中对数据的操作很多是通过数组传递的
        $data = array(
            'artist' => $star->artist,
            'title' => $star->title,
        );
        $id = (int) $star->id;
        if ($id == 0) {
            //如果ID不存在的时候将数据里的数据插入到数据库，这里实现插入功能
            $this->tableGateway->insert($data);
        } else {
            if ($this->getStarById($id)) {
                //如果ID存在的时候，对数据库里指定ID的数据进行更新
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('star id does not exist');
            }
        }
    }

    public function deleteStarById($id) {
        $this->tableGateway->delete(array('id' => (int) $id));
    }

}
