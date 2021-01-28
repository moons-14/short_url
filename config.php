<?php
$site_name="サイトネーム";//サイトの名前
$site_url='';//サイトのURL(必ず最後は/をつけてください)
$favicon='favicon.ico';//faviconのファイル名

//データーベース情報
//以下の情報は必ず変更してください

//データーベースのホストネーム
$db_host='';
//データーベースネーム
$db_name='';

$db_dsn = 'mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8';//この行は編集しないで

// データベースのユーザー名
$db_user='';

// データベースのパスワード
$db_password='';

//APIを実装するか
$api='off';//APIを実装する場合offの部分をonにしてください

//recaptchaの情報
$recaptcha='off';//オフにする場合offの部分をonにしてください
//以下の情報は必要に応じて変更してください
$site_key='';//サイトキー
$secret_key='';//シークレットキー

//botと判定されたときのメッセージ
$bot_message='BOTと判定されました';
