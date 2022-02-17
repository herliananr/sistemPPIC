<?php
  // periksa apakah user sudah login, cek kehadiran session name 
  // jika tidak ada, redirect ke login.php
  session_start();
  if (!isset($_SESSION["id_kar"])) {
     header("Location: login.php");
  }
  else{
  $isi_sesi=$_SESSION["id_kar"];
  }
?>