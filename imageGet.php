<!doctype html>
<html lang="jp">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link rel="stylesheet" type="text/css" href="css/transitions.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.17.2/build/cssreset/cssreset-min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<title>えみちの画像 一気に保存します!メンバーのGoogle+の画像 一気に保存しちゃいます！）</title>
<style>

</style>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="js/jquery.collagePlus.min.js"></script>
<script type="text/javascript">
$(window).load(function(){
	$('.Collage').collagePlus({
		'effect' : 'effect-5',
	});
});
</script>
</head>
<body>
<header>
  <h1><a href="index.php">えみちの画像 一気に保存します!<br><small>（メンバーのGoogle+の画像 一気に保存しちゃいます！）</small></a></h1>
</header>
<div id="wrapper">
<?php 
set_time_limit(0); //タイムアウト防止
echo str_pad('',1); //ブラウザタイムアウト防止
flush();
$count = 0;
$pageCount = 0;
$maxResults = 100;
$userId = $_POST["userID"];
$url = "https://www.googleapis.com/plus/v1/people/" . $userId . "/activities/public?maxResults=100&key=AIzaSyD0v2NJR22_He4zS9BzwnJQVSpSQNHSn3g";
$emichi = json_decode(file_get_contents($url));
$nextPage = $emichi -> {'nextPageToken'};
foreach($emichi -> {'items'} as $data){
  $name = mb_convert_encoding($data -> {'actor'} -> {'displayName'}, "UTF-8", "auto");
  break;
}
/*フォルダ作成*/
$imageDirectory = $name;
$direct = "./images/" . $imageDirectory;
if(!file_exists($direct)){
  mkdir($direct, 0700);
}
?>
<div id="name" class="clearfix"><p id="member"><?php echo $name; ?>の画像を保存！</p><p id="nameChyui">全件を取得できない場合があります。その際は数回試してみるか、古い写真を取得するオプションなどを選択してください。<br>保存は全件していますが、画面上では全ての画像を表示していません。</p></div>
<div class="Collage">
<?php
/*1回目呼び出し（最新画像100件･表示あり）*/
echo gplusSave($emichi,1,$imageDirectory);

while($nextPage != ""){
  $pageCount ++;
  if(!empty($nextPage)){
    $url = "https://www.googleapis.com/plus/v1/people/" . $userId . "/activities/public?pageToken=" . $nextPage . "&maxResults=" . $maxResults . "&key=AIzaSyD0v2NJR22_He4zS9BzwnJQVSpSQNHSn3g";
  }
  $emichi = json_decode(file_get_contents($url));
  if(!empty($emichi -> {'nextPageToken'})){
    $nextPage = $emichi -> {'nextPageToken'};
  }
  if($_POST["type"] == 3){ //もっと古い画像を保存
    if($pageCount > 26){
      echo gplusSave($emichi,0,$imageDirectory);
    }
  }elseif($_POST["type"] == 2){ //古い画像を保存
    if($pageCount > 13){
      echo gplusSave($emichi,0,$imageDirectory);
    }
  }else{ //通常保存
    echo gplusSave($emichi,0,$imageDirectory);
  }
}

function gplusSave($apiData,$typeFlag,$imageDirectory){
  $returnText = "";
  foreach($apiData -> {'items'} as $data){
    $count = 0;
    $datetime = str_replace(array('T','Z','/',':',"."),array('/','','_','-',"-"),mb_convert_encoding($data -> {'updated'}, "UTF-8", "auto"));
    if(!empty($data -> {'object'} -> {'attachments'})){
      foreach($data -> {'object'} -> {'attachments'} as $data2){
        if(!empty($data2 -> {'image'} -> {'url'})){
          if($data2 -> {'image'} -> {'url'} != ""){
            $count++;
            $dlUrl = "images/" . $imageDirectory . "/" . $datetime . "_" . $count . ".jpg";
            if(!file_exists($dlUrl)){
              if(!empty($data2 -> {'fullImage'} -> {'url'})){
                $fullUrl = mb_convert_encoding($data2 -> {'fullImage'} -> {'url'}, "UTF-8" , "auto");
              }
              if($typeFlag == 1){
                $url = mb_convert_encoding($data2 -> {'image'} -> {'url'}, "UTF-8" , "auto");
                $returnText .= "<a href='" . $fullUrl . "'><img src='" . $url . "'></a>";
              }else{
                
              }
              //$returnText .= $dlUrl . "の画像を保存しました<br>";
              if(mb_convert_encoding($data2 -> {'objectType'}, "UTF-8" , "auto") == "photo"){
                $imgData = curl_init();
                curl_setopt($imgData, CURLOPT_URL, $fullUrl);
                curl_setopt($imgData, CURLOPT_RETURNTRANSFER, true);
                $data = curl_exec($imgData);
                file_put_contents($dlUrl, $data);
                curl_close($imgData);
              }
            }
          }
        }
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