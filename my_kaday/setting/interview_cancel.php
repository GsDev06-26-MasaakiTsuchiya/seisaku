<?php
//1. POSTデータ取得
session_start();
include("../function/function.php");
include("../sendgrid/sendgrid_send.php");
$interview_id = $_GET["interview_id"];

//2. DB接続します
$pdo = db_con();

//interviewのステージフラグを抽出(メールの送信方法を分けるため。
)
$stmt_interview_stage = $pdo->prepare("SELECT stage_flg,interviewee_id FROM interview WHERE id=:id;");
$stmt_interview_stage->bindValue(':anchet_id',$anchet_id, PDO::PARAM_INT);
$status_interview_stage = $stmt_interview_stage->execute();
if($status_interview_stage==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_interview_stage->errorInfo();
  exit("ErrorQuery_interview_stage:".$error[2]);
}else{
  $res_interview_stage = $stmt_interview_stage->fetch();
}

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

//メール送信
$url_path = path_for_mail();
if($res_interview_stage["stage_flg"]==2){//確定前キャンセル 候補者に送る。
  $stmt_interviewee = $pdo->prepare("SELECT * FROM interviewee_info WHERE id=:interviewee_id;");
  $stmt_interviewee->bindValue(':interviewee_id',$stmt_interview_stage["interviewee_id"], PDO::PARAM_INT);
  $status_interviewee = $stmt_interviewee->execute();
  if($status_interviewee==false){
    //execute（SQL実行時にエラーがある場合）
    $error = $stmt_interviewee->errorInfo();
    exit("ErrorQuery_interviewee:".$error[2]);
  }else{
    $res_interviewee = $stmt_interviewee->fetch();
  }
  $to_s = $res_interviewee["mail"];
  $subject_text = "[smartinterview]○○株式会社様より面接日時についてご案内";
  $text = "";
  // $text .= $anchet_message.;
  // $text .= $res_interviewee_mail["interviewee_name"]."様".PHP_EOL;
  $text .= $res_interviewee["interviewee_name"]."様".PHP_EOL;
  $text .= "現在調整中の面接日時について、ご返信いただきました日時で確定できなかったため再調整が必要となりました。".PHP_EOL;
  $text .= "日程について改めて○○株式会社様よりご連絡がありますのでしばらくお待ちください。".PHP_EOL;
  $text .= "どうぞよろしくお願いいたします。".PHP_EOL;
  $res_send = send_email_by_sendgrid($to_s,$subject_text,$text);
  var_dump($res_send);
}else if($res_interview_stage["stage_flg"]==3){//確定後キャンセル

}

$res_interview_stage["interviewee_id"]

$to_s = kanri_users_mails();
$subject_text = "[smartinterview]".$res_interviewee_name["interviewee_name"]."様よりアンケート回答のお知らせ";
$text = "";
// $text .= $anchet_message.;
// $text .= $res_interviewee_mail["interviewee_name"]."様".PHP_EOL;
$text .= $res_interviewee_name["interviewee_name"]."様よりアンケート回答のが届いております。".PHP_EOL;
$text .= "smartinterviewにログイン後,候補者一覧画面からご確認いただけます。".PHP_EOL;
$text .= $url_path.'setting/questionnaire_show.php?anchet_id='.$anchet_id;
$res_send = send_email_by_sendgrid($to_s,$subject_text,$text);

var_dump($res_send);


//リダイレクト
  header("Location: interview_resetting.php?interview_id=".$interview_id);//location: のあとに必ずスペースが入る
  exit;

?>
