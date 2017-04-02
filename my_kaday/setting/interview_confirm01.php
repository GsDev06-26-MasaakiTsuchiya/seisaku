<?php
session_start();
include("../function/function.php");
login_check();
$_SESSION["interview_id"] = $_GET["target_interview_id"];
// $interview_id = $_GET["target_interview_id"];

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
// $stmt = $pdo->prepare("SELECT * FROM interview,interviewee_info,interview_reserve_time
//  WHERE interview.id= :interview_id AND interviewee_info.id = interview.interviewee_id AND interview_reserve_time.interview_id = :interviewee_id");
// $stmt->bindValue(':interview_id', $interview_id , PDO::PARAM_INT);
// $status = $stmt->execute();
$stmt = $pdo->prepare("SELECT * FROM interview,interviewee_info,interview_reserve_time
 WHERE interview.id= :interview_id AND interviewee_info.id = interview.interviewee_id AND interview_reserve_time.interview_id = interview.id");
$stmt->bindValue(':interview_id', $_SESSION["interview_id"] , PDO::PARAM_INT);
$status = $stmt->execute();


//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res = $stmt->fetch();
  }
$interview_type_str = inteview_type($res["interview_type"]);

//面接担当者
$stmt = $pdo->prepare("SELECT interviewer_name FROM interviewer_list,interviewer_info
 WHERE interviewer_list.interview_id = :interview_id AND interviewer_info.id = interviewer_list.interviewer_id");
$stmt->bindValue(':interview_id', $_SESSION["interview_id"], PDO::PARAM_INT);
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
    $view .= $result["interviewer_name"].' ';
  }
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

<h3 class="text-center">面接日時確認</h3>
<p class="text-center">候補者より面接日時の返信が届いています。</p>
<p class="text-center">確認の上、日程を確定してください。</p>
<div class="container">
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8 text-center">
      <dl class="dl-horizontal">

        <?php if($res["stage_flg"] == 2): ?>
          <table class="table">
            <tr>
              <th>候補者名</th>
              <td class="text-left"><?=$res["interviewee_name"] ?></td>
            </tr>
            <tr>
              <th>面接ステージ</th>
              <td class="text-left"><?=$interview_type_str ?></td>
            </tr>
            <tr>
              <th>面接担当者</th>
              <td class="text-left"><?=$view ?></td>
            </tr>
            <tr>
              <th>面接時間</th>
              <td class="text-left"><?=$res["interview_reserve_time"] ?></td>
            </tr>
          </table>
          <!-- <form action=" "method="post">
          <input type="hidden" name="target_interview_id" value="">
          <input type="hidden" name="interview_date_time" value="">
          <input type="submit" type="button" class="btn btn-info" name="interview_date_time" value="確定">
          </form> -->
          <a class="btn btn-info" href="interview_confirm_insert.php?interview_id=<?= $res["interview_id"] ?>&interview_date_time=<?= $res["interview_reserve_time"] ?>">確定</a>
          <a class="btn btn-danger" href="interview_cancel.php?interview_id='<?= $res["interview_id"] ?>'">キャンセルして再設定</a>
      <?php else: ?>
        <p>確認できる面接日程がありません</p>
      <?php endif; ?>


    </div>
    <div class="col-sm-2"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
