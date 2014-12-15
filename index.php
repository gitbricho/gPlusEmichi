
<?php
$version = "1.7.4";
?>
<!doctype html>
<html lang="jp">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link rel="stylesheet" type="text/css" href="css/transitions.css">
<link href='http://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.17.2/build/cssreset/cssreset-min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<title>えみちの画像 一気に保存します!（メンバーのGoogle+の画像 一気に保存しちゃいます！）</title>
<style>

</style>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript">
  $(function(){
    var height = $(window).height();
    var header = $("header").height();
    $("#cover").css("height",(height - header) + "px");
  });
</script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41993548-8', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body>
<div id="version">
  <p>バージョン<?php echo $version; ?></p>
</div>
<header>
  <h1><a href="index.php">えみちの画像 一気に保存します!<br><small>（メンバーのGoogle+の画像 一気に保存しちゃいます！）</small></a></h1>
</header>
<div id="wrapper">
  <div id="cover" class="clearfix">
    <div id="mainLeft">
    <p id="title">Who do you save?</p>
    <h2>Google+のユーザIDを入力してください。画像を保存します（数回やると完璧に保存します）。<br>保存は非常に時間がかかります。コーヒーなどを飲みながら気長に待ちましょう。</h2>
    <!--<h2>GoogleDrive認証：<a href="gDrive.php">こちら</a></h2>-->
    <div id="formBox">
      <form action="imageGet.php" method="post">
        <input type="text" id="userId" name="userID" value="101590036846564916771">
        <p id="default">（初期値は上枝恵美加のIDです）</p>
        <div id="type" class="clearfix">
          <input type="radio" name="type" id="select1" value="1" checked="">
          <label for="select1">通常</label>
          <input type="radio" name="type" id="select2" value="2">
          <label for="select2">古い写真を取得する</label>
          <input type="radio" name="type" id="select3" value="3">
          <label for="select3">もっと古い写真を取得する</label>
        </div>
        <div id="type2" class="clearfix">
          <input type="radio" name="destination" id="select4" value="1" checked="">
          <label for="select4">Picasaアルバムも取得する<br><small>アルバム内の複数の写真も取得します</small></label>
          <input type="radio" name="destination" id="select5" value="2">
          <label for="select5">Google+のみ取得する<br><small>複数写真のアルバムは取得しません</small></label>
        </div>
        <?php
        if (isset($_SESSION['token'])) { //Drive認証check
          //echo '<p id="saveBt"><a href="gDrive.php">GoogleDriveの認証をしてください</a><p>';
          echo '<input type="submit" id="saveBt" value="画像保存！">';
        }else{
          echo '<input type="submit" id="saveBt" value="画像保存！">';
        }
        ?>
      </form>
    </div>
    </div>
    <div id="mainRight"><img src="images/bord.png" alt="画像はimagesフォルダに保存されるよ！"></div>
  </div>
</div>
<footer>
  <p id="copyright">G+･Picasa両方を取得するため、重複して画像が保存される場合があります。　　CopyRight(C) 2014 kix All Rights Reserved.</p>
</footer>
</body>
</html>