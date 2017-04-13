<?php

session_start();
include("../function/function.php");
login_check();
kanri_check();

$pdo = db_con();

$stmt = $pdo->prepare("SELECT * FROM corp_info");
$status_corp_info = $stmt->execute();

//３．データ表示
if($status_corp_info==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery_corp_info:".$error[2]);
}else{
  $res_corp_info = $stmt->fetch();
}

$pdf_url_for_iframe = url_folder_name_remove("pdfjs",$res_corp_info["up_pdf"]);


?>

<html lang="ja">
<head>
<meta charset="utf-8">
<title>interview_rader_chart > input</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/16c63c33a4.js"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
<style>
span.interview_info{
  font-size:2.5em;
}
.interviewer_img{
  height: 120px;
}
div.interview_info{
  background:#fff;
  margin-bottom:30px;
  padding-top:20px;
  padding-bottom:20px;
}
.interviewer_item{
  padding-top:10px;
  padding-bottom:10px;
}
.video-responsive{
  max-width: 100%;
  height: auto;
}
.content_item{
  margin-bottom: 30px;
}
.content_sub_item{
    margin-bottom: 15px;
}
h2.title{
  margin-bottom:30px;
}
h3.item_title{
  margin-bottom:50px;
}
div.item_s{
  margin-bottom:15px;
}
</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<div class="container interview_info_all" style="display:none;">
  <h2 class="text-center title" id="interview_item">面接情報</h2>
</div>


<div class="container-fruid">
  <div class="row content_item">
    <h2 class="text-center title" id="info_top">会社情報</h2>
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="row content_sub_item">
        <div class="col-sm-5"></div>
        <div class="col-sm-2 text-center"><img class="img-responsive center-block" src="<?=$res_corp_info["catch_photo"]?>" alt=""></div>
        <div class="col-sm-5"></div>
      </div>
      <div class="row content_sub_item">
        <div class="col-sm-5"></div>
        <div class="col-sm-2 text-center"><a class="btn btn-success btn-sm" href="<?=$res_corp_info["corp_url"]?>" target="_blank">corprate site</a></div>
        <div class="col-sm-5"></div>
      </div>
      <div class="row content_sub_item">
        <div class="col-sm-3"></div>
        <div class="col-sm-6"><?=$res_corp_info["info_text"]?></div>
        <div class="col-sm-3"></div>
      </div>
    </div>
    <div class="col-sm-1"></div>
  </div>

  <div class="row content_item" id="reference_material">
    <h3 class="text-center item_title">参考資料</h3>
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
      <iframe src="http://localhost/10/my_kaday/pdfjs/web/viewer.html?file=<?php if($pdf_url_for_iframe){echo $pdf_url_for_iframe; }?>" width="100%" height="100%" style="border: none;"></iframe>
      </div>
    <div class="col-sm-3"></div>
  </div>
  <div class="row content_item" id="company_video">
    <h3 class="text-center item_title">紹介動画</h3>
    <div class="col-sm-3"></div>
    <div class="col-sm-6">
      <video class="video-responsive" src="<?php if($res_corp_info["company_video"]){echo $res_corp_info["company_video"]; }?>" alt="" preload="auto" onclick="this.play()" controls>
    </div>
    <div class="col-sm-3"></div>
  </div>

  <div class="row content_item" id="access">
    <h3 class="text-center item_title">アクセス</h3>
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
      <div class="text-center item_s"><?=$res_corp_info["address"]?></div>
      <div class="text-center item_s"><?=$res_corp_info["tel"]?></div>
      <div class="text-center item_s"><a class="btn btn-success btn-sm" href="<?=$res_corp_info["corp_url"]?>" target="_blank">corprate site</a></div>
    </div>
    <div class="col-sm-1"></div>
  </div>

</div>

<div class="text-center content_item">
<a href="detail.php" class="btn btn-default btn-lg">編集する</a>
</div>



<?php include("../template/footer.html") ?>

</body>
</html>