<?php
class ContentAction extends CommonAction{
	/*
		添加内容
	*/
	public function add()
	{
		if(isset($_POST["title"]))
		{

			$data = array();
			$data["title"]	= $_POST["title"];
			$data["userid"]  = $_POST["userid"];

            $data["anonymous"]  = empty($_POST["anonymous"])?0:1;
            
			$data["link"]	= $_POST["link"];
            $data["time"]   = date("Y-m-d H:i:s");
			$Content = M('Content');
            $result = $Content->add($data);
            if ( $result ){
                //成功提示
                $this->success('增加内容成功',U('Content/manage'));
            }
            else{
                //错误提示
                $this->error('增加内容失败');
            }
	   	}
		else
		{
            $User = M("User");
            $User = $User->field('userid,username')->select();
            $this->assign("UserList",$User);
			$this->display();
		}
	}
	public function manage()
	{

		if(isset($_POST['duoxuanHidden'])) {
			$id = $_POST['duoxuanHidden'];
			
			$model = M("Content");
			$map['id'] = array('in',$id);
			$mp3s = $model->where($map)->select();
			$mp3length = count($mp3s);
			for($i=0;$i<$mp3length;$i++)
			{
				$this->del_file($mp3s[$i]['mp3address']);
			}
			$result = $model->where($map)->delete();
		}
		$ContentList = array();
		
        //$Content = M("Content");
		$page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

		//$Content= $Content->order('contentid asc')->select();
        $Model = new Model();
        $Content = $Model->query("select id,username,title,link,time from dc_content,dc_user where dc_user.userid = dc_content.userid");
		while (list($key, $val) = each($Content)) {
		    array_push($ContentList,$val);
		}
		import("ORG.Util.Page");// 导入分页类
		$count = count($ContentList);// 查询满足要求的总记录数
		$length = 10;
		$offset = $length * ($page - 1);
		$Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
		$Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
		$show = $Page->show();// 分页显示输出
		$this->assign("ContentList",$ContentList);
		$this->assign("offset",$offset);
		$this->assign("length",$length);
		$this->assign("page",$show);
		$this->display();

	}
	public function edit()
    {

        if(isset($_POST["contentid"]))
        {
            $data = array();
            $data["title"]  = $_POST["title"];
            $data["userid"]  = $_POST["userid"];
            $data["anonymous"]  = empty($_POST["anonymous"])?0:1;
            $data["link"]   = $_POST["link"];
            $data["time"]   = date("Y-m-d H:i:s");
            

            $Content = M('Content');
            if(isset($_POST['id']))
            {
                $condition['id'] = $_POST['id'];
	
                //编辑数据
                $result = $Content->where($condition)->save($data);
                if ($result)
                {
                    //成功提示
                    $this->success('编辑内容成功',U('Content/manage'));
                }
                else
                {
                    //错误提示
                    $this->error('编辑内容失败',U('Content/manage'));
                }
            } 
        }
        else
        {
            $contentid = $_GET['id'];           
            $Content = M("Content");
            $condition['id'] = $_GET['id'];
            $Content = $Content->where($condition)->find();
            $this->assign("Content",$Content);

            $Class = M("Class");
            $Class = $Class->field('classid,classname')->select();
            $this->assign("ClassList",$Class);
            $this->display();
        }
    }
    public function del()
    {
        $this->assign("jumpUrl",U('Content/manage'));
        $contentid = $_GET['id'];
        //echo $contentid;
        //exit;
        $condition['id'] = $contentid;
        $Content = M('Content');
        
	    $result = $Content->where($condition)->delete();
        if ($result) {
            //成功提示
            $this->success('删除成功');
        } else {
            //错误提示
            $this->error('删除失败');
        }
    }

}
