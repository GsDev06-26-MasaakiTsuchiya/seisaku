<?php

session_start();
include("../function/function.php");
login_check();

$html_title = '無料から使えるクラウド採用管理、面接システム Smart Interview';
?>
<!DOCTYPE html>
<html>
<head>
<?php include("../template/head.php") ?>
<style>
</style>
</head>
<body>
  <?php include("../template/nav.php") ?>
<h1>top</h1>
  <?php include("../template/footer.html") ?>
</body>
</html>
