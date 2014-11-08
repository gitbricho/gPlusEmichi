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
<script type="text/javascript">

</script>
<style>
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
  <h1>Google+の画像一気に保存しちゃいます！</h1>
  <h2>Google+のユーザIDを入力してください。すべての画像を保存します。</h2>
  <form action="imageGet.php" method="post">
    <input type="text" name="userID" value="101590036846564916771">
    <input type="checkbox" name="old"><label>古い写真を取得する<small>（新しい写真を取得せずに古い写真を取得するようにがんばります）</small></label>
    <input type="submit" value="画像保存！">
  </form>
</div>
<footer>
  <p id="copyright">CopyRight(C) 2013-2014 kix All Rights Reserved.</p>
</footer>
</body>
</html>