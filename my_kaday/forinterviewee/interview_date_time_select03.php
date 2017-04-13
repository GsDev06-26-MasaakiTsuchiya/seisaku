<?php

// /10/my_kaday/setting/interview_date_time_select01.php?interviewee_id=*&interview_id=*

session_start();
include("../function/function.php");

$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interview_reserve_time WHERE interview_id = :interview_id");
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

    $view .='<div class="radio text-center">';

    //今日の日付の翌日の日付を取得
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    //interview_reserve_timeを日付と時間に分割
    $interview_reserve_date_and_time_array = explode(" ",$result["interview_reserve_time"]);
    //interview_reserve_timeの日付と今日の翌日の日付を比較
    if($tomorrow < $interview_reserve_date_and_time_array[0]){
      //interview_reserve_timeのほうがおおきければ、選択可能
      //そうでなければ選択不可
      $view .='<label><input type="radio" name="interview_reserve_time" id="'.$result["interview_reserve_time"].'" value="'.$result["interview_reserve_time"].'">'.$result["interview_reserve_time"].'</label>';
    }else{
      $view .='<label class="unselectable"><input type="radio" name="interview_reserve_time" id="'.$result["interview_reserve_time"].'" value="'.$result["interview_reserve_time"].'" disabled="disabled">'.$result["interview_reserve_time"].'</label>';
    }
    $view .='</div>';
  }
}

?>

<html lang="ja">
<head>
<meta charset="utf-8">
<title>interview_rader_chart > input</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">

<style>
h4.pg{
font-size:0.9em;
}
.gray{
  color:#aaa;
}

.unselectable{
  text-decoration: line-through;
}
</style>



</head>
<body>
<?php include("../template/nav_for_interviewee.php") ?>
<div class="container-fruid">
  <div class="row">
      <div class="col-xs-2"></div>
      <h4 class="col-xs-2 pg text-center gray">1,規約同意</h4><h4 class="col-xs-2 pg text-center gray">2,動作検証</h4><h4 class="col-xs-2 pg text-center">3,面接日時選択</h4><h4 class=" col-xs-2 pg text-center gray">4,返信完了
      </h4>
      <div class="col-xs-2"></div>
  </div>
</div>
<div class="container-fruid">
  <div class="row">
    <div class="col-xs-2"></div>
    <div class="col-xs-8">
    <h3 class="text-center">面接日時選択</h3>
    <p  class="text-center">
    以下の日時候補の中からご対応可能な日時をご選択ください。<br>
  </p>
    <div class="row" id="streams" style="display:none;">
    </div>
    <div class="col-xs-2"></div>
  </div>
</div>

</div>
  <div class="container-fruid">
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <form class="form-horizontal" action="interview_date_time_insert.php" method="post">
          <div class="form-group">
            <?= $view ?>
          </div>
          <div class="form-group text-center">
            <input type="submit" class="btn btn-info" id="submit" value="送信" />
          </div>
        </form>
      </div>
      <div class="col-sm-2"></div>
    </div>
    <div class="row">
      <div class="col-sm-2"></div>
      <div class="col-sm-8">
        <p class="text-center">※過去、当日、翌日の日時は選択できません。</p>
        <p class="text-center">※対応可能な日時がない場合、ビデオ面接で対応できない場合は<a href="interview_reset.php">こちら</a>からご連絡ください。</p>
      </div>
      <div class="col-sm-2"></div>
    </div>
  </div>
    </form>

  </div>
<?php include("../template/footer_for_interviewee.html") ?>

</body>
</html>
