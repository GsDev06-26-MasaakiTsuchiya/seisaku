<?php
session_start();
include("../function/function.php");
login_check();

$_SESSION["interviewee_id"] = $_GET["target_interviewee_id"];
$_SESSION["interview_type_num"] = $_GET["interview_type_num"];

$interview_type_str = inteview_type($_SESSION["interview_type_num"]);

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interviewer_info");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .='<option value="'.h($result["id"]).'">'.h($result["interviewer_name"]).'</option>';
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
<title>interview_setting</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">ビデオ面接予約1</h3>
<h4 class="text-center">面接担当者選択</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <form class="form-group form-horizontal" action="video_interview_setting_02.php" method="post">
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewee_name">候補者名</label><div class="col-sm-10"><p class="form-control-static"><?= h($res["interviewee_name"]); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interview_type">選考ステップ</label>
          <div class="col-sm-10"><p class="form-control-static"><?= h($interview_type_str); ?></p></div>
        </div>
        <div class="form-group">
          <label class="control-label col-sm-2" for="interviewer_id">選考担当者</label>
          <div class="col-sm-10">
            <select class="form-control" name="interviewer_id[]" multiple>
              <?= $view ?>
            </select>
          </div>
        </div>
        <div class="text-center">
          <input class="btn btn-info" type="submit" value="次へ">
        </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
