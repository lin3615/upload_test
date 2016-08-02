<?php
/**
 * 获取token值，作验证用
 * $token  = $_POST['token'];
 * 可以多个参数，如与产品关联的参数
 */
if(!isset($_FILES['img']['name']) || $_FILES['img']['name'] == '')
{
	$arr = array('error'=>1, 'message'=>'非法请求');
	die(json_encode($arr));
}

if (!empty($_FILES['img']['error']) && $_FILES['img']['error'] > 0) {
	switch($_FILES['img']['error']){
		case '1':
			$error = '超过php.ini允许的大小。';
			break;
		case '2':
			$error = '超过表单允许的大小。';
			break;
		case '3':
			$error = '图片只有部分被上传。';
			break;
		case '4':
			$error = '请选择图片。';
			break;
		case '6':
			$error = '找不到临时目录。';
			break;
		case '7':
			$error = '写文件到硬盘出错。';
			break;
		case '8':
			$error = 'File upload stopped by extension。';
			break;
		case '999':
		default:
			$error = '未知错误。';
	}
	$arr = array();
	$arr = array('error'=>1, 'message'=>$error);
	die(json_encode($arr));
}

 $filename = $_FILES['img']['name'];
 $tmpname = $_FILES['img']['tmp_name'];
 if(move_uploaded_file($tmpname,dirname(__FILE__).'/upload/'.$filename)){
	$url = 'http://www.linuxvm.org/upload/'.$filename;
	/*
	 这里可以对图片存放处理
	 */
	$arr = array('error'=>0,'url'=>$url);
	die(json_encode($arr));
	exit;
 }else{
	$arr = array('error'=>1,'message'=>'文件上传失败');
	die(json_encode($arr));
 }
?>