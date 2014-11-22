<?php
// ライブラリ読み込み
require_once('src/Google/Client.php');
// Google Drive
require_once('src/Google/Service/Drive.php');
// セッションスタート
session_start();
$client = new Google_Client();
// クライアントID
$client->setClientId('820240936542-5od9eqva1ddaa7mvrr8fiefr9f4ls15p.apps.googleusercontent.com');
// クライアントSecret ID
$client->setClientSecret('nytFAZf-OaN-bBz540gHjX01');
// リダイレクトURL
$client->setRedirectUri('http://localhost:8888/gPlusEmichi/gDrive.php');
 
$service = new Google_Service_Drive($client);
// 許可されてリダイレクトされると URL に code が付加されている
// code があったら受け取って、認証する
if (isset($_GET['code'])) {
    // 認証
    $client->authenticate($_GET['code']);
    $_SESSION['token'] = $client->getAccessToken();
    // リダイレクト GETパラメータを見えなくするため（しなくてもOK）
    header('Location: http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF']);
    exit;
}
 
// セッションからアクセストークンを取得
if (isset($_SESSION['token'])) {
    // トークンセット
    $client->setAccessToken($_SESSION['token']);
}
 
// トークンがセットされていたら
if ($client->getAccessToken()) {
    try {
        //echo "Google Drive Api 連携完了！";
        header("Location:index.php");
    } catch (Google_Exception $e) {
        echo $e->getMessage();
    }
} else {
    // 認証用URL取得
    $client->setScopes(Google_Service_Drive::DRIVE);
    $authUrl = $client->createAuthUrl();
    echo '<a href="'.$authUrl.'">アプリケーションのアクセスを許可してください。</a>';
}
?>