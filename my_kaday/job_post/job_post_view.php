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


$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
<script src="../ckeditor/ckeditor.js"></script>
<style>

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
