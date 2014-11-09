<!doctype html>
<html lang="jp">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Script-Type" content="text/javascript">
<link rel="stylesheet" type="text/css" href="css/transitions.css">
<link href='http://fonts.googleapis.com/css?family=Poiret+One' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/3.17.2/build/cssreset/cssreset-min.css">
<link rel="stylesheet" type="text/css" href="css/style.css">
<title>えみちの画像 一気に保存します!メンバーのGoogle+の画像 一気に保存しちゃいます！）</title>
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
</head>
<body>
<header>
  <h1><a href="index.php">えみちの画像 一気に保存します!<br><small>（メンバーのGoogle+の画像 一気に保存しちゃいます！）</small></a></h1>
</header>
<div id="wrapper">
  <div id="cover" class="clearfix">
    <div id="mainLeft">
    <p id="title">Who do you save?</p>
    <h2>Google+のユーザIDを入力してください。すべての画像を保存します。<br>保存は非常に時間がかかります。コーヒーなどを飲みながら気長に待ちましょう。</h2>
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
        
        <input type="submit" id="saveBt" value="画像保存！">
      </form>
    </div>
    </div>
    <div id="mainRight"><img src="images/bord.png" alt="画像はimagesフォルダに保存されるよ！"></div>
  </div>
</div>
<footer>
  <p id="copyright">CopyRight(C) 2014 kix All Rights Reserved.</p>
</footer>
</body>
</html>