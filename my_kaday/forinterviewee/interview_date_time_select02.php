<?php

// /10/my_kaday/setting/interview_date_time_select01.php?interviewee_id=*&interview_id=*

session_start();
include("../function/function.php");



?>

<html lang="ja">
<head>
<meta charset="utf-8">
<title>interview_rader_chart > input</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
<script src="https://skyway.io/dist/0.3/peer.min.js"></script>
<script src="https://skyway.io/dist/multiparty.min.js"></script>

<style>

.container-fruid{
  margin-bottom:30px;
}
video {
  width:300px;
}
h4.pg{
font-size:0.9em;
}
.gray{
  color:#aaa;
}
</style>



</head>
<body>
<?php include("../template/nav_for_interviewee.php") ?>
<div class="container-fruid">
  <div class="row">
      <div class="col-xs-2 hidden-xs"></div>
      <h4 class="col-xs-2 pg text-center gray">1,規約同意</h4><h4 class="col-xs-2 pg text-center">2,動作検証</h4><h4 class="col-xs-2 pg text-center gray">3,面接日時選択</h4><h4 class=" col-xs-2 pg text-center gray">4,返信完了
      </h4>
      <div class="col-xs-2 hidden-xs"></div>
  </div>
</div>

<div class="container-fruid">
  <div class="row">
    <div class="col-xs-2 hidden-xs"></div>
    <div class="col-xs-8">
    <h3 class="text-center">動作検証</h3>
    <p>
    test
  </p>
    <div class="row" id="streams" style="display:none;">
    </div>
    <div class="col-xs-2 hidden-xs"></div>
  </div>
</div>
  <div class="container-fruid">
    <form action="interview_date_time_select03.php" method="post">
      <div class="text-center">
        <input type="checkbox" id="check" />
        <label for="check">カメラとマイクが動作して自分の画像が表示されました。</label>
      </div>
       <div class="text-center">
        <input type="submit" class="btn btn-info" id="submit" value="次へ" />
      </div>
    </form>
  </div>
<?php include("../template/footer_for_interviewee.html") ?>
<script>

  $(function() {
  	$('#submit').attr('disabled', 'disabled');

  	$('#check').click(function() {
  		if ($(this).prop('checked') == false) {
  			$('#submit').attr('disabled', 'disabled');
  		} else {
  			$('#submit').removeAttr('disabled');
  		}
  	});
  });

  // MultiParty インスタンスを生成
//   multiparty = new MultiParty( {
//     "key": "68ea836f-c243-4b68-b5c9-ee68fcf48c97", /* SkyWay keyを指定 */
//     "reliable": true
//   });
//
//   multiparty.on('my_ms', function(video) {
//   // 自分のvideoを表示
//   var vNode = MultiParty.util.createVideoNode(video);
//   vNode.volume = 0;
//
//   $(vNode).appendTo("#streams");
// }).on('peer_ms', function(video) {
//   // peerのvideoを表示
//   var vNode = MultiParty.util.createVideoNode(video);
//   $(vNode).appendTo("#streams");
// }).on('ms_close', function(peer_id) {
//   // peerが切れたら、対象のvideoノードを削除する
//   $("#"+peer_id).remove();
// });
//
//   // サーバとpeerに接続
//   multiparty.start()
//
//
// }

</script>
</body>
</html>
