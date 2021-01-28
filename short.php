<?php
$short_url= $_SERVER['QUERY_STRING'];
require_once 'config.php';
$dbh = new PDO($db_dsn, $db_user, $db_password);
$sql = 'SELECT * FROM short_url WHERE short_url = "'.$short_url.'"';
$stmt = $dbh->query($sql);
foreach ($stmt as $row) {
$long_url= $row['long_url'];
}

if(empty($long_url)||$long_url==""){
    header('Location: ../');
    exit;
}else{
header('Location:'.$long_url);
exit;
}
?>