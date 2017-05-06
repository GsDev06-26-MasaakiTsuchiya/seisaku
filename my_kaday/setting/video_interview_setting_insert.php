<?php

session_start();
include("../function/function.php");
login_check();


//1. POSTデータ取得 from video_interview_setting_03
$toSubmit = $_POST["toSubmit_address"];
$mail_text = $_POST["mail_text"];


//2. DB接続します
$pdo = db_con();
//複数のテーブルにデータをインサートするのはどうやるの？

//３．データ登録SQL作成
//interview の設定


try{
$pdo->beginTransaction();//transaction 開始

$stmt = $pdo->prepare("INSERT INTO interview(id, interview_type, interviewee_id,stage_flg
)VALUES(NULL, :interview_type, :interviewee_id, :stage_flg)");
$stmt->bindValue(':interview_type', $_SESSION["interview_type_num"], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':interviewee_id', $_SESSION["interviewee_id"], PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$stmt->bindValue(':stage_flg', 1, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
$status = $stmt->execute();


$interview_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("INSERT INTO interviewer_list(id, interview_id, interviewer_id
)VALUES(NULL, :interview_id, :interviewer_id)");
$stmt->bindParam(':interview_id', $interview_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
foreach($_SESSION["interviewer_id"] as $interviewerId){
  $stmt->bindValue(':interviewer_id', $interviewerId, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $status2 = $stmt->execute();
}

$stmt = $pdo->prepare("INSERT INTO interview_reserve_time(id, interview_id, interview_reserve_time
)VALUES(NULL, :interview_id, :interview_reserve_time)");
$stmt->bindParam(':interview_id', $interview_id, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
foreach($_SESSION["interview_date_time_reserves"] as $interview_reserve_time){
  $stmt->bindValue(':interview_reserve_time', $interview_reserve_time, PDO::PARAM_INT);  //Integer（数値の場合 PDO::PARAM_INT)
  $status3 = $stmt->execute();
}

$pdo->commit();

}catch (PDOException $e) {
  $pdo->rollback();
  echo "とちゅうでとまりました";
  exit;
}
if($status==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError1:".$error[2]);
}
if($status2==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError2:".$error[2]);
}
if($status3==false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt->errorInfo();
    exit("QueryError3:".$error[2]);
}

//ここからメール送信設定
$stmt_interviewee_info= $pdo->prepare("SELECT interviewee_name,mail FROM interviewee_info where id=:interviewee_id");
$stmt_interviewee_info->bindValue(':interviewee_id',$_SESSION["interviewee_id"], PDO::PARAM_INT);
$status_interviewee_info = $stmt_interviewee_info->execute();
if($status_interviewee_info==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee_info->errorInfo();
  exit("ErrorQuery_interviewee_info:".$error[2]);
}else{
  $res_interviewee_info = $stmt_interviewee_info->fetch();
  }

include("../sendgrid/sendgrid_send.php");
$url_path = path_for_mail();
$to_s = $toSubmit;
$subject_text = "[smartinterview]○○株式会社より面接時間調整のお知らせ[web面接]";
$text = "";
// $text .= $anchet_message.;
$text .= $res_interviewee_info["interviewee_name"]."様".PHP_EOL;
$text .= "○○株式会社様より面接時間調整のご連絡が届いております。".PHP_EOL;
$text .= "以下URLよりにアクセスしていただき、なるべく早めに面接日時の候補のご確認をお願いいたします。".PHP_EOL;
$text .= "なお、確認に時間がかかりますと面接時間の候補日時が過ぎてしまうことがありますのでご注意ください。".PHP_EOL;
$text .= $url_path.'forinterviewee/interview_date_time_select01.php?interview_id='.$interview_id;
$res_send = send_email_by_sendgrid($to_s,$subject_text,$text);

var_dump($res_send);



header("Location: interviewee_select.php");//location: のあとに必ずスペースが入る
exit;


?>
