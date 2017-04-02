<?php

include("../function/function.php");
$job_post_id = $_GET["job_post_id"];
$pdo = db_con();

$stmt = $pdo->prepare("SELECT * FROM job_post where id=:id");
$stmt->bindValue(':id',$job_post_id,PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  queryError($stmt);
}else{
  $res = $stmt->fetch();
}


?>


<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>interview_rader_chart > input</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<link rel="stylesheet" href="../css/common.css">
<style>
body{
  word-wrap:break-word;
  }
  .job_title{
    font-size:3em;
  }
  .item{
    font-size:1.2em;
  }
  hr {
    width:100%;
    margin-bottom:30px;
  }
  .row{
    margin-bottom:30px;
  }
</style>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-sm-1"></div>
      <div class="col-sm-10">
        <div class="container">
          <h3 class="job_title"><?=$res["job_title"] ?></h3>
        </div>
        <div class="text-center">
          <hr>
        </div>
        <div class="container-fruid">
          <div class="row">
            <?php if($res["job_img"]){?>
            <div class="col-sm-2 item">募集要項:</div>
            <div class="col-sm-5"><?=$res["job_description"]?></div>
            <div class="col-sm-5"><img class="img-responsive" src="<?=$res["job_img"]?>" alt=""></div>
            <?php }else{ ?>
            <div class="col-sm-2 item">募集要項:</div>
            <div class="col-sm-10"><?=$res["job_description"]?></div>
            <?php } ?>
          </div>
          <div class="row">
            <div class="col-sm-2 item">募集要件:</div>
            <div class="col-sm-10"><?=$res["requirement"]?></div>
          </div>
          <div class="row">
            <div class="col-sm-2 item">給与制度:</div>
            <div class="col-sm-10"><?=$res["salary_sys"]?></div>
          </div>
          <div class="row">
            <div class="col-sm-2 item">福利厚生:</div>
            <div class="col-sm-10"><?=$res["welfare"]?></div>
          </div>
          <div class="row">
            <div class="col-sm-2 item">勤務地:</div>
            <div class="col-sm-10"><?=$res["location"]?></div>
          </div>
          <div class="row">
            <div class="col-sm-2 item">勤務時間:</div>
            <div class="col-sm-10"><?=$res["work_hour"]?></div>
          </div>
          <div class="row">
            <div class="col-sm-2 item">備考:</div>
            <div class="col-sm-10"><?=$res["etc"]?></div>
          </div>
        </div>
          <div class="text-center">
            <hr>
            <a class="btn btn-warning" href="job_post_view_form.php?job_post_id=<?=$res["id"]?>&job_title=<?=$res["job_title"]?>">この職種に応募する</a>
          </div>
      </div>
      <div class="col-sm-1"></div>
    </div>
  </div>

</body>
</html>
