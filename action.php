<?php
header('content-type:text/html;charset=utf8');
/**
 * 实现当中，加上一些验证功能,如是否登陆之类的
 */
 if(!isset($_FILES['mypic']['name']) && $_FILES['mypic']['name'] != '')
 {
	echo '非法访问';
	exit;
 }
 //PHP上传失败
 /*
if (!empty($_FILES['mypic']['error']) && $_FILES['mypic']['error'] > 0) {
	switch($_FILES['mypic']['error']){
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
	echo $error;
	exit;
}
*/
$picname = $_FILES['mypic']['name'];
$picsize = $_FILES['mypic']['size'];
$typeArr = array('.gif','.png','.jpg','.jpeg','.bmp');
if ($picname != "") {
	if ($picsize > 2048000) {
		echo '图片大小不能超过2M';
		exit;
	}
	$type = strstr($picname, '.');
	if (!in_array($type,$typeArr)) {
		echo '图片只能是jpg/jpeg/png/gif格式的';
		exit;
	}
	$rand = microtime() . '_' . rand(1, 99999999);
	$pics = md5($rand) . $type;
	//上传路径
	$pic_path = dirname(__FILE__) . "/files/". $pics;
	if(!move_uploaded_file($_FILES['mypic']['tmp_name'], $pic_path))
	{
		echo '图片上传失败';
		exit;
	}
}
$size = round($picsize/1024,2);
$arr = array(
	'name'=>$picname,
	'size'=>$size
);
//移动到指定的文件服务器
$ch = curl_init();
//加@符号curl就会把它当成是文件上传处理,可以传递多个参数,如token,产品参数
$data = array('img'=>'@'. $pic_path, 'token'=>'token值，在文件服务器作验证处理');
curl_setopt($ch,CURLOPT_URL,"http://www.linuxvm.org/up.php");
curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
curl_setopt($ch,CURLOPT_POST,true);
curl_setopt($ch,CURLOPT_POSTFIELDS,$data);
$result = curl_exec($ch);
curl_close($ch);
$result = json_decode($result, true);
if($result['error'])
{	
	unlink($pic_path);
	echo '上传文件失败';
	exit;
}
$arr['pic'] = $result['url'];
//删除本服务器图片文件
//unlink($pic_path);
echo json_encode($arr);
?>