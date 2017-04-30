<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Cabin+Condensed&text=SmartInterview">
<script src="https://use.fontawesome.com/16c63c33a4.js"></script>
<nav class="navbar navbar-default navbar-static-top">
<div class="navbar-header">
  <button class="navbar-toggle" data-toggle="collapse" data-target=".target">
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
    <span class="icon-bar"></span>
  </button>
  <a class="navbar-brand" href="#" style="font-family:'Cabin+Condensed',serif;"><i class="fa fa-id-card" aria-hidden="true"></i> Smart Interview</a>
</div>

<!-- <div class="collapse navbar-collapse target">
  <ul class="nav navbar-nav navbar-right">
      <php>if(isset($_SESSION["kanri_flg"]) AND $_SESSION["kanri_flg"] == 1){
          echo '
                <li class="dropdown active">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Setting<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="../setting/interviewee_select.php">候補者新規登録</a></li>
						<li><a href="../setting/interviewer_select.php">面接者登録</a></li>
            <li><a href="../job_post/job_post_select.php">求人管理</a></li>
            <li><a href="../apply/apply_index_input.php">求人サイト作成</a></li>
            <li><a href="../apply/index.php">求人サイト確認</a></li>
					</ul>
				</li>
                ';
            }
        </php>
        <php> if(isset($_SESSION["kanri_flg"])){
                  echo '
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">評価入力/更新<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<li><a href="../result/input_data_select.php">評価入力</a></li>
					</ul>
				</li>
                ';
            }
        </php>
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"><php> if($_SESSION){echo $_SESSION["interviewer_name"];}else{echo "アカウント";} </php><span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
						<php> if(!$_SESSION){echo '<li><a href="/10/my_kaday/login_out/login.php">ログイン</a></li>';} </php>
                        <php> if($_SESSION){echo '
                        <li><a href="/10/my_kaday/login_out/logout.php">ログアウト</a></li>
						<li><a href="/10/my_kaday/login_out/my_interviewer_detail.php">情報更新</a></li>
                                ';
                            }
                        </php>
					</ul>
				</li> -->




  </ul>
</div>
</nav>
