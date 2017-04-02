<?php
session_start();
include("../function/function.php");
login_check();
// $interviewee_id = $_POST["target_inteviewee"];
$interview_id = $_GET["interview_id"];
//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT * FROM  interview_result,interviewer_info WHERE interview_result.interview_id = :interview_id AND interview_result.interviewer_id = interviewer_info.id");
$stmt->bindValue(':interview_id',$interview_id, PDO::PARAM_INT);
$status = $stmt->execute();

//３．データ表示
$view="";
$data_s = [];
if($status==false){
  //execute（SQL実行時にエラーがある場合）
  $error = $stmt->errorInfo();
  exit("ErrorQuery:".$error[2]);

}else{
  //Selectデータの数だけ自動でループしてくれる
  while( $result = $stmt->fetch(PDO::FETCH_ASSOC)){
    $view .= '<h3 class="text-center">'.h($result["interviewer_name"]).'</h3>';
    $view .= '<table class="table table-striped evaluation_detail" style="table-layout:fixed;width:100%;">';
    $view .= '<thead><tr><th class="text-center">評価項目</th><th class="text-center">score</th><th class="text-center">comment</th></tr></thead>';
    $view .= '<tbody>';
    $view .= '<tr><td class="text-center">能力・スキル</td><td class="point text-center">'.h($result["score_0"]).'</td><td class="comment">'.h($result["qualitative_0"]).'</td></tr>';
    $view .= '<tr><td class="text-center">協調性</td><td class="point text-center">'.h($result["score_1"]).'</td><td class="comment">'.h($result["qualitative_1"]).'</td></tr>';
    $view .= '<tr><td class="text-center">コミュニケーション能力</td><td class="point text-center">'.h($result["score_2"]).'</td><td class="comment">'.h($result["qualitative_2"]).'</td></tr>';
    $view .= '<tr><td class="text-center">積極性</td><td class="point text-center">'.h($result["score_3"]).'</td><td class="comment">'.h($result["qualitative_3"]).'</td></tr>';
    $view .= '<tr><td class="text-center">モラル・性格面</td><td class="point text-center">'.h($result["score_4"]).'</td><td class="comment">'.h($result["qualitative_4"]).'</td></tr>';
    $view .= '<tr><td class="text-center">定着度</td><td class="point text-center">'.h($result["score_5"]).'</td><td class="comment">'.h($result["qualitative_5"]).'</td></tr>';
    $view .= '<tr><td class="text-center">平均点/総評</td><td class="point text-center">1</td><td class="comment">'.h($result["comment"]).'</td></tr>';
    $view .= '</tbody>';
    $view .= '</table>';

    $data = array(h($result["score_0"]),h($result["score_1"]),h($result["score_2"]),h($result["score_3"]),h($result["score_4"]),h($result["score_5"]),h($result["interviewer_name"]));
    array_push($data_s, $data);
  }
}
$json_data_s = json_encode($data_s);
$interview_type = array("書類選考","1次面接","2次面接","3次面接");

$stmt2 = $pdo->prepare("SELECT * FROM  interview,interviewee_info WHERE interview.id = :interview_id AND interview.interviewee_id = interviewee_info.id");
$stmt2->bindValue(':interview_id',$interview_id, PDO::PARAM_INT);
$status2 = $stmt2->execute();
if($status2==false){
  //execute（SQL実行時にエラーがある場合）
  $error2 = $stmt2->errorInfo();
  exit("ErrorQuery:".$error2[2]);
}else{
  $res = $stmt2->fetch();
}

$stmt3 = $pdo->prepare("SELECT * FROM  job_post WHERE id = :job_post_id");
$stmt3->bindValue(':job_post_id',$res["job_post_id"], PDO::PARAM_INT);
$status3 = $stmt3->execute();
if($status3==false){
  //execute（SQL実行時にエラーがある場合）
  $error3 = $stmt3->errorInfo();
  exit("ErrorQuery:".$error3[2]);
}else{
  $res_job_post = $stmt3->fetch();
}

?>


<html lang="ja">
<head>
<meta charset="utf-8">
<title>interview_rader_chart > result </title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
<style type="text/css">
  body{
    background:#f8f8f8;

  }
  td.comment{
    word-wrap:break-word;
  }

  .tableItemTitle{
    width:30%;
  }
  .tableCommentTitle{
    width:60%;
  }
  .tablePointTitle{
    width:10%;
  }
.evaluation_detail{
  margin-bottom: 60px;
}
.info_name{
  margin-bottom:30px;
}

</style>
</head>
<body>
<?php include("../template/nav.php") ?>
<h3 class="text-center">評価結果</h3>
<div class="info_name">
<p class="text-center"><?= h($res["interviewee_name_kana"])?></p>
<h2 class="text-center"><?= h($res["interviewee_name"])?></h2>
<p class="text-center"><a href="web_interview.php?interview_id=<?=$interview_id?>" target="_blank">ビデオ面接</a></p>
</div>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-6">
      <canvas id="myChart" width="400" height="400"></canvas>
    </div>
    <div class="col-sm-4">
<table class="table table-striped">';
<tr><th class="text-center">誕生日</th><td class="text-center"><?= h($res["birthday"]) ?></td></tr>
<tr><th class="text-center">職種</th><td class="text-center"><?= h($res_job_post["job_title"]) ?></td></tr>
<tr><th class="text-center">ステージ</th><td class="text-center"><?= $interview_type[h($res["interview_type"])] ?></td></tr>
<tr><th class="text-center">面接日時</th><td class="text-center"><?= h($res["interview_date_time"]) ?></td></tr>
</table>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<h2 class="text-center">評価詳細</h2>
<div class="container">
  <div class="row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <?=$view?>
    </div>
    <div class="col-sm-1"></div>
  </div>
</div>
<?php include("../template/footer.html") ?>
<script>
var ctx = document.getElementById("myChart");
var data_s = JSON.parse('<?php echo $json_data_s; ?>');
console.dir(data_s);

var colorSet = [["rgba(255, 99, 132, 0.2)","rgba(255,99,132,1)"],["rgba(54, 162, 235, 0.2)","rgba(54, 162, 235, 1)"],["rgba(255, 206, 86, 0.2)","rgba(255, 206, 86, 1)"],["rgba(75, 192, 192, 0.2)","rgba(75, 192, 192, 1)"],["rgba(153, 102, 255, 0.2)","rgba(153, 102, 255, 1)"],["rgba(255, 159, 64, 0.2)","rgba(255, 159, 64, 1)"]];

var dataSets = [];
for (var i=0; i< data_s.length; i++){
  var p1 = Number(data_s[i][0]);
  var p2 = Number(data_s[i][1]);
  var p3 = Number(data_s[i][2]);
  var p4 = Number(data_s[i][3]);
  var p5 = Number(data_s[i][4]);
  var p6 = Number(data_s[i][5]);
  var points = [p1,p2,p3,p4,p5,p6];
  var obj = { label: data_s[i][6],//名前
              backgroundColor: colorSet[i][0],
              borderColor: colorSet[i][1],
              data: points //配列
            };
  dataSets.push(obj);  //配列 オブジェクトが完成したらpush
  // console.dir(points);
}
console.dir(dataSets);
var myChart = new Chart(ctx, {
  type: 'radar',
  data: {
    labels: ["能力・スキル", "協調性", "コミュニケーション", "積極性", "モラル", "定着度"],
    datasets: dataSets
    },
  options:{
      scale:{
        ticks:{
          beginAtZero: true
        }
      }

  }

});

</script>

</body>
</html>
