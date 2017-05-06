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

//ここからメール送信
//候補者あて
//候補者メールアドレス検索
$stmt_interviewee = $pdo->prepare("SELECT interview.interviewee_id,interviewee_info.interviewee_name,interviewee_info.mail FROM interviewee_info INNER JOIN interview ON interviewee_info.id = interview.interviewee_id where interview.id = :interview_id");
$stmt_interviewee->bindValue(':interview_id',$interview_id, PDO::PARAM_INT);
$status_interviewee = $stmt_interviewee->execute();
if($status_interviewee==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interviewee->errorInfo();
  exit("ErrorQuery_interviewee:".$error[2]);
}else{
  $res_interviewee = $stmt_interviewee->fetch();
  }

include("../sendgrid/sendgrid_send.php");
$to_interviewee = $res_interviewee["mail"];
$url_path = path_for_mail();
$subject_text_interviewee = "[smartinterview]○○株式会社様より面接時間確定のご連絡";
$text_interviewee = "";
$text_interviewee .= $res_interviewee["interviewee_name"]."様".PHP_EOL;
$text_interviewee .= "以下の日時で面接時間が確定しました。".PHP_EOL;
$text_interviewee .= $interview_date_time.PHP_EOL;
$text_interviewee .= "以下のサイトで面接の日時や会社の情報がご覧になられますので、事前にかならずご確認ください。".PHP_EOL;
$text_interviewee .= $url_path.'forinterviewee/index.php? interviewee_id='.$res_interviewee["interviewee_id"];
$res_send_interviewee = send_email_by_sendgrid($to_interviewee,$subject_text_interviewee,$text_interviewee);

// var_dump($res_send);

//面接担当者へのメール
$stmt_interviewer = $pdo->prepare("SELECT * FROM interviewer_list INNER JOIN interviewer_info ON interviewer_list.interviewer_id = interviewer_info.id WHERE interviewer_list.interview_id = :interview_id");
$stmt_interviewer->bindValue(':interview_id',$interview_id, PDO::PARAM_INT);
$status_interviewer = $stmt_interviewer->execute();

//３．データ表示
$mail_interviewer="";
$name_interviewer="";
if($status_interviewer==false){
  $error = $stmt_interviewer->errorInfo();
  exit("ErrorQuery_interviewer:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_interviewer = $stmt_interviewer->fetch(PDO::FETCH_ASSOC)){
    $mail_interviewer .= $result_interviewer["interviewer_mail"].',';
    $name_interviewer .= $result_interviewer["interviewer_name"].',';

  }
  $mail_interviewer = rtrim($mail_interviewer, ',');
  $name_interviewer = rtrim($name_interviewer, ',');
}

$to_interviewer = $mail_interviewer;
$url_path = path_for_mail();
$subject_text_interviewer = "[smartinterview]面接スケジュールのご案内";
$text_interviewer = "";
// $text_interviewer  .= $res_interviewee["interviewee_name"]."様".PHP_EOL;
$text_interviewer  .= "以下のとおり面接スケジュールが確定しました。".PHP_EOL;
$text_interviewer  .= "候補者名:".$res_interviewee["interviewee_name"].PHP_EOL;
$text_interviewer  .= "日時:".$interview_date_time.PHP_EOL;
$text_interviewer  .= "面接担当者:".$name_interviewer .PHP_EOL;
$text_interviewer  .= "詳細はsmartinterviewにログインしてご確認ください。".PHP_EOL;
$text_interviewer  .= $url_path.'login_out/login.php'.PHP_EOL;
$res_send_interviewer  = send_email_by_sendgrid($to_interviewer,$subject_text_interviewer,$text_interviewer);

// var_dump($res_send_interviewer);

header("Location: interviewee_select.php");//location: のあとに必ずスペースが入る
exit;


?>
