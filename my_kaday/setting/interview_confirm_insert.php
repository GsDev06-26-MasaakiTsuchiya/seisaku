<?php

session_start();
include("../function/function.php");
login_check();

//1. POSTデータ取得 from video_interview_setting_03
$interview_id = $_GET["interview_id"];
$interview_date_time = $_GET["interview_date_time"];

// var_dump($interview_id);
// var_dump($interview_date_time);
// exit;

//2. DB接続します
$pdo = db_con();

//３．データ登録SQL作成



try{
$pdo->beginTransaction();//transaction 開始

//確定した面接時間を入力するとともにstage_flgを3（日程確定）に変更
$stmt = $pdo->prepare("UPDATE interview SET interview_date_time = :interview_date_time,stage_flg = :stage_flg WHERE id=:id");
$stmt->bindValue(':id', $interview_id, PDO::PARAM_INT);
$stmt->bindValue(':interview_date_time', $interview_date_time, PDO::PARAM_STR);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':stage_flg', 3, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();

//予約時間のリストを削除
$stmt2 = $pdo->prepare("DELETE FROM interview_reserve_time WHERE interview_id=:interview_id");
$stmt2->bindValue(':interview_id', $interview_id);
$status2 = $stmt2->execute();

$pdo->commit();

}catch (PDOException $e) {
  $pdo->rollback();
  echo "とちゅうでとまりました";
  exit;
}


if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError:".$error[2]);
}
if($status2==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error2 = $stmt2->errorInfo();
    exit("QueryError:".$error2[2]);
}

header("Location: interviewee_select.php");//location: のあとに必ずスペースが入る
exit;


?>
