<?php
session_start();
include("../function/function.php");
login_check();
//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成 該当の候補者情報の抽出
$stmt_form = $pdo->prepare("SELECT * FROM form WHERE life_flg=:life_flg");
$stmt_form->bindValue(':life_flg', 1, PDO::PARAM_INT);

$status_form = $stmt_form->execute();

$view_form_item = "";
//３．データ表示
if($status_form==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_form->errorInfo();
  exit("ErrorQuery_form:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_form = $stmt_form->fetch(PDO::FETCH_ASSOC)){
    $view_form_item .= '<tr>';
    $view_form_item .= '<td>';
    $view_form_item .= $result_form["form_name"];
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    $view_form_item .= $result_form["form_description"];
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    $view_form_item .= '<a type="button" href="show.php?form_id='.$result_form["form_id"].'" class="btn btn-sm btn-primary">確認・修正</a> <a type="button" class="btn btn-sm btn-danger" href="delete.php?form_id='.$result_form["form_id"].'">削除</a>';
    $view_form_item .= '</td>';
    $view_form_item .= '</tr>';
  }
}

$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
.mb-30{
  margin-bottom:30px;
}

</style>
</head>
<body>
<?php include("../template/nav.php") ?>
<div class="container">

  <h2 class="text-center">フリーアンケート一覧</h2>
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-right mb-30">
        <a class="btn btn-default" type="button" href="input.php">新規作成</a>
      </div>

      <table class="table">
        <?=$view_form_item?>
      </table>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>


<?php include("../template/footer.html") ?>
</body>
<script>

</script>
</html>
