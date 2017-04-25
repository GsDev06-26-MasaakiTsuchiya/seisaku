<?php
session_start();
include("../function/function.php");
login_check();
kanri_check();

//1. POSTデータ取得
$anchet_message = $_POST["anchet_message"];
$deadline = $_POST["deadline"];

$pdo = db_con();

//anchet テーブルに情報登録
//ほんとうならupdate文

$stmt_anchet = $pdo->prepare("INSERT INTO anchet(anchet_id,interviewee_id,form_id,anchet_message,send_date,deadline,stage_flg
)VALUES(NULL,:interviewee_id,:form_id,:anchet_message,sysdate(),:deadline,:stage_flg)");
$stmt_anchet->bindValue(':interviewee_id', $_SESSION["interviewee_id"], PDO::PARAM_INT);
$stmt_anchet->bindValue(':form_id', $_SESSION["form_id"], PDO::PARAM_INT);
$stmt_anchet->bindValue(':anchet_message', $anchet_message, PDO::PARAM_INT);
$stmt_anchet->bindValue(':deadline', $deadline, PDO::PARAM_STR);
$stmt_anchet->bindValue(':stage_flg', 1, PDO::PARAM_INT);
$status_anchet = $stmt_anchet->execute();

if($status_anchet == false){
    //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
    $error = $stmt_status_anchet->errorInfo();
    exit("QueryError_status_anchet:".$error[2]);
}

header("Location: interviewee_select.php");
exit;
// }

?>
