<?php
    class CommentAction extends CommonAction
    {   
        /*评论模块*/
        public function manage()
        {
		    if(isset($_POST['duoxuanHidden'])) {
			    $id = $_POST['duoxuanHidden'];
			
			    $model = M("Comment");
			    $map['id'] = array('in',$id);
			    $result = $model->where($map)->delete();
		    }
    	    $CommentList = array();
    	    $page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

            $sql="select feel_comment.id,feel_dj.id as djid,feel_dj.name,uid,posttime,comment from feel_comment,feel_dj where feel_dj.id = feel_comment.djid";
            if(session('djid') > 0)
            {
                $sql .=" and feel_dj.id=";
                $sql .=session('djid');
            }
            else
            {
                
            }
            $Model = new Model();
            $Comment = $Model->query($sql);
    	while (list($key, $val) = each($Comment)) {
    	    array_push($CommentList,$val);
    	}	
    	import("ORG.Util.Page");// 导入分页类
    	$count = count($CommentList);// 查询满足要求的总记录数
    	$length = 10;
    	$offset = $length * ($page - 1);
    	$Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
    	$Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
    	$show = $Page->show();// 分页显示输出
    	$this->assign("CommentList",$CommentList);
    	$this->assign("offset",$offset);
    	$this->assign("length",$length);
    	$this->assign("page",$show);
            $this->display();
        }
        /*添加评论*/
        public function add()
        {
            if(isset($_POST['djid']))
            {
                $data = array();
                $data['uid']  = $_POST['uid'];
                $data['djid'] = $_POST['djid'];
                $data['comment'] = $_POST['comment'];
                $data['posttime']=time();
                $Comment = M('Comment');
                $result = $Comment->add($data);
            
                if($result)
                {
                    echo "{'result':'success'}";
                }
                else
                {
                    echo "{'result':'fail'}"; 
                }
            }
            else
            { 
			    $this->display();
            }
        }
        public function del()
        {
            $this->assign("jumpUrl",U('Comment/manage'));
            $commentid = $_GET['id'];
    
           
            $condition['id'] = $commentid;
            $Comment = M('Comment');
            $result = $Comment->where($condition)->delete();
            if ($result) {
                //成功提示
                $this->success('评论删除成功');
            } else {
                //错误提示
                $this->error('评论删除失败');
            }
         }
        
        public function suggest()
        {
    	    $SuggestList = array();
           	$Suggest = M("Suggest");
    	    $page = isset($_GET['p'])? $_GET['p'] : '1';  //默认显示首页数据

           	$Suggest = $Suggest->order('id asc')->select();
    	    while (list($key, $val) = each($Suggest)) {
    	        array_push($SuggestList,$val);
    	    }	
    	    import("ORG.Util.Page");// 导入分页类
    	    $count = count($SuggestList);// 查询满足要求的总记录数
        	$length = 10;
        	$offset = $length * ($page - 1);
    	    $Page = new Page($count,$length,$page);// 实例化分页类 传入总记录数和每页显示的记录数和当前页数
    	    $Page->setConfig('theme',' %upPage%   %linkPage%  %downPage%');
    	    $show = $Page->show();// 分页显示输出
    	    $this->assign("SuggestList",$SuggestList);
    	    $this->assign("offset",$offset);
        	$this->assign("length",$length);
        	$this->assign("page",$show);
            $this->display();
        }

        
        /*添加建议*/
        public function suggestadd()
        {
            if(isset($_POST['suggest']))
            {
                $data = array();
                $data['uid']  = $_POST['uid'];
                $data['suggest'] = $_POST['suggest'];
                $data['posttime']=time();
                $Suggest = M('Suggest');
                $result = $Suggest->add($data);
            
                if($result)
                {
                    echo "{'result':'success'}";
                }
                else
                {
                    echo "{'result':'fail'}"; 
                }
            }
            else
            { 
			    $this->display();
            }
     }
     public function suggestdel()
     {
            $this->assign("jumpUrl",U('Comment/suggest'));
            $commentid = $_GET['id'];
    
           
            $condition['id'] = $commentid;
            $Suggest = M('Suggest');
            $result = $Suggest->where($condition)->delete();
            if ($result) {
                //成功提示
                $this->success('建议删除成功');
            } else {
                //错误提示
                $this->error('建议删除失败');
            }
     }
}
