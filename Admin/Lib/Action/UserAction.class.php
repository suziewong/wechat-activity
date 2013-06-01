<?php
class UserAction extends Action {
	public function login()
    {
        if(session('userid')){
            redirect(U('Index/index'));
        }else{
            $this->display();
        }
        
    }
    public function checklogin()
    {  
        $username = $_POST["username"];
        $password = md5($_POST["password"]);
        $verify = $_POST["verify"];
        
        if(session('verify') != md5($_POST['verify'])) {
            $msg = '验证码错误';
            $this->assign('message',$msg);// 提示信息
                    // 成功操作后默认停留1秒
            $this->assign('waitSecond','1');
                    // 登出成功返回登录页面
            $this->assign('jumpUrl',U('User/login'));
            $this->display(THINK_PATH.'Tpl/dispatch_jump.tpl');
            exit;
        }
        $User = M("User");
        $condition['username'] = $username;
        $user_info = $User->where($condition)->find();
        // 数据库操作
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
    
    public function logout()
    {
        if(session('userid')) {    
            session('userid',null);
            session('verify',null);
            session('username',null);
            session('userpower',null);
            session('lastlogintime',null);
            //$this->assign("jumpUrl",U('Public/login'));
            //$this->success('登出成功！');
            $this->assign('message','登出成功！');// 提示信息
            // 成功操作后默认停留1秒
            $this->assign('waitSecond','1');
            // 登出成功返回登录页面
            $this->assign('jumpUrl',U('User/login'));
            $this->display(THINK_PATH.'Tpl/dispatch_jump.tpl');
        }else {
            $this->error('已经登出！');
        }
    }

    public function add()
    {
        if(isset($_POST["user_type"]))
        {
            $data = array();

            if(empty($_POST['username']))
            {
                $this->error("用户名为空");
            }
            if(empty($_POST['password']))
            {
                $this->error("密码为空");
            }
            if(empty($_POST['password2']))
            {
                $this->error("密码为空");
            }
            if($_POST['password'] != $_POST['password2'])
            {
                $this->error("2次密码不一致");
            }
            $data['username'] = $_POST['username'];
            $data['userpassword'] = md5($_POST['password']);
            $data['userpower'] = $_POST['user_type'];
            $user = M('User');

            if(isset($_POST['userid'])) {
                //编辑数据
                $condition['id'] = $_POST['userid'];
                $result = $user->where($condition)->save($data);
                if ( $result ) {
                    //成功提示
                    $this->success('编辑用户成功');
                } else {
                    //错误提示
                    $this->error('编辑用户失败');
                }
            } else {
                // 添加数据
                //$user->create($data);
                //$user->data($data)->add();
                $result = $user->add($data);
                if ( $result ) {
                    //成功提示
                    $this->success('增加用户成功',U('User/manage'));
                } else {
                    //错误提示
                    $this->error('增加用户失败');
                }
            }
        }
        else
        {
            $this->display();
        }
    }
    public function manage()
    {
        if(isset($_POST['duoxuanHidden'])) {
            $id = $_POST['duoxuanHidden'];
            
            $model = M("User");
            $map['userid'] = array('in',$id);
            $result = $model->where($map)->delete();
        }
        $userList = array();
        $user = M("User");
        $page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

        $user= $user->order('userid asc')->select();
        while (list($key, $val) = each($user)) {
            array_push($userList,$val);
        }
        
        import("ORG.Util.Page");// 导入分页类
        $count = count($userList);// 查询满足要求的总记录数
        $length = 10;
        $offset = $length * ($page - 1);
        $Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
        $Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
        $show = $Page->show();// 分页显示输出
        $this->assign("userList",$userList);
        $this->assign("offset",$offset);
        $this->assign("length",$length);
        $this->assign("page",$show);
        $this->display();
    }
    public function edit()
    {
        if(isset($_POST["user_type"]))
        {
            $data['username'] = $_POST['username'];
            if(!empty($_POST['password']))
            {
                $data['userpassword'] = md5($_POST['password']);
            }
            $data['userpower'] = $_POST['user_type'];
            //var_dump($data);
            //exit;
            $user = M('User');
            if(isset($_POST['userid']))
            {
                //编辑数据
                $condition['userid'] = $_POST['userid'];
                $result = $user->where($condition)->save($data);
                if ($result) 
                {
                    //成功提示
                    $this->success('编辑用户成功',U('User/manage'));
                }
                else
                {
                    //错误提示
                    $this->error('编辑用户失败',U('User/manage'));
                }
            } 
        }
        else
        {
            $userid = $_GET['id'];
            $model = M("User");
            $condition['userid'] = $_GET['id'];
            $user = $model->where($condition)->find();
            $this->assign("user",$user);
            $this->display();
        }
    }
    public function del()
    {
        $this->assign("jumpUrl",U('User/manage'));
        $id = $_GET['id'];
        $condition['userid'] = $id;
        $User = M('User');
        $djid =  $User->where($condition)->find();
        
        if($djid['djid'] > 0)
        {
            $condition['id'] = $djid['djid'];
            $DJ = M('Dj');
            $dj = $DJ->where($condition)->find();
            $this->del_file($dj['faceaddress']);
            $this->del_file($dj['headaddress']);
            $Life = M('Life');
            $condition2['djid'] = $djid['djid'];
            $life = $Life->where($condition2)->select();
            $lifelength = count($life);
            for($i=0;$i<$lifelength;$i++)
            {
               $this->del_file($life[$i]['lifeaddress']);
            }
            $result = $Life->where($condition2)->delete();  
            $result = $DJ->where($condition)->delete();
        }
        $result = $User->where($condition)->delete();
        if ($result) {
            //成功提示
            $this->success('用户删除成功');
        } else {
            //错误提示
            $this->error('用户删除失败');
        }
    } 
    public function verify()
    {
        import('ORG.Util.Image');
        Image::buildImageVerify();
    }

    public function change_password()
    {
        //var_dump($_SESSION);
        $oldpasswd = md5($_POST["oldpasswd"]);
        $newpasswd = md5($_POST["newpasswd"]);
        $condition['userid'] = $_SESSION['userid'];
        $User = M('User');
        $result = $User->where($condition)->find();
       // var_dump($result);
        if($result['userpassword'] != $oldpasswd)
        {
            $msg="旧密码错误！";
            echo "{";
            echo "\"msg\":\"".$msg."\"";
            echo "}";
        }
        else
        {
            $data =array();
            $data['userpassword'] = $newpasswd;
            $result = $User->where($condition)->save($data);
            if ($result) {
                //成功提示
                $msg='修改成功! ';
                echo "{";
                echo "\"msg\":\"".$msg."\"";
                echo "}";

            } else {
                //错误提示
                $msg='修改失败！';
                echo "{";
                echo "\"msg\":\"".$msg."\"";
                echo "}";
            }

        }
    }
    //删除旧文件
    public function del_file($filename)
    {
         ///删除文件使用绝对路径

        $filename = $_SERVER['DOCUMENT_ROOT'].__ROOT__.$filename;
//      echo $filename;
//      exit;
        if(is_file( $filename ))
        {
            if( unlink($filename) )
            {
                //echo '文件删除成功';
            }
            else
            {
                //echo '文件删除失败，权限不够';
            }
        }
        else
        {
                //  echo '不是有一个有效的文件';
        }
    }        

}
