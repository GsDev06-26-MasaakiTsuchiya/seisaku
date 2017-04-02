<?php
session_start();
include("../function/function.php");
login_check();

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM interviewer_info");
$status = $stmt->execute();

//３．データ表示
$view="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    // $kanri_flg_str = "";
    // if(h($result["kanri_flg"]==0){
    //   $kanri_flg_str = "一般者";
    // }elseif(h($result["kanri_flg"]==1){
    //   $kanri_flg_str = "管理者";
    // }
    $view .='<tr>';
    $view .='<td><span class="glyphicon glyphicon-user"><span></td>';
    $view .='<td>';
    $view .=h($result["interviewer_name"]);
    $view .='</td><td>'.h($result["lid"]).'</td>';
    // $view .='<td>'.$kanri_flg_str.'</td>';
    (h($result["kanri_flg"])==0) ? ($view .= '<td>'.'一般者'.'</td>'):($view .= '<td>'.'管理者'.'</td>');
    (h($result["life_flg"])==0) ? ($view .= '<td>'.'使用中'.'</td>'):($view .= '<td>'.'停止中'.'</td>');
    $view .='<td><a class="btn btn-xs btn-info" href="interviewer_detail.php?id='.h($result["id"]).'">更新</a> <a class="btn btn-xs btn-danger" href="interviewer_delete.php?id='.h($result["id"]).'">削除</a></td>';
    // $view .='<td><a class="btn btn-xs btn-info" href="interviewer_detail.php?id='.h($result["id"]).'">更新</a></td>';
    $view .='</tr>';
  }
}
?>


<html lang="ja">
<head>
<meta charset="utf-8">
<title>interview_rader_chart > input</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
<style>

html,body{
  height: 100%;
}
.container{
  margin-bottom:20px;
}



</style>
</head>
<body>
<?php include("../template/nav.php") ?>
<h3 class="text-center">面接者管理</h3>
<div class="container">
<div class="row">
  <div class="col-sm-offset-9 col-sm-2 text-center"><a class="btn btn-sm btn-default" href="interviewer_setting.php">新規登録</a></div>
  </div>
</div>
<div class="container">
  <table class="table table-hover">
    <?=$view?>
  </table>
</div>
<?php include("../template/footer.html") ?>
</script>
</body>
</html>
