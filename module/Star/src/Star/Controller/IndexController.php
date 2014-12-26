<?php

namespace Star\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Star\Model\Star;
use Star\Form\StarForm;

class IndexController extends AbstractActionController {

    protected $starTable;

    public function getStarTable() {
        if (!$this->starTable) {
            $sm = $this->getServiceLocator();
            $this->starTable = $sm->get('Star\Model\StarTable');
        }
        return $this->starTable;
    }

    public function indexAction() {
        //使用分页进行操作
        $paginator = $this->getStarTable()->fetchAll(TRUE);
        //设置当前页，如果不存在页面则默认为第一页
        $paginator->setCurrentPageNumber((int) $this->params()->fromQuery('page', 1));
        //设置每个分页将显示的记录行数
        $paginator->setItemCountPerPage(10);
        //将分页导航对象返回给模版调用
        return new ViewModel(array(
            'paginator' => $paginator,
        ));
    }

    public function addAction() {
        //实例化表单
        $form = new StarForm();
        //提交按钮的名称
        $form->get('submit')->setValue('add');
        //获取用户的请求
        $request = $this->getRequest();
        if ($request->isPost()) {//判断是否为POST请求
            $star = new Star();
            //为表单添加过滤器
            $form->setInputFilter($star->getInputFilter());
            //设置表单数据
            $form->setData($request->getPost());

            if ($form->isValid()) {//检查表单是否通过验证
                //交换表单的数据
                $star->exchangeArray($form->getData());
                //通过模型表单提交的数据保存到数据库中
                $this->getStarTable()->saveStar($star);
                //实现路由跳转
                return $this->redirect()->toRoute('star');
            }
        }
        //返回一个表单对象
        return array('form' => $form);
    }

    public function editAction() {
        //从路由中分离Id,获取id
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {//如果id不存在的话直接跳转到添加新闻页面
            return $this->redirect()->toRoute('star', array(
                        'action' => 'add',
            ));
        }
        try {
            //通过网关指定的id获取一条记录
            $star = $this->getStarTable()->getStarById($id);
        } catch (Exception $ex) {
            //出现异常的话，跳转到列表页面
            return $this->redirect()->toRoute('star', array(
                        'action' => 'index',
            ));
        }
        //实例化一个表单
        $form = new StarForm();
        //给表单绑定数据
        $form->bind($star);
        //设置表单提交按钮的值
        $form->get('submit')->setAttribute('value', 'Edit');
        //获取用户请求
        $request = $this->getRequest();
        //判断是否为POST请求
        if ($request->isPost()) {
            //为表单添加过滤器
            $form->setInputFilter($star->getInputFilter());
            //为表单附加数据
            $form->setData($request->getPost());
            //判断表单是否通过校验
            if ($form->isValid()) {
                //将编辑后的数据更新到数据库
                $this->getStarTable()->saveStar($star);
                //跳转到列表页面
                return $this->redirect()->toRoute('star');
            }
        }
        //返回表单对象和id到模版，此处的表单对象和插入数据的表单有所区别，此表单里面的标签都已经有数据了
        //而插入的表单只是一个空的表单
        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function deleteAction() {
        //获取记录的ID 
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {//判断实佛有传递ID值，如果没有直接跳转到列表页面
            return $this->redirect()->toRoute('star');
        }
        $request = $this->getRequest();
        //判断用户的请求类型是否为POST请求
        if ($request->isPost()) {
            //获取用户的处理动作
            $del = $request->getPost('del', 'No');
            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                //删除指定的记录
                $this->getStarTable()->deleteStarById($id);
                //完成删除后跳转到列表页面
                return $this->redirect()->toRoute('star');
            }
        }
        //如果请求不是Post，返回数据给模版
        return array(
            'id' => $id,
            'star' => $this->getStarTable()->getStarById($id),
        );
    }

    /**
     * 验证是否可能对指定的用户名与密码进行认证
     */
    public function authAction() {
        $auth = new \Star\Model\Myauth();
        if ($auth->auth()) {
            echo "Authentication  Success";
        } else {
            echo "Authentication Failure";
        }
    }

    /**
     * 验证持久性验证是否有效
     */
    public function isauthAction() {
        $auth = new \Star\Model\MyAuth();
        if ($auth->isAuth()) {
            echo "Already Authentication Success";
        } else {
            echo "Authentication Failure";
        }
        exit;
    }

}
