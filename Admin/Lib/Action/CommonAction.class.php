<?php
class CommonAction extends Action {
    protected function _initialize() {
		if(session('userid')){
			
		}else{
			redirect(U('User/login'));
		}
	}	
}
