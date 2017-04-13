<?php
//1. POSTデータ取得
session_start();
include("../function/function.php");

$interview_id = $_GET["interview_id"];

//2. DB接続します
$pdo = db_con();

try{
$pdo->beginTransaction();//transaction 開始
//最初に対象のinterview_reserve_timeをすべて削除します。
$stmt = $pdo->prepare("DELETE FROM interview_reserve_time WHERE interview_id =:interview_id");
$stmt->bindValue(':interview_id', $interview_id);
$status = $stmt->execute();


//次に対象のinterviewer_listをすべて削除します。
$stmt2 = $pdo->prepare("DELETE FROM interviewer_list WHERE interview_id =:interview_id");
$stmt2->bindValue(':interview_id', $interview_id);
$status2 = $stmt2->execute();

//次に対象のinterviewのstage_flgを6[再調整に]変更します。
$stmt3 = $pdo->prepare("UPDATE interview SET stage_flg = :stage_flg,fix_time = :fix_time WHERE id=:interview_id");
$stmt3->bindValue(':interview_id', $interview_id);
$stmt3->bindValue(':stage_flg', 6);
$stmt3->bindValue(':fix_time', "");
$status3 = $stmt3->execute();

$pdo->commit();
}catch (PDOException $e) {
  $pdo->rollback();
  echo "とちゅうでとまりました";
  exit;
}

//データ登録処理後 errorがあったらとまる
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
if($status3==false){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error3 = $stmt3->errorInfo();
  exit("QueryError:".$error3[2]);
}

//リダイレクト
  header("Location: interview_resetting.php?interview_id=".$interview_id);//location: のあとに必ずスペースが入る
  exit;

?>
