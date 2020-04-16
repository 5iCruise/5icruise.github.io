<?php
header('Content-type: text/json;charset=utf-8');
require 'Meting.php';
use Metowolf\Meting;
$id=$_GET['id'];
$type=$_GET['type'];
//使用网易云 xiami 腾讯//
$api = new Meting($type);
//根据音乐ID解析//
$datas=$api->format(true)->song($id);
$datas = json_decode($datas,true);
$data = $datas[0];
//解析Meting.php取出图片链接//
$cover = json_decode($api->format(true)->pic($data['pic_id']),true)['url'];
//解析Meting.php取出音频链接//
$url = json_decode($api->format(true)->url($data['id']),true)['url'];
$lrc = $api->lyric($data['id']);
$lrc = json_decode($lrc, true);
$lrc_data = $lrc['lyric'];
$lrc_data = preg_replace('/\s/', '', $lrc_data);
//网易云音乐//
if ($type = 'netease'){
//将获取到的链接转换为HTTPS//
$url = str_replace("http://","https://", $url);
}
//QQ音乐//
if ($type = 'tencent'){
//将获取到的链接转换为HTTPS//
$url = str_replace("http://","https://", $url);
$url = str_replace("ws","isure", $url);
}
if($url){
$info = array(
	'code' => '200',
    'name' => $data['name'],
    'mp3url' => $url,
    'pic' => $cover,
    'author' => $data['artist'][0],
    'lrc' =>$lrc_data
);
echo json_encode($info,320);
}else{
$result=array("code"=>"201","msg"=>"获取失败");
echo json_encode($result,320);
}
?>