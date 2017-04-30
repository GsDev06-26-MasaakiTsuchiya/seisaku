<?php

function h($str){
    $str = htmlspecialchars($str,ENT_QUOTES);
    return $str;//関数の外に出す
}
//login されているかの確認のチェック
function login_check(){
  if(!isset($_SESSION["chk_ssid"])||($_SESSION["chk_ssid"])!=session_id()){
    echo "Login error";
    header("Location: /10/my_kaday/login_out/login.php");
    exit();
    }else{
      session_regenerate_id(true);
      $_SESSION["chk_ssid"] = session_id();
    }
}

//kanri_flg checker

function kanri_check(){
  if(!isset($_SESSION["kanri_flg"])||$_SESSION["kanri_flg"] == 0){
    echo "You are not authorized to access this page";
    header("Location: ../top/index.php");
  }
}
//dbに接続
function db_con(){
  $dbname='gs_db';
  try {
    $pdo = new PDO('mysql:dbname='.$dbname.';charset=utf8;host=localhost','root','');
  } catch (PDOException $e) {
    exit('DbConnectError:'.$e->getMessage());
  }
  return $pdo;
}

//SQL処理エラー
function queryError($stmt){
  //SQL実行時にエラーがある場合（エラーオブジェクト取得して表示）
  $error = $stmt->errorInfo();
  exit("QueryError:".$error[2]);
}

function inteview_type($interview_type_num){
$interview_type = array("書類選考","1次面接","2次面接","3次面接");
return $interview_type[$interview_type_num];
}
function skyway_key(){
  return “****************************”;
}

function url_folder_name_remove($spacer,$url){
  $url_array = explode($spacer,$url);
  $url_array[1] = "..".$url_array[1];
  return $url_array[1];
}


//

?>
