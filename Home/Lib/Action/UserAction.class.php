<?php
class UserAction extends Action{
	/*
		添加内容
	*/
    public function login()
    {
        $this->display();
    }

    public function checklogin()
    {
        $username = $_POST["username"];
        $password = md5($_POST["password"]);
        $User = M("User");
        $condition['username'] = $username;
        $user_info = $User->where($condition)->find();
        // 数据库操作

        //exit;
        if($user_info) 
        {
            if($password == $user_info['userpassword'] )
            {
                        // 设置登录信息
                session('userid',$user_info['userid']);
                session('username',$user_info['username']);
                session('userpower',$user_info['userpower']);
                session('lastlogintime',$user_info['lastlogintime']);
                        
                // 更新帐号登录信息
                $loginip = get_client_ip();
                $data = array('loginip'=>$loginip,'lastlogintime'=>time(),'logincount'=>$user_info['logincount']+1);
                $condition['userid'] = $user_info['userid'];
                $User->where($condition)->setField($data);
                
                redirect(U('Index/index'));
                //$this->success("登录成功",'../Index/index');
                //$this->assign('jumpUrl',U('Index/index'));
                //$this->display(THINK_PATH.'Tpl/dispatch_jump.tpl'); 
                //echo json_encode(array("msg" => 'succeess登录成功！', "result" => '1'));
            }
            else 
            {
                $msg = '密码错误，请重新输入';

            } // end if password
        } 
        else 
        {
            $msg ='用户名不存在';
        } // end if admin
        $this->assign('message',$msg);// 提示信息
                // 成功操作后默认停留1秒
        $this->assign('waitSecond','1');
                // 登出成功返回登录页面
        $this->assign('jumpUrl',U('User/login'));
        $this->display(THINK_PATH.'Tpl/dispatch_jump.tpl');
    }
    /*
        精弘账号
    */
    public function jhchecklogin()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $url = "http://user.zjut.com/api.php?app=member&action=login&username=".$username."&password=".$password;
        $result = json_decode(file_get_contents($url));
        // 设置登录信息
        /*var_dump($result);
        exit;*/
        if($result->state == "success")
        {
            session('uid',$result->data->uid);
            session('username',$result->data->username);
            session('email',$result->data->email);
            session('avatar',$result->data->avatar);
            redirect(U('Index/index'));
        }
        else
        {
            //错误提示
            $this->error('用户登录失败',U('User/login'));
        }
       
    }
    public function jhlogout()
    {
        if(session('userid')) {    
            session('userid',null);
            session('username',null);
            session('email',null);
            session('avatar',null);
            redirect(U('Index/index'));
        }else {
            $this->error('已经登出！');
        }
    }

    public function logout()
    {
        if(session('userid')) {    
            session('userid',null);
            session('verify',null);
            session('username',null);
            session('userpower',null);
            session('lastlogintime',null);
            redirect(U('Index/index'));
        }else {
            $this->error('已经登出！');
        }
    }
    /*
        注册用户
    */
    public function register()
    {
        if(isset($_POST['username']))
        {
            $data = array();
            $data['username'] = $_POST['username'];
            $data['userpassword'] = md5($_POST['password']);
            $data['userpower'] = 1;
            $user = M('User');
             $result = $user->add($data);
            if($result)
            {
                //成功提示
                redirect(U('Index/index'));
            } else {
                //错误提示
                $this->error('增加用户失败');
            }
        }
        $this->display();
    }
}
