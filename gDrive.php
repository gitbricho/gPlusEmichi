<?php
// ライブラリ読み込み
require('./src/Google/Client.php');
// Google Drive
require('./src/Google/Service/Drive.php');
// セッションスタート
session_start();
$client = new Google_Client();
// クライアントID
$client->setClientId('820240936542-c3hqjlsgjpq48hj3fas8idve1m8lok5j.apps.googleusercontent.com');
// クライアントSecret
$client->setClientSecret('TAy9624GPRuSq1ZDPy2PEvEl');
// リダイレクトURL
$client->setRedirectUri('http://localhost:8888/gPlusEmichi/gDrive.php');
$service = new Google_Service_Drive($client);
// 許可されてリダイレクトされると URL に code が付加されている
// code があったら受け取って、認証する
if (isset($_GET['code'])) {
    // 認証(トークン、リフレッシュトークンを取得)
    $client->authenticate();
    // 取得したトークンをセッションにセット
    $_SESSION['token'] = $client->getAccessToken();
    // リダイレクト(ここらへんは任意に)
    header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
    exit;
}
if (isset($_SESSION['token'])) {
    // トークンセット
    $client->setAccessToken($_SESSION['token']);
}
// トークンがセットされていたら
if ($client->getAccessToken()) {
    try {
      header("Location:index.php");
    } catch (Google_Exception $e) {
        echo $e->getMessage();
    }
} else {
    // 認証用URL取得
    $authUrl = $client->createAuthUrl();
    echo '<a href="'.$authUrl.'">アプリケーションのアクセスを許可してください。</a>';
}

?>