<?php
session_start();
include("../function/function.php");
$anchet_id = $_GET["anchet_id"];


$pdo = db_con();

//２．データ登録SQL作成
$stmt_question = $pdo->prepare("SELECT * FROM detail_question WHERE anchet_id = :anchet_id ORDER BY question_order ASC");
$stmt_question->bindValue(':anchet_id',$anchet_id,PDO::PARAM_INT);
$status_question = $stmt_question->execute();

//３．データ表示
$question_answer_view="";
if($status_question==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_question->errorInfo();
  exit("ErrorQuery_question:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_question = $stmt_question->fetch(PDO::FETCH_ASSOC)){
    $question_answer_view .= '<div class="reply_item">';
    $question_answer_view .= '<h5><span>質問:'.$result_question["question"].'</h5>';
    $question_answer_view .= '<ul class="list-unstyled answer_item">';
    //回答を検索
      $stmt_answer = $pdo->prepare("SELECT * FROM detail_answer WHERE detail_question_id = :detail_question_id");
      $stmt_answer->bindValue(':detail_question_id',$result_question["detail_question_id"],PDO::PARAM_INT);
      $status_answer = $stmt_answer->execute();
      //３．データ表示
      if($status_answer==false){
        //execute（SQL実行時にエラーがある場合）
        $error = $stmt_answer->errorInfo();
        exit("ErrorQuery_answer:".$error[2]);
      }else{
        //Selectデータの数だけ自動でループしてくれる
        while( $result_answer = $stmt_answer->fetch(PDO::FETCH_ASSOC)){
          $question_answer_view.= '<li>'.$result_answer["answer"].'<li>';
        }
      }
    $question_answer_view .= '</ul>';
    $question_answer_view .= '</div>';

  }
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>interviewer_detail</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
</head>
<style>
.reply_item{
  margin-bottom:40px;

}
.answer_item{

}
</style>
<body>
<?php include("../template/nav.php") ?>
<div class="container">
  <h2 class="text-center" style="margin-bottom:30px;">
    アンケート返信内容
  </h2>
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8 text-center">
      <?= $question_answer_view ?>
    </div>
    <div class="col-sm-2"></div>
  </div>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
