<?php
session_start();
include("../function/function.php");

login_check();

//1.  DB接続します
$pdo = db_con();

//２．データ登録SQL作成
$stmt = $pdo->prepare("SELECT interviewee_info.id,interviewee_info.interviewee_name,interviewee_info.interviewee_name_kana,interviewee_info.indate,job_post.job_title FROM interviewee_info,job_post WHERE interviewee_info.job_post_id = job_post.id");
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
    $view .='<tr>';
    $view .='<td><span class="glyphicon glyphicon-user"><span>';
    $view .='<td><ul class="list-unstyled">';
    $view .='<li class="">'.h($result["interviewee_name"]).'</li>';
    $view .='<li class="isc">'.h($result["interviewee_name_kana"]).'</li>';
    $view .='</ul></td>';
    $view .='<td><ul class="list-unstyled">';
    $view .='<li class="">'.h($result["job_title"]).'</li>';
    $view .='<li class="isc">自社サイト</li>';
    $view .='<li class="isc">中途</li>';
    $view .='<li class="isc">'.h($result["indate"]).'</li>';
    $view .='</ul></td>';
    $view .='<td><a class="btn btn-xs btn-default" href="#">未送信</a></td>';
    $view .='<td><ul class="list-unstyled">';//書類選考
    $view .='<li class="isc">2017-01-12</li>';
    $view .='<li class="isc">2017-01-20</li>';
    $view .='<li class="isc">通過</li>';
    $view .='</ul></td>';
      $stmt2 = $pdo->prepare("SELECT * FROM interview WHERE interviewee_id = :interviewee_id AND interview_type = :interview_type");
      $stmt2->bindValue(':interviewee_id', $result["id"], PDO::PARAM_INT);
      $stmt2->bindValue(':interview_type', 1, PDO::PARAM_INT);

      $status2 = $stmt2->execute();
      if($status==false){
        //execute（SQL実行時にエラーがある場合）
        $error2 = $stmt2->errorInfo();
        exit("ErrorQuery:".$error2[2]);
      }else{
        $res = $stmt2->fetch();
      }
    $view .='<td><ul class="list-unstyled">';//1次面接
    if(!$res["stage_flg"]||$res["stage_flg"] ==0){
    $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview01_setting.php?interview_type_num=1&target_interviewee_id='.h($result["id"]).'">未設定（日程）</a></li>';//日程調整
    }elseif($res["stage_flg"] ==1){
      $view .='<li class="isc"><a class="btn btn-xs btn-default" href="#">日程候補送信済</a></li>';//日程調整
      $view .='<li class="isc"><a class="btn btn-xs btn-default" href="../forinterviewee/interview_date_time_select01.php?interview_id='.$res["id"].'">仮）候補者確認画面</a></li>';//日程調整

    }elseif($res["stage_flg"] ==2){
      $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview_confirm01.php?interview_id='.$res["id"].'">要日程確定</a></li>';//日程調整
    }elseif($res["stage_flg"] ==3){
      $view .='<li class="isc"><a class="btn btn-xs btn-success" href="interview_confirm01.php?interview_id='.h($res["id"]).'&stage_flg=3">日程確定</a></li>';
      $view .='<li class="isc">'.$res["interview_date_time"].'</li>';
      $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="../result/output_data.php?interview_id='.h($res["id"]).'">結果入力</a></li>';
    }elseif($res["stage_flg"] ==4){
          $view .='<li class="isc">面接:'.$res["interview_date_time"].'</li>';//日程調整
          $view .='<li class="isc">通過:'.$res["fix_time"].'</li>';//日程調整
          $view .='<li class="isc"><a class="btn btn-xs btn-default" href="../result/output_data.php?interview_id='.h($res["id"]).'">結果変更</a></li>';//日程調整
    }elseif($res["stage_flg"] ==5){
          $view .='<li class="isc">'.$res["interview_date_time"].'</li>';//日程調整
          $view .='<li class="isc">不合格:'.$res["fix_time"].'</li>';//日程調整
          $view .='<li class="isc"><a class="btn btn-xs btn-default" href="../result/output_data.php?interview_id='.h($res["id"]).'">結果変更</a></li>';//日程調整
    }elseif($res["stage_flg"] ==6){
      $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview_resetting.php?interview_id='.h($res["id"]).'">日程再調整</a></li>';//通過
    }
    $view .='</ul></td>';
    $view .='</ul></td>';
    $view .='<td><ul class="list-unstyled">';//2次面接
     if($res["stage_flg"] ==4){
    $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview01_setting.php?interview_type_num=2&target_interviewee_id='.h($result["id"]).'">未設定（日程）</a></li>';//日程調整
    $view .='<li class="isc">-（結果）</li>';
    $view .='<li class="isc">-（通過/不可　日付)</li>';
    $view .='</ul></td>';
    $view .='</ul></td>';
    }
    $view .='<td><ul class="list-unstyled">';//3次面接
    // $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview01_setting.php?interview_type_num=3&target_interviewee_id='.h($result["id"]).'">未設定（日程）</a></li>';//日程調整
    // $view .='<li class="isc">-（結果）</li>';
    // $view .='<li class="isc">-（通過/不可　日付)</li>';
    $view .='</ul></td>';
    $view .='<td><ul class="list-unstyled">';//オファー
    // $view .='<li class="isc"><a class="btn btn-xs btn-warning" href="interview_detail_select.php?target_interviewee_id='.h($result["id"]).'">未設定（日程）</a></li>';//日程調整
    // $view .='<li class="isc">-（結果）</li>';
    // $view .='<li class="isc">-（通過/不可　日付)</li>';
    $view .='</ul></td>';


    // $view .='<td><a href="input_data.php?target_inteviewee='.h($result["id"]).'" class="btn btn-xs btn-info">評価入力</a>&nbsp;<a href="output_data.php?target_inteviewee='.h($result["id"]).'" class="btn btn-xs btn-primary">評価閲覧</a></td>';
    // $view .='<td><a href="interview_detail_select.php?target_interviewee_id='.h($result["id"]).'" class="btn btn-xs btn-info">選考設定</a>&nbsp;<a href="interviewee_detail.php?target_interviewee_id='.h($result["id"]).'&target_interviewee_name='.h($result["interviewee_name"]).'" class="btn btn-xs btn-primary">情報更新</a>&nbsp;<a href="interviewee_delete.php?target_interviewee_id='.h($result["id"]).'&target_interviewee_name='.h($result["interviewee_name"]).'" class="btn btn-xs btn-danger">削除</a></td>';
    $view .='<td class="text-center"><p><a href="interviewee_detail.php?target_interviewee_id='.h($result["id"]).'&target_interviewee_name='.h($result["interviewee_name"]).'" class="btn btn-xs btn-primary">情報更新</a></p>';
    $view .='<p><a href="interviewee_delete.php?target_interviewee_id='.h($result["id"]).'&target_interviewee_name='.h($result["interviewee_name"]).'" class="btn btn-xs btn-danger">削除</a></p></td>';

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
li.isc{
  font-size:0.7em;

}
span.isc{
    font-size:0.7em;
}


</style>
</head>
<body>
<?php include("../template/nav.php") ?>

<h3 class="text-center">候補者管理</h3>
<div class="container">
<div class="row">
  <div class="col-sm-offset-9 col-sm-2 text-center"><a class="btn btn-sm btn-default" href="interviewee_setting.php">新規登録</a></div>
  </div>
</div>




<div class="container-fruid">
  <table class="table table-hover table-bordered">
    <tr>
      <th></th>
      <th>名前 カナ</th>
      <th>ポジション</th>
      <th>アンケートフォーム</th>
      <th>書類選考</th>
      <th>一次面接</th>
      <th>二次面接</th>
      <th>最終面接</th>
      <th>オファー</th>
      <th></th>
    </tr>
    <?=$view?>
  </table>
</div>
<?php include("../template/footer.html") ?>
<!-- <script>
  $(function(){
      $('#to_output_data').click(function() {
          $('#form').attr('action', 'output_data.php');
          $('#form').submit();
      });
      $('#to_input_data').click(function() {
          $('#form').attr('action', 'input_data.php');
          $('#form').submit();
      });
  }); -->
</script>
</body>
</html>
