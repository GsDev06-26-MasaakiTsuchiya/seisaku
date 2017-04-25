<?php
session_start();
include("../function/function.php");
login_check();

$_SESSION["interviewee_id"] = $_GET["target_interviewee_id"];

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt_interviewee = $pdo->prepare("SELECT * FROM interviewee_info INNER JOIN job_post ON interviewee_info.job_post_id = job_post.id WHERE interviewee_info.id=:interviewee_id");
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


//アンケートフォーム形式一覧
$stmt_form = $pdo->prepare("SELECT * FROM form");
$status_form = $stmt_form->execute();

$view_form_item = "";
if($status_form==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt_form->errorInfo();
  exit("ErrorQuery_form:".$error[2]);
}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result_form = $stmt_form->fetch(PDO::FETCH_ASSOC)){
    $view_form_item .= '<tr>';
    $view_form_item .= '<td>';
    $view_form_item .= '<input type="radio" name="form_id" value="'.$result_form["form_id"].'">';
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    $view_form_item .= $result_form["form_name"];
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    $view_form_item .= $result_form["form_description"];
    $view_form_item .= '</td>';
    $view_form_item .= '<td>';
    $view_form_item .= '<a type="button" href="../questionnaire/show.php?form_id='.$result_form["form_id"].' "class="btn btn-sm btn-primary">確認</a>';
    $view_form_item .= '</td>';
    $view_form_item .= '</tr>';
  }
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
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">フリーアンケート送信</h3>
<h4 class="text-center">アンケート選択</h3>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="target">
        <h4 class="text-center">対象:<?= $res_interviewee["interviewee_name"] ?></h4>
        <h5 class="text-center">職種:<?= $res_interviewee["job_title"] ?></h5>
      </div>
      <div class="anchet_items">
        <p class="text-center">送信するアンケート形式を選んでください。</div>
        <form class="form" method="post" action="questionnaire_setting02.php">
        <table class="table">
          <?=$view_form_item?>
        </table>
        <div class="text-center">
          <input type="submit" class="btn btn-info" value="次へ">
        <form>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<?php include("../template/footer.html") ?>

</body>
</html>
