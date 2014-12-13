<?php
// ライブラリ読み込み
require_once('./src/Google/Client.php');
// Google Drive
require_once('./src/Google/Service/Drive.php');
// セッションスタート
session_start();
$client = new Google_Client();
// クライアントID
$client->setClientId('820240936542-c3hqjlsgjpq48hj3fas8idve1m8lok5j.apps.googleusercontent.com');
// クライアントSecret ID
$client->setClientSecret('TAy9624GPRuSq1ZDPy2PEvEl');
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
        folderCreate();
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


<?php
function folderCreate(){
  // ライブラリ読み込み
  require_once('./src/Google/Client.php');
  // Google Drive
  require_once('./src/Google/Service/Drive.php');

  // 認証
  session_start();
  $client = new Google_Client();
  $client->setAccessToken($_SESSION['token']);
  $service = new Google_Service_Drive($client);

  // 追加したいファイルオブジェクトを作成
  $file = new Google_Service_Drive_DriveFile();
  $file->setTitle('えみちの画像一気に保存します');
  $file->setDescription('EmichiImageSave');
  $file->setMimeType('application/vnd.google-apps.folder');

  // Google Driveに登録するファイル
  $filename = 'emichi.txt';
  $chunkSizeBytes = 1 * 1024 * 1024;

  // 親としたいフォルダの ID
  $parentId = 'root';
  // 親オブジェクト
  $parent = new Google_Service_Drive_ParentReference();
  $parent->setId($parentId);
  // ファイルに親をセット
  $file->setParents(array($parent));

  // ファイルを送信してHTTP通信を開く
  $client->setDefer(true);
  $request = $service->files->insert($file);

  // アップするファイルオブジェクトを生成
  $media = new Google_Http_MediaFileUpload(
          $client,
          $request,
          'application/vnd.google-apps.folder',
          null,
          true,
          $chunkSizeBytes
  );
  $media->setFileSize(filesize($filename));

  // ファイルを送信する
  $status = false;
  $handle = fopen($filename, "rb");
  while (!$status && !feof($handle)) {
      $chunk = fread($handle, $chunkSizeBytes);
      $status = $media->nextChunk($chunk);
  }

  $result = false;
  if($status != false) {
      $result = $status;
  }

  fclose($handle);
  $client->setDefer(false);
}
?>