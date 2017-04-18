<?php

session_start();
include("../function/function.php");
login_check();

//1. POSTデータ取得



$_SESSION["interview_date_time_reserves"] = $_POST["interview_date_time_reserves"];
$interviewer_id_count = count($_SESSION["interviewer_id"]);//面接担当者の数
$interview_type_str = interview_type($_SESSION["interview_type_num"]);//面接のステージ

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$view="";
for($i = 0; $i < $interviewer_id_count; $i++){
$stmt = $pdo->prepare("SELECT interviewer_name FROM interviewer_info where id = :interviewer_id");
$stmt->bindValue(':interviewer_id', $_SESSION["interviewer_id"][$i], PDO::PARAM_INT);
$status = $stmt->execute();
//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
    $res = $stmt->fetch();
    $view .= $res["interviewer_name"];
    $view .= '&emsp;';
  }
}

$stmt = $pdo->prepare("SELECT * FROM interviewee_info where id= :interviewee_id");
$stmt->bindValue(':interviewee_id', $_SESSION["interviewee_id"], PDO::PARAM_INT);
$status2 = $stmt->execute();

//３．データ表示
if($status2==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res = $stmt->fetch();
  }

?>

<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>interview_setting</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">ビデオ面接予約3</h3>
<h4 class="text-center">送信先、テキスト確認</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="video_interview_setting_insert.php" method="post">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewee_name">候補者名</label><div class="col-sm-10"><p class="form-control-static"><?= h($res["interviewee_name"]); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_type">選考ステップ</label>
          <div class="col-sm-10"><p class="form-control-static"><?= h($interview_type_str); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_type">面接担当者</label>
          <div class="col-sm-10"><p class="form-control-static"><?= $view ?></p></div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-2" for="interviewer">面接日時候補</label>
            <div class="col-sm-10"><p class="form-control-static">
              <ul>
              <?php foreach($_SESSION["interview_date_time_reserves"] as $interview_date_time_reserve):?>
                <li><?php echo $interview_date_time_reserve ;?></li>
              <?php endforeach; ?>
              </ul>
            </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="toSubmit">送信先</label>
          <!-- 本人　エージェント　媒体経由　その他 -->
          <!-- <div class="col-sm-10"><p class="form-control-static"><?= $view ?></p></div> -->
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="toSubmit">送信先アドレス</label>
          <div class="col-sm-10">
            <input type="text" name="toSubmit_address" value="<?= h($res["mail"])?>">
          </div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="mail_text">送信テキスト</label>
          <div class="col-sm-10">
            <textarea name="mail_text" style="width:90%; height:300px;">
　この度は弊社採用募集にご応募いただきましてありがとうございます。選考にあたりましてビデオ面接システムを利用して実施させていただきたいと思います。
下記URLにアクセスしていただき、動作環境のテストをお願い致します。
また面接日時の候補も確認いただけますので、ご都合のよろしい日時のご選択をお願い致します。
もし動作環境が整わなかったり、ご都合の良い日時がない場合はURL上のフォームよりご連絡ください。
よろしくお願い申し上げます。
          </textarea>
          </div>
        </div>
        <div class="text-center">
          <input class="btn btn-default" type="submit" value="送信">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
