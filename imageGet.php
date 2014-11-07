<!doctype html>
<html lang="jp">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link rel="stylesheet" type="text/css" href="css/transitions.css">
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.17.2/build/cssreset/cssreset-min.css">
<title>Google+の画像一気に保存しちゃいます！</title>
<style>

</style>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script src="jquery.collagePlus.min.js"></script>
<script type="text/javascript">
$(window).load(function(){
	$('.Collage').collagePlus({
		'effect' : 'effect-5',
	});
});
</script>
<style>
.Collage{
	margin:50px 0 0 0;
}
header{
	position: fixed;
	top: 0;
	height: 50px;
	z-index: 2;
}
</style>
</head>
<body>
<header>

</header>
<div id="wrapper">
<div class="Collage">
<?php 
$count = 0;
$pageCount = 0;
$imageDirectory = "kamiedaEmika";
//$userId = "101590036846564916771"; //101590036846564916771
$userId = $_POST["userID"]; //101590036846564916771
$url = "https://www.googleapis.com/plus/v1/people/" . $userId . "/activities/public?maxResults=100&key=AIzaSyD0v2NJR22_He4zS9BzwnJQVSpSQNHSn3g";
$emichi = json_decode(file_get_contents($url));
$nextPage = $emichi -> {'nextPageToken'};
//print_r($emichi);
foreach($emichi -> {'items'} as $data){
  $name = mb_convert_encoding($data -> {'actor'} -> {'displayName'}, "UTF-8", auto);
  break;
}
$imageDirectory = $name;
$direct = "./" . $imageDirectory;
mkdir($direct, 0700);

foreach($emichi -> {'items'} as $data){
	$datetime = str_replace(array('T','Z','/',''),array('/','','_','_'),mb_convert_encoding($data -> {'updated'}, "UTF-8", auto));
	if(!empty($data -> {'object'} -> {'attachments'})){
		foreach($data -> {'object'} -> {'attachments'} as $data2){
			if($data2 -> {'image'} -> {'url'} != ""){
              //sleep(0.21);
              $url = mb_convert_encoding($data2 -> {'image'} -> {'url'}, "UTF-8" , auto);
              $fullUrl = mb_convert_encoding($data2 -> {'fullImage'} -> {'url'}, "UTF-8" , auto);
              echo "<a href='" . $fullUrl . "'>";
              echo "<img src='" . $url . "' />";
              echo "</a>";
              $count++;
              $dlUrl = $imageDirectory . "/" . $datetime . ".jpg";
              $yahoo = curl_init();
              curl_setopt($yahoo, CURLOPT_URL, $fullUrl);
              curl_setopt($yahoo, CURLOPT_RETURNTRANSFER, true);
              $data = curl_exec($yahoo);
              file_put_contents($dlUrl, $data);
              curl_close($yahoo);
			}
		}
	}
}

while($nextPage != ""){
    $pageCount++;
	$url = "https://www.googleapis.com/plus/v1/people/" . $userId . "/activities/public?pageToken=" . $nextPage . "&maxResults=100&key=AIzaSyD0v2NJR22_He4zS9BzwnJQVSpSQNHSn3g";
	$emichi = json_decode(file_get_contents($url));
	$nextPage = $emichi -> {'nextPageToken'};
    if($_POST["old"]){
      if($pageCount > 13){
        foreach($emichi -> {'items'} as $data){
            $datetime = str_replace(array('T','Z','/',''),array('/','','_','_'),mb_convert_encoding($data -> {'updated'}, "UTF-8", auto));
            if(!empty($data -> {'object'} -> {'attachments'})){
                foreach($data -> {'object'} -> {'attachments'} as $data2){
                    if($data2 -> {'image'} -> {'url'} != ""){
                      //sleep(0.21);
                      $url = mb_convert_encoding($data2 -> {'image'} -> {'url'}, "UTF-8" , auto);
                      $fullUrl = mb_convert_encoding($data2 -> {'fullImage'} -> {'url'}, "UTF-8" , auto);
                      //echo "<a href='" . $fullUrl . "'>";
                      //echo "<img src='" . $url . "' />";
                      //echo "</a>";
                      $count++;
                      $dlUrl = $imageDirectory . "/" . $datetime . ".jpg";
                      $yahoo = curl_init();
                      curl_setopt($yahoo, CURLOPT_URL, $fullUrl);
                      curl_setopt($yahoo, CURLOPT_RETURNTRANSFER, true);
                      $data = curl_exec($yahoo);
                      file_put_contents($dlUrl, $data);
                      curl_close($yahoo);
                    }
                }
            }
        }
      }
    }else{
      foreach($emichi -> {'items'} as $data){
          $datetime = str_replace(array('T','Z','/',''),array('/','','_','_'),mb_convert_encoding($data -> {'updated'}, "UTF-8", auto));
          if(!empty($data -> {'object'} -> {'attachments'})){
              foreach($data -> {'object'} -> {'attachments'} as $data2){
                  if($data2 -> {'image'} -> {'url'} != ""){
                    //sleep(0.21);
                    $url = mb_convert_encoding($data2 -> {'image'} -> {'url'}, "UTF-8" , auto);
                    $fullUrl = mb_convert_encoding($data2 -> {'fullImage'} -> {'url'}, "UTF-8" , auto);
                    //echo "<a href='" . $fullUrl . "'>";
                    //echo "<img src='" . $url . "' />";
                    //echo "</a>";
                    $count++;
                    $dlUrl = $imageDirectory . "/" . $datetime . ".jpg";
                    $yahoo = curl_init();
                    curl_setopt($yahoo, CURLOPT_URL, $fullUrl);
                    curl_setopt($yahoo, CURLOPT_RETURNTRANSFER, true);
                    $data = curl_exec($yahoo);
                    file_put_contents($dlUrl, $data);
                    curl_close($yahoo);
                  }
              }
          }
      }
    }
}

echo "<p class='cyui'>※API制限防止の為すべての画像は表示していません。なお、保存はすべての画像を行っています。</p>";
echo "<p>総画像枚数：{$count}枚</p>";
?>
</div>
</div>
<footer>
  <p id="copyright">CopyRight(C) 2013-2014 kix All Rights Reserved.</p>
</footer>
</body>
</html>