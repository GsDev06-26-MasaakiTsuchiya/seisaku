<?php

session_start();
include("../function/function.php");
$_SESSION["interviewee_id"] = "";
$_SESSION["interviewee_id"] = $_GET["interviewee_id"];

$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT interviewee_name FROM interviewee_info WHERE id =:interviewee_id");
$stmt->bindValue(':interviewee_id',$_SESSION["interviewee_id"],PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);
}else{
  $res_interviewee_info = $stmt->fetch();
}

//interview 情報の出力
$stmt = $pdo->prepare("SELECT interview.id,interview.interview_type,interview.interview_date_time FROM interview INNER JOIN interviewee_info ON interviewee_info.id = interview.interviewee_id WHERE interview.stage_flg = :stage_flg AND interviewee_info.id = :interviewee_id ");
$stmt->bindValue(':interviewee_id',$_SESSION["interviewee_id"],PDO::PARAM_INT);
$stmt->bindValue(':stage_flg',3,PDO::PARAM_INT);//3=面接日程確定

$status_interview = $stmt->execute();

$view_interview ="";
if($status_interview==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view_interview .= '<div class="row interview_info">';
    $view_interview .= '<div class="col-sm-1"></div>';
    $view_interview .= '<div class="col-sm-5">';
        $interview_type_str = interview_type($result["interview_type"]);
    $view_interview .= '<h2 class="text-center">'.$interview_type_str.'</h2>';
    $view_interview .= '<div class="text-center"><span class="interview_info">'.$result["interview_date_time"].'開始</span></div>';
    $view_interview .= '<p>面接日時の5分前には下記ボタンから面接画面にアクセスして準備を完了していただきますようお願い致します。</p>';
    $view_interview .= '<div class="text-center"><ul class="list-inline list-unstyled"><li><a href="../result/web_interview.php?interview_id='.$result["id"].'&interviewee_id='.$_SESSION["interviewee_id"].'" class="btn btn-info"><span class="glyphicon glyphicon-facetime-video"></span> 面接画面へ</a></li><li><a class="btn btn-default" href="#">キャンセル</a><li></ul></div>';
    $view_interview .= '</div>';
    $view_interview .= '<div class="col-sm-5">';//面接者情報
    $view_interview .= '<h2 class="text-center"></h2>';
    $view_interview .= '<h3 class="text-center">面接担当者</h3>';
    $view_interview .= '<p>以下の者が当日はお話させていただきます。よろしくお願いいたします。プロフィールをご覧いただけるようになっておりますので、よろしければ事前にご確認ください。</p>';
    $view_interview .= '<div class="row">';
      $stmt_interviewer = $pdo->prepare("SELECT * FROM interviewer_list INNER JOIN interviewer_info ON interviewer_list.interviewer_id = interviewer_info.id WHERE interviewer_list.interview_id = :interview_id");
      $stmt_interviewer->bindValue(':interview_id',$result["id"],PDO::PARAM_INT);
      $status_interviewer = $stmt_interviewer->execute();
      if($status_interviewer==false){
        //execute（SQL実行時にエラーがある場合）
        $error = $stmt_interviewer->errorInfo();
        exit("ErrorQuery_interviewer:".$error[2]);
      }else{
        //Selectデータの数だけ自動でループしてくれる
        while( $result_interviewer = $stmt_interviewer->fetch(PDO::FETCH_ASSOC)){
            $view_interview .= '<div class="col-sm-6 interviewer_item">';
            $view_interview .= '<div class="text-center"><img class="interviewer_img img-responsive img-circle center-block" src="'.$result_interviewer["interviewer_img"].'" alt=""></div>';
            // // $view_interview .= '<dl class="dl-horizontal">';
            // $view_interview .= '<dt>名前</dt><dd>'.$result_interviewer["interviewer_name"].'</dd>';
            // $view_interview .= '<dt>部署</dt><dd>'.$result_interviewer["department"].'</dd>';
            // $view_interview .= '<dt>役職</dt><dd>'.$result_interviewer["title"].'</dd>';
            // $view_interview .= '</dl>';
            $view_interview .= '<div class="text-center">';
            $view_interview .= '<div>名前:'.$result_interviewer["interviewer_name"].'</div>';
            $view_interview .= '<div>部署:'.$result_interviewer["department"].'</div>';
            $view_interview .= '<div>役職:'.$result_interviewer["title"].'</div>';
            $view_interview .= '</div>';
            // $view_interview .= '<div class="text-center"><a href="interviewer_show.php?interviewer_id='.$result_interviewer["interviewer_id"].'" class="btn btn-default btn-sm">プロフィール詳細</a></div>';

            $view_interview .= '<div class="text-center"><a data-toggle="modal" href="interviewer_show0.php?interviewer_id='.$result_interviewer["interviewer_id"].'" data-target="#remoteModal" class="remoteModallink btn btn-default btn-sm">プロフィール詳細</a></div>';
            $view_interview .= '</div>';
          }
      }
    $view_interview .= '</div>';//row
    $view_interview .= '</div>';//面接者情報
    $view_interview .= '<div class="col-sm-1"></div>';
    $view_interview .= '</div>';//row
  }
}

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
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>interview_rader_chart > input</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<script src="https://use.fontawesome.com/16c63c33a4.js"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
<style>
body {
  padding-top: 50px;
}
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
<?php include("../template/nav_for_interviewee_index.php") ?>
<div class="container-fruid">
<h1 class="text-center"><?= $res_interviewee_info["interviewee_name"]?>様</h1>
<h2></h2>
</div>

<div class="container interview_info_all">
  <h2 class="text-center title" id="interview_item">面接情報</h2>
  <?= $view_interview ?>
</div>

<div class="modal fade" id="remoteModal" tabindex="-1" role="dialog" aria-labelledby="remoteModalLabel" aria-hidden="true" data-show="true" data-keyboard="false" data-backdrop="static">
   <div class="modal-dialog">
     <div class="modal-content">
     </div> <!-- /.modal-content -->
   </div> <!-- /.modal-dialog -->
</div> <!-- /.modal -->

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
  <!-- <div class="row">
    <h3 class="text-center item_title">参考資料</h3>
    <div class="col-sm-1"></div>
    <div class="col-sm-10"><object data="<?php if($res_corp_info["up_pdf"]){echo $res_corp_info["up_pdf"]; }?>" type="application/pdf" width="100%" height="auto"></object></div>
    <div class="col-sm-1"></div>
  </div> -->
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



<?php include("../template/footer_for_interviewee.html") ?>
<script>

  $('#remoteModal').on('hidden.bs.modal',function(){
      $(this).removeData('bs.modal');
  });

</script>
</body>
</html>
