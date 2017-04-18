<?php
include("../function/function.php");

$pdo = db_con();


$stmt = $pdo->prepare("SELECT * FROM corp_apply");
$status = $stmt->execute();
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  queryError($stmt);
}else{
  $res = $stmt->fetch();
}

//job_post表示
$stmt = $pdo->prepare("SELECT * FROM job_post where life_flg = 0 ORDER BY indate DESC limit 5");
$status = $stmt->execute();

//３．データ表示
$view2="";
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    // var_dump($result);
    // $view .='<a href="../job_post/job_post_view.php?job_post_id='.$result["id"].'">';
    // $view .='<tr>';
    // $view .='<td><i class="fa fa-id-badge" aria-hidden="true"></i></td>';
    // $view .='<td><a href="../job_post/job_post_view.php?job_post_id='.$result["id"].'">'.$result["job_title"].'</a></td>';
    // $jd_text_of_head = substr($result["job_description"],0,100);
    // $view .= '<td>'.$jd_text_of_head.'</td>';
    // $view .= '<td>'.$result["indate"].'</td>';
    // $view .='</tr>';
    $view2 .= '<div class="panel panel-default">';
    $view2 .= '<div class="panel-heading" role="tab" id="heading_'.$result["id"].'">';
    $view2 .= '<h4 class="panel-title">';
    $view2 .= '<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_'.$result["id"].'" aria-expanded="false" aria-controls="collapse_'.$result["id"].'">';
    $view2 .= $result["job_title"];
    $view2 .= '</a>';
    $view2 .= '</h4>';
    $view2 .= '</div>';
    $view2 .= '<div id="collapse_'.$result["id"].'" class="panel-collapse" role="tabpanel" aria-labelledby="heading_'.$result["id"].'">';
    $view2 .= '<div class="panel-body">';
    $view2 .= $result["job_description"];
    $view2 .= '<div class="text-right"><a href="../job_post/job_post_view.php?job_post_id='.$result["id"].'" class="btn btn-sm btn-info">詳細</a></div>';
    $view2 .= '</div>';
    $view2 .= '</div>';
    $view2 .= '</div>';
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
 <script src="https://use.fontawesome.com/16c63c33a4.js"></script>
 <script src="../js/l-by-l.min.js"></script>
 <link rel="stylesheet" href="../css/common.css">
 <style>
 html,body{
   height: 100%;
 }

 .carousel{
    width:100%;  /*サイズ指定*/
    margin:auto;
 }
 .carousel img{
    width:100%;
 }
 #main{
   background-image: url("<?=h($res["main_photo"])?>");
        background-size: cover;
        background-position:center center;
        height:100%;
        color:#fff;
        padding-top:200px;
        margin-bottom:50px;
        margin-top:-20px;

}
#main h1{
  text-shadow: 3px 3px 3px #999;
}
#main p{
  text-shadow: 3px 3px 3px #999;
}
#list{
  margin-bottom:40px;
}

 </style>
 </head>
 <body>
<?php include("./template/apply_nav.php"); ?>
 <div class="container-fluid" id="main">
   <h1 class="text-center"><?=$res["main_title_text"]?></h1>
     <div class="text-center">
       <?=$res["main_lead_text"]?>
     </div>
 </div>
   <!-- <div class="container-fluid">
     <div class="row">
       <div id="carousel-example-generic" class="carousel slide" data-ride="carousel"> -->
      <!-- Indicators -->
      <!-- <ol class="carousel-indicators">
        <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
        <li data-target="#carousel-example-generic" data-slide-to="1"></li>
        <li data-target="#carousel-example-generic" data-slide-to="2"></li>
      </ol> -->

      <!-- Wrapper for slides -->
      <!-- <div class="carousel-inner" role="listbox">
        <div class="item active">
          <img src="img/01.jpg" alt="photo1">
          <div class="carousel-caption">
            写真1
          </div>
        </div>
        <div class="item">
          <img src="img/02.jpg" alt="photo2">
          <div class="carousel-caption">
            写真2
          </div>
        </div>
        <div class="item">
          <img src="img/03.jpg" alt="photo3">
          <div class="carousel-caption">
            写真3
          </div>
        </div>
        ...
      </div> -->

      <!-- Controls -->
      <!-- <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
  </div>
</div> -->
<h2 class="text-center text-info" id="list">現在募集中のポジション</h2>
<!-- <div class="container-fluid">
  <div class="row">
    <div class="col-sm-2"></div>
    <div class="col-sm-8">
       <table class="table table-responsive">

       </table>
    </div>
    <div class="col-sm-2"></div>
  </div>
 </div> -->
 <div class="container-fluid">
   <div class="row">
     <div class="col-sm-2"></div>
     <div class="col-sm-8">
 <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
<?=$view2?>
</div>
</div>
     <div class="col-sm-2"></div>
</div>
</div>
<?php include("./template/apply_footer.php"); ?>
 </body>
 <script>
 // $(function(){
 //   $('.carousel').lbyl({
 //    content: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Fusce iaculis a quam a pellentesque. Proin maximus, nulla non molestie scelerisque, ligula purus lacinia massa, et dapibus quam mi at mi.",
 //    speed: 10, //time between each new letter being added
 //    type: 'show', // 'show' or 'fade'
 //    fadeSpeed: 500, // Only relevant when the 'type' is set to 'fade'
 //    finished: function(){ console.log('finished') } // Finished Callback
 //    });
 //
 // });
 </script>
</html>
