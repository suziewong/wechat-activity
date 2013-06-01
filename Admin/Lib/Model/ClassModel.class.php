<?php

class ClassModel extends Model{
  protected $_validate = array(
    array('classname','require','栏目必须！'), //默认情况下用正则进行验证
  );
}