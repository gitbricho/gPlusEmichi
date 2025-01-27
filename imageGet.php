<?php
error_reporting(0);
$osFlag = "";
if (PHP_OS == "WIN32" || PHP_OS == "WINNT") {
  $osFlag = "SJIS-win";
} else {
  $osFlag = "UTF-8";
}
?>
<!doctype html>
<html lang="jp">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link rel="stylesheet" type="text/css" href="css/transitions.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.17.2/build/cssreset/cssreset-min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<title>えみちの画像 一気に保存します!（メンバーのGoogle+の画像 一気に保存しちゃいます！）</title>
<style>

</style>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="js/jquery.collagePlus.min.js"></script>
<script type="text/javascript">
/*
$(window).load(function(){
	$('.Collage').collagePlus({
		'effect' : 'effect-5',
	});
});
*/
</script>
<!--
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41993548-8', 'auto');
  ga('send', 'pageview');

</script>
-->
</head>
<body>
<header>
  <h1><a href="index.php">えみちの画像 一気に保存します!<br><small>（メンバーのGoogle+の画像 一気に保存しちゃいます！）</small></a></h1>
</header>
<div id="wrapper" class="Collage">
<?php 
set_time_limit(0); //タイムアウト防止
echo str_pad('',1); //ブラウザタイムアウト防止
flush();
$count = 0;
$pageCount = 0;
$maxResults = 100;
$apiKey = "AIzaSyCn-Rz2fEPWXZzZZk4Q2xkBC_2MFrhkIoE";
$userId = $_POST["userID"];
$url = "https://www.googleapis.com/plus/v1/people/" . $userId . "/activities/public?maxResults=100&key=" . $apiKey;
$emichi = json_decode(file_get_contents($url));
$nextPage = $emichi -> {'nextPageToken'};
foreach($emichi -> {'items'} as $data){
  $imageDirectory = mb_convert_encoding($data -> {'actor'} -> {'displayName'}, $osFlag, "ASCII,JIS,UTF-8,EUC-JP,SJIS");
  break;
}
/*フォルダ作成*/
$direct = "./images/" . $imageDirectory;
if(!file_exists($direct)){
  mkdir($direct, 0700);
}
?>
<div id="name" class="clearfix"><p id="member"><?php echo mb_convert_encoding($imageDirectory, "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS"); ?>の画像を保存！</p><p id="nameChyui">全件を取得できない場合があります。その際は数回試してみるか、古い写真を取得するオプションなどを選択してください。<br>保存は全件していますが、画面上では全ての画像を表示していません。</p></div>
<div class="Collage">
<?php
/*1回目呼び出し（最新画像100件･表示あり）*/
echo gplusSave($emichi,1,$imageDirectory);

//picasa画像取得
if($_POST["destination"] == 1){
  echo picasaSave($userId,$imageDirectory);
}

while($nextPage != ""){
  $pageCount ++;
  if(!empty($nextPage)){
    $url = "https://www.googleapis.com/plus/v1/people/" . $userId . "/activities/public?pageToken=" . $nextPage . "&maxResults=" . $maxResults . "&key=" . $apiKey;
  }
  $emichi = json_decode(file_get_contents($url));
  if(!empty($emichi -> {'nextPageToken'})){
    $nextPage = $emichi -> {'nextPageToken'};
  }
  if($_POST["type"] == 3){ //もっと古い画像を保存
    if($pageCount > 26){
      gplusSave($emichi,0,$imageDirectory);
    }
  }elseif($_POST["type"] == 2){ //古い画像を保存
    if($pageCount > 13){
      gplusSave($emichi,0,$imageDirectory);
    }
  }else{ //通常保存
    gplusSave($emichi,0,$imageDirectory);
  }
}

