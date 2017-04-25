<?php
session_start();
include("../function/function.php");
login_check();

$anchet_message = $_POST["anchet_message"];
$deadline = $_POST["deadline"]." 23:59:59";
$form_id = $_SESSION["form_id"];

//1.  DB接続します

$pdo = db_con();
$stmt = $pdo->prepare("SELECT * FROM form INNER JOIN form_item ON form.form_id = form_item.form_id WHERE form.form_id = :form_id");
$stmt->bindValue(':form_id',$form_id, PDO::PARAM_INT);
$status = $stmt->execute();

$form_item_view = "";
 if($status==false){
   //execute（SQL実行時にエラーがある場合）
   $error = $stmt->errorInfo();
   exit("ErrorQuery:".$error[2]);

 }else{
   //Selectデータの数だけ自動でループしてくれる
   while( $result_form_item = $stmt->fetch(PDO::FETCH_ASSOC)){
   $form_element_id = "form_".$result_form_item["form_order"];//from_ + 数値　でidを作成。
   $form_item_view .= '<div class="form-group" id="'.$form_element_id.'">';

   if($result_form_item["form_type"] == "textarea"){//textareaの場合
     $form_item_view .= '<label class="control-label" for="answer['.$form_element_id.']">'.$result_form_item["question"].'</label>';
     $form_item_view .= '<input type="hidden" name="question['.$form_element_id.']" value="'.$result_form_item["question"].'">';
     $form_item_view .= '<textarea class="form-control" name="answer['.$form_element_id.'][]" disabled></textarea>';

   }elseif($result_form_item["form_type"] == "radio"){//radio-boxの場合
      $form_item_view .= '<p class="question_text text-left" for="answer['.$form_element_id.']">'.$result_form_item["question"].'</p>';
      $form_item_view .= '<input type="hidden" name="question['.$form_element_id.']" value="'.$result_form_item["question"].'">';
      $stmt_select_item = $pdo->prepare("SELECT * FROM form_item INNER JOIN select_item ON form_item.form_item_id = select_item.form_item_id WHERE form_item.form_id = :form_id AND form_item.form_item_id = :form_item_id");
      $stmt_select_item->bindValue(':form_id', $form_id, PDO::PARAM_INT);
      $stmt_select_item->bindValue(':form_item_id', $result_form_item["form_item_id"], PDO::PARAM_INT);
      $statu_select_item = $stmt_select_item->execute();
        if($statu_select_item==false){
          //execute（SQL実行時にエラーがある場合）
          $error = $stmt_select_item->errorInfo();
          exit("ErrorQuery_select_item:".$error[2]);
        }else{
          //Selectデータの数だけ自動でループしてくれる
          while($result_select_item = $stmt_select_item->fetch(PDO::FETCH_ASSOC)){
            $form_item_view .= '<div class="radio-inline">';
            $form_item_view .= '<label class="radio-inline"><input type="radio" name="answer['.$form_element_id.'][]" value="'.$result_select_item["select_item_label"].'" disabled>'.$result_select_item["select_item_label"].'</label>';
            $form_item_view .= '</div>';
        }
   }

 }elseif($result_form_item["form_type"] == "checkbox"){//checkboxの場合
   $form_item_view .= '<p class="question_text text-left" for="answer['.$form_element_id.']">'.$result_form_item["question"].'</p>';
   $form_item_view .= '<input type="hidden" name="question['.$form_element_id.']" value="'.$result_form_item["question"].'">';
   $stmt_select_item = $pdo->prepare("SELECT * FROM form_item INNER JOIN select_item ON form_item.form_item_id = select_item.form_item_id WHERE form_item.form_id = :form_id AND form_item.form_item_id = :form_item_id");
   $stmt_select_item->bindValue(':form_id', $form_id, PDO::PARAM_INT);
   $stmt_select_item->bindValue(':form_item_id', $result_form_item["form_item_id"], PDO::PARAM_INT);
   $statu_select_item = $stmt_select_item->execute();
     if($statu_select_item==false){
       //execute（SQL実行時にエラーがある場合）
       $error = $stmt_select_item->errorInfo();
       exit("ErrorQuery_select_item:".$error[2]);
     }else{
       //Selectデータの数だけ自動でループしてくれる
       while($result_select_item = $stmt_select_item->fetch(PDO::FETCH_ASSOC)){
         $form_item_view .= '<div class="checkbox-inline">';
         $form_item_view .= '<label class="checkbox-inline"><input type="checkbox" name="answer['.$form_element_id.'][]" value="'.$result_select_item["select_item_label"].'" disabled>'.$result_select_item["select_item_label"].'</label>';
         $form_item_view .= '</div>';
     }
   }

 }
    $form_item_view .= '</div>';//form-contorl
 }
 }

 $stmt_interviewee = $pdo->prepare("SELECT * FROM interviewee_info WHERE id=:interviewee_id");
 $stmt_interviewee->bindValue(':interviewee_id', $_SESSION["interviewee_id"], PDO::PARAM_INT);
 $status_interviewee = $stmt_interviewee->execute();

 //３．データ表示
 if($status_interviewee==false){
   //execute（SQL実行時にエラーがある場合）
   $error = $stmt_interviewee->errorInfo();
   exit("ErrorQuery_interviewee:".$error[2]);
 }else{
   $res_interviewee = $stmt_interviewee->fetch();
 }

?>

<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>interview_setting</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
<style>
.send_content{
  /*background:#aaa;*/
  border: solid 1px #ddd;
}
.mb-20{
  margin-bottom:20px;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">フリーアンケート送信</h3>
<h4 class="text-center mb-20">送信内容確認</h3>
<div class="container">
  <div class="row mb-20">
    <div class="col-sm-1"></div>
    <div class="col-sm-10 send_content">
      <h5><?= $res_interviewee["interviewee_name"]?>様</h5>
      <p class="mb-20"><?= h($anchet_message); ?></p>
      <h5 class="text-center mb-20">返信期限：<span style="color:red;"><?= h($deadline); ?></span></h5>
      <form class="form-horizontal" method="post" action="test_input_data_insert.php">
        <?= $form_item_view ?>
      <div class="text-center">
        <input type="submit" class="btn btn-info" value="送信" disabled>
      </div>
      </form>
    </div>
    <div class="col-sm-1"></div>
  </div>
  <form method="post" action="questionnaire_insert.php">
    <input type="hidden" name="anchet_message" value="<?= h($anchet_message); ?>">
    <input type="hidden" name="deadline" value="<?= h($deadline); ?>">
  <div class="text-center mb-20">
    <input type="submit" class="btn btn-info btn-lg" value="候補者へ送信">
  </div>
  </form>
</div>

<?php include("../template/footer.html") ?>

</body>
</html>
