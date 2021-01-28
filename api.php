<?php
require_once 'config.php';
if($api!='on'){
    $arr["status"] = "no open API!!";
}

if($_GET['mode']=='create_url'){
    $long_url=$_GET['url'];

    if(strpos($long_url,'https://') === false&&strpos($long_url,'http://') === false){
        $long_url='http://'.$_POST['url'];
      }
    try {
        short_url:
        $dsn = sprintf('mysql:host='.$db_host.';dbname='.$db_name.';charset=utf8');
        $pdo = new PDO($dsn,  $db_user, $db_password, array(PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION));
        $stmt = $pdo->prepare("INSERT INTO short_url (long_url,short_url) VALUES (?, ?)");
        $str = array_merge(range('a', 'z'), range('A', 'Z"'), range('0', '9'));
        $length=5;
        for ($i = 0; $i < $length; $i++) {
            $short_url .= $str[rand(0, count($str)-1)];
        }
        $stmt->execute(array($long_url,$short_url)); 
      } catch (PDOException $e) {
        $errorCode = $e->errorInfo[1];
        if($errorCode == 1062)
        {
          $errormessage = $e->errorInfo[2];
          if(strpos($errormessage,'long_url') !== false){
            $token=1;
          }else if(strpos($errormessage,'short_url') !== false){
            goto short_url;
          }
          
        }
    }
    if($token==1){
        $dbh = new PDO($db_dsn, $db_user, $db_password);
        $sql = 'SELECT * FROM short_url WHERE long_url = "'.$long_url.'"';
        $stmt = $dbh->query($sql);
        foreach ($stmt as $row) {
        $short_url= $row['short_url'];
        }
    }
    $arr["short_url"] = $site_url.$short_url."/";
    $arr["mode"]='create_url';
    $arr["status"] = "ok";
}else if($_GET['mode']=='search_url'){
    $dbh = new PDO($db_dsn, $db_user, $db_password);
    $sql = 'SELECT * FROM short_url WHERE short_url = "'.$_GET['url'].'"';
    $stmt = $dbh->query($sql);
    foreach ($stmt as $row) {
    $long_url= $row['long_url'];
    }
    if(empty($long_url)){
        $arr["status"] = 'no URL';
    }else{
        $arr["search_url"] = $long_url;
        $arr["mode"]='search_url';
        $arr["status"] = "ok";
    }
}else{
    $arr["status"] = "no mode";
}

print json_encode($arr,  JSON_UNESCAPED_UNICODE);