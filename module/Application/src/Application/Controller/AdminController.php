<?php
namespace Application\Admin;
use Zend\Mvc\Controller\AbstractActionController;

class AdminController extends AbstractActionController {
    
    public function indexAction() {
        echo "admin_index";
    }
    
    public function editAction() {
        echo 'admin_edit';
    }
    
    public function addAction() {
        echo 'admin_add';
    }
    
    
    public function delAction() {
        echo 'admin_del';
    }
    
    public function listAction(){
        echo "admin_list";
    }
}