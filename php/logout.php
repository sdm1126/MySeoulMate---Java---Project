<?php
  session_start();
  unset($_SESSION["session_id"]);
  unset($_SESSION["session_name"]);

  echo "<script>alert('로그아웃 되었습니다.');</script>";
  echo "<script>location.href = './main.php';</script>";
?>