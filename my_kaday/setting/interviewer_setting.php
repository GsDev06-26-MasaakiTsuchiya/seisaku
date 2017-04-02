<?php
session_start();
include("../function/function.php");
login_check();

?>

<html lang="ja">
<head>
<meta charset="utf-8">
<title>interviewer_setting</title>
<script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="../css/common.css">
</head>
<body>
<?php include("../template/nav.php") ?>
<form class="form-group form-horizontal" action="interviewer_insert.php" method="post">
  <label for="interviewer_name">名前</label><input class="form-control" type="text" name="interviewer_name" value="">
  <label for="lid">id</label><input class="form-control" type="text" name="lid" value="">
  <label for="lpw">pw</label><input class="form-control" type="text" name="lpw" value="">
  <label for="kanri_flg">管理者</label>
  <select class="form-control" name="kanri_flg">
    <option value="0">一般</option>
    <option value="1">管理者</option>
  </select>
  <label for="life_flg">使用中</label>
  <select class="form-control" name="life_flg">
    <option value="0">使用中</option>
    <option value="1">使用していない</option>
  </select>
  <div class="text-center">
    <input class="btn btn-default" type="submit" value="登録">
  </div>
</form>

<?php include("../template/footer.html") ?>

</body>
</html>