function gplusSave($apiData,$typeFlag,$imageDirectory){
  $osFlag = "";
  if (PHP_OS == "WIN32" || PHP_OS == "WINNT") {
    $osFlag = "SJIS";
  } else {
    $osFlag = "UTF-8";
  }
  $returnText = "";
  foreach($apiData -> {'items'} as $data){
    $datetime = str_replace(array('T','Z','/',':',"."),array('/','','_','-',"-"),mb_convert_encoding($data -> {'updated'}, $osFlag, "ASCII,JIS,UTF-8,EUC-JP,SJIS"));
    if(!empty($data -> {'object'} -> {'attachments'})){
      foreach($data -> {'object'} -> {'attachments'} as $data2){
        if(!empty($data2 -> {'image'} -> {'url'})){
          if($data2 -> {'image'} -> {'url'} != ""){
            $dlUrl = "images" . DIRECTORY_SEPARATOR . $imageDirectory . DIRECTORY_SEPARATOR . $datetime . ".jpg";
            if(!file_exists($dlUrl)){
              if(!empty($data2 -> {'fullImage'} -> {'url'})){
                $fullUrl = mb_convert_encoding($data2 -> {'fullImage'} -> {'url'}, $osFlag , "ASCII,JIS,UTF-8,EUC-JP,SJIS");
              }
              if($typeFlag == 1){
                $url = mb_convert_encoding($data2 -> {'image'} -> {'url'}, $osFlag, "ASCII,JIS,UTF-8,EUC-JP,SJIS");
                $returnText .= "<a href='" . $fullUrl . "'><img src='" . $url . "' target='_blank' width=200></a>";
              }else{
                
              }
              //$returnText .= $dlUrl . "の画像を保存しました<br>";
              if(mb_convert_encoding($data2 -> {'objectType'}, $osFlag, "ASCII,JIS,UTF-8,EUC-JP,SJIS") == "photo"){
                /*
                $imgData = curl_init();
                curl_setopt($imgData, CURLOPT_URL, $fullUrl);
                curl_setopt($imgData, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($imgData);
                file_put_contents($dlUrl, $data);
                curl_close($imgData);
                */
                $dataImg = file_get_contents($fullUrl);
                file_put_contents($dlUrl, $dataImg);
              }
            }
          }
        }
      }
    }
  }
  return $returnText;
}
function picasaSave($userId,$imageDirectory){
  $osFlag = "";
  if (PHP_OS == "WIN32" || PHP_OS == "WINNT") {
    $osFlag = "SJIS";
  } else {
    $osFlag = "UTF-8";
  }
  $picasaUrl = 'https://picasaweb.google.com/data/feed/api/user/' . $userId;
  $picasaXml = file_get_contents($picasaUrl);
  $picasaXml = simplexml_load_string($picasaXml);
  foreach($picasaXml as $picasaData){
    $nameSpaces = $picasaData -> getNamespaces(true);
    if($picasaData -> children($nameSpaces['gphoto']) != ""){
      $gNode = $picasaData -> children($nameSpaces['gphoto']);
    }
    if($gNode -> id != ""){
      $picasaUrl2 = "https://picasaweb.google.com/data/feed/api/user/" . $userId . "/albumid/" . $gNode -> id;
      $picasaXml2 = file_get_contents($picasaUrl2);
      $picasaXml2 = simplexml_load_string($picasaXml2);
      $saveDate = (string) $picasaXml2 -> updated;
      $datetime = str_replace(array('T','Z','/',':',"."),array('/','','_','-',"-"), $saveDate );
      $saveCount = 1;
      //print "<strong>" . $datetime . "</strong><br>"; //画像更新日時
      foreach($picasaXml2 -> entry as $picasaData2){
        $imgUrl = Array();
        $imgUrl = explode("/",$picasaData2 -> content -> attributes() -> src);
        $imgUrlAB = "";
        $countArray = 0;
        foreach($imgUrl as $imgUrlAA){
          $imgUrlAB .= $imgUrlAA . "/";
          $countArray ++;
          if($countArray == 7){
            $imgUrlAB .= "s0/";
          }
        }
        $imgSaveUrl = substr($imgUrlAB, 0, -1);
        //print $imgSaveUrl;
        $returnText .= "<a href='" . $imgSaveUrl . "' target='_blank'><img src='" . $imgSaveUrl . "' width=200></a>";
        $dlUrl = "images" . DIRECTORY_SEPARATOR . $imageDirectory . DIRECTORY_SEPARATOR . $datetime . "_" . $saveCount . ".jpg";
        if(!file_exists($dlUrl)){
          /*
          $imgData = curl_init();
          curl_setopt($imgData, CURLOPT_URL, $imgSaveUrl);
          curl_setopt($imgData, CURLOPT_RETURNTRANSFER, true);
          $data = curl_exec($imgData);
          file_put_contents($dlUrl, $data);
          curl_close($imgData);
          */
          $dataImg = file_get_contents($imgSaveUrl);
          file_put_contents($dlUrl, $dataImg);
        }
        $saveCount ++;
      }
    }
  }
  return $returnText;
}
?>
</div>
</div>
<footer>
  <p id="copyright">CopyRight(C) 2014 kix All Rights Reserved.</p>
</footer>
</body>
</html>