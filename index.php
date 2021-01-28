<?php
require_once 'config.php';
$short_url=NULL;
$token=0;
if($recaptcha=='off'){
if(isset($_POST['create'])&&isset($_POST['url'])&&$_POST['url']!=""){
    $long_url=$_POST['url'];
    if(strpos($_POST['url'],'https://') === false&&strpos($_POST['url'],'http://') === false){
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
}
}else if($recaptcha=='on'){
    if(isset($_POST['create'])&&isset($_POST['url'])&&$_POST['url']!=""&&isset($_POST["recaptchaResponse"]) && !empty($_POST["recaptchaResponse"])){
        $verifyResponse = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$secret_key."&response=".$_POST["recaptchaResponse"]);
            $reCAPTCHA = json_decode($verifyResponse);
            if ($reCAPTCHA->success)
            {


                $long_url=$_POST['url'];
                if(strpos($_POST['url'],'https://') === false&&strpos($_POST['url'],'http://') === false){
                    $long_url='http://'.$_POST['url'];
                  }
                try {
                    short_url_1:
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
                        goto short_url_1;
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
        }
        
        else
        {
        echo $bot_message;
        exit;
        }
    }
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>ショートURL作成</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://kit.fontawesome.com/c08ca8ae03.js" crossorigin="anonymous"></script>
    <?php if($recaptcha=='on'):?>
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo$site_key;?>"></script>
    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute("<?php echo$site_key;?>", {action: "sent"}).then(function(token) {
            var recaptchaResponse = document.getElementById("recaptchaResponse");
            recaptchaResponse.value = token;
            });
        });
    </script>
    <?php endif;?>
    <link rel="shortcut icon" href="<?php echo$favicon;?>">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href=""><?php echo$site_name;?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="">ショートURL作成</a>
                </li>
                <?php if($api=='on'):?>
                    <li class="nav-item active">
                    <a class="nav-link" href="API.php">API</a>
                    </li>
                <?php endif;?>
            </ul>
        </div>
    </nav>
    <br>
    <div class="container">
    <h2 class="text-center">短縮URL作成</h2>
    <?php if(empty($short_url)):?>
    <form action="" method="post">
    
    <div class="form-group">
    <input type="text" class="form-control" name="url" placeholder="URL">
    </div>
    <?php if($recaptcha=='on'):?>
    <input type="hidden" name="recaptchaResponse" id="recaptchaResponse">
    <?php endif;?>
    <button type="submit" class="btn btn-success" name="create" style="width:100%">&nbsp;&nbsp;作成!</button>
    </form>
    <?php else:?>
    <br>
    <h3>ショートURLはこちら</h3>
    <code><?php echo$site_url.$short_url;?>/</code>
    <?php endif;?>
    <br>
    </div>
</body>

</html>