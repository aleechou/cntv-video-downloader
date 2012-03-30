#! /usr/bin/php5
<?php 

$sFileDir = dirname(__FILE__).'/files' ;
if(is_dir($sFileDir))
{
	mkdir($sFileDir) ;
}

if( empty($argv[1]) )
{
	echo "未输入视频网页url ." ;
	return ;
}

echo "正在打开视频网页：{$argv[1]}\r\n" ;
$sContent = file_get_contents($argv[1]) ;


// 节目清单
if( !preg_match_all('/<a id="vide\\d+" href="([^"]+)"/',$sContent,$arrRes) )
{
	echo "输入的url不是有效的cntv视频网页，节目清单。\r\n" ;
	return ;
}

foreach($arrRes[1] as $sPageUrl)
{
	echo "\r\n\r\n\r\n--------------------------------------------------\r\n正在打开视频网页：{$sPageUrl}\r\n" ;
	$sContent = file_get_contents($sPageUrl) ;

	if( !preg_match('/var multiVariate = "[^\\,]+\\,([^\\,]+),/',$sContent,$arrRes) )
	{
		echo "输入的url不是有效的cntv视频网页，没有找到视频信息。\r\n" ;
		return ;
	}
	$sVideoId = $arrRes[1] ;
	
	$sUrl = "http://vdn.apps.cntv.cn/api/getHttpVideoInfo.do?pid={$sVideoId}&tz=-8&from=000kejiaoidl=32&idlr=32&modifyed=false" ;
	echo "请求视频信息，id：{$sVideoId}/ url：{$sUrl}\r\n" ;
	
	$sContent = file_get_contents($sUrl) ;
	$aInfo = json_decode($sContent) ;
	
	echo "\r\n开始下载视频：{$aInfo->title}\r\n" ;
	
	foreach($aInfo->video->chapters as $nIdx=>$aChapterInfo)
	{
		echo "下载视频片段{$nIdx}：{$aChapterInfo->url}\r\n" ;
		`wget "{$aChapterInfo->url}" -O "files/{$aInfo->title}-{$nIdx}.mp4"` ;
	}
}
