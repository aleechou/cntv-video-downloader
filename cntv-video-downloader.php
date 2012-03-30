#! /usr/bin/php5
<?php 


if( empty($argv[1]) )
{
	echo "未输入视频网页url ." ;
	return ;
}

echo "正在打开视频网页：{$argv[1]}\r\n" ;
$sContent = file_get_contents($argv[1]) ;
if( !preg_match('/var multiVariate = "[^\\,]+\\,([^\\,]+),/',$sContent,$arrRes) )
{
	echo "输入的url不是有效的cntv视频网页，没有找到视频信息。\r\n" ;
	return ;
}
$sVideoId = $arrRes[1] ;

$sUrl = "http://vdn.apps.cntv.cn/api/getHttpVideoInfo.do?pid={$sVideoId}&tz=-8&from=000kejiaoidl=32&idlr=32&modifyed=false" ;
echo "请求视频信息，id：{$sVideoId}/ url：{$sUrl}\r\n" ;

$sContent = file_get_contents($sUrl) ;
print_r(json_decode($sContent)) ;
//var multiVariate = "CN26,982c572c29d44dba16bf2b8da2795c27,20111116101821,,,,,,,科教台社会,C33295,CCTV-10,0-节目,粗切,,百家讲坛,CN26";

