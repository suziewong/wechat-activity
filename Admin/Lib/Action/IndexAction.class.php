<?php
class IndexAction extends CommonAction {
    // 显示管理后台首页
    public function index()
    {
        $this->display();
    }
    // 显示管理后台头部内容
    public function header()
    {
        $this->display(); 
    }
    
    // 显示管理后台左侧菜单内容
    public function left()
    {
        $this->display();
        
    }
    
    // 显示管理后台欢迎页
    public function home()
    {       
        $this->display();
        
    }
}