<?php
class SettingAction extends CommonAction {
	protected $item;
	protected function _initialize() {
		parent::_initialize();
		$this->item = isset($_REQUEST['item']) ? $_REQUEST['item'] : '0';
		$this->assign("item",$this->item);
	}

    // 显示网站设置的基本配置页面
    public function basic(){
		$this->assign("jumpUrl","__SELF__");

		if(isset($_POST['feel'])){

			if ($this->_update($_POST['feel'])) {
			//成功提示
				$this->success('基本配置修改成功');
			} else {
					//错误提示
					$this->error('基本配置修改失败');
			}
			
		} else {
			$model = M("Setting");
			//$setting = $model->where("item='".$this->item."'")->getField('item_key,item_value');
			$setting = $model->getField('item_key,item_value');
			$this->assign("feel",$setting);
			$this->display();
		}
    }
    // 全局设置
    public function all()
    {

    	if(isset($_POST['all'])) {
    		$v=$_POST['all']['theme'];
    		$tmp = file_get_contents(CONF_PATH.'config.php');
			$tmp = preg_replace("/'HOME_DEFAULT_THEME'\s*\=\>\s*'.*?'\,/is", "'HOME_DEFAULT_THEME' => '".$v."',", $tmp);
			if(file_put_contents(CONF_PATH.'config.php',$tmp))
			{
				@unlink(RUNTIME_PATH.'~runtime.php');
				// 更新数据
				//if ($this->_update($_POST['all'])) {
					//成功提示
					$this->success('全局设置修改成功');
				//} else {
					//错误提示
				//	$this->error('全局设置修改失败');
				//}
			} else $this->error('全局设置修改失败');
    	}
    	else
    	{
    		$all =array();
    		$all['theme'] = C('HOME_DEFAULT_THEME');
    		$this->assign("all",$all);
    		$this->display();
    	}
    }

	protected function _update($settingarr, $item = '') {
		if($item == '') $item = $this->item;
		$setting = M('Setting');
		$setting->where("item='".$item."'")->delete(); 
		$data = array();
		foreach($settingarr as $k => $v) {
			if(is_array($v)) $v = implode(',', $v);
			$data['item_key'] = $k;
			$data['item_value'] = $v;
			$result = $setting->add($data);
			if(false === $result) return false;
		}
		return true;
	}
	public function clear()
	{
	//	$_POST['cache']=2;
		if(session('userpower') == 0)
		{
			if($_POST['cache'] == 2)
			{
				$dir = $_SERVER['DOCUMENT_ROOT'].__ROOT__."/Admin/Runtime/";
				if(is_dir($dir))
				{
					$info = $this->deldir($dir);
				}
				$msg = '后台';
			}
			if($_POST['cache'] == 1)
			{
				$dir = $_SERVER['DOCUMENT_ROOT'].__ROOT__."/Home/Runtime/";
				if(is_dir($dir))
				{
					$info = $this->deldir($dir);
				}
				$msg = '前台';
			}
			if($info == 1)
			{
				$msg='清除'.$msg.'缓存成功！';
				echo "{";
				echo "\"msg\":\"".$msg."\"";
				echo "}";
			}
			else
			{
				$msg='清除失败！';
				echo "{";
				echo "\"msg\":\"".$msg."\"";
				echo "}";
			}
		}
		else
		{
			$msg='你木有权限,修改失败！';
			echo "{";
			echo "\"msg\":\"".$msg."\"";
			echo "}";
		}
	}

	protected function deldir($dir) {
	  //先删除目录下的文件：
		
	  $dh=opendir($dir);
	  while ($file=readdir($dh)) {
	    if($file!="." && $file!="..") {
	      $fullpath=$dir."/".$file;
	      if(!is_dir($fullpath)) {
	          unlink($fullpath);
	      } else {
	          $this->deldir($fullpath);
	      }
	    }
	  }
	 
	  closedir($dh);
	  //删除当前文件夹：
	  if(rmdir($dir)) {
	    return true;
	  } else {
	    return false;
	  }
	}
	
}
