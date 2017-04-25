<?php
session_start();
include("../function/function.php");
login_check();
//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成 該当の候補者情報の抽出
$stmt_form = $pdo->prepare("SELECT * FROM form");
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
    $view_form_item .= '<a type="button" href="show.php?form_id='.$result_form["form_id"].'"class="btn btn-sm btn-primary">確認・修正</a> <button class="btn btn-sm btn-danger" disabled>削除</button>';
    $view_form_item .= '</td>';
    $view_form_item .= '</tr>';
  }
}

?>


<html lang="ja">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>interview_rader_chart > input</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
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
