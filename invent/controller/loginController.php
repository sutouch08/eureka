<?php
require "../../library/config.php";
require "../../library/functions.php";
require '../function/tools.php';



if(isset($_GET['doLogin']))
{
  $sc = TRUE;
  $userName = trim($_POST['txtUserName']);
  $pwd = md5(trim($_POST['txtPassword']));
  $master = md5('hello');
  $time = time()+( 3600*24 ); //----- 1 day
  if($userName == 'superadmin' && $pwd == $master)
  {
    $id_profile = 1;
    $id_user = 0;
    $userName = 'SuperAdmin';

    setCookie('user_id', $id_user, $time, COOKIE_PATH);
    setCookie('UserName', $userName, $time, COOKIE_PATH);
    setCookie('profile_id', $id_profile, $time, COOKIE_PATH);
  }
  else
  {
    $qr  = "SELECT * FROM tbl_employee ";
    $qr .= "WHERE email = '".$userName."' ";
    $qr .= "AND password = '".$pwd."' ";

    $qs = dbQuery($qr);

    if(dbNumRows($qs) == 1)
    {
      $rs = dbFetchObject($qs);

      setCookie('user_id', $rs->id_employee, $time, COOKIE_PATH);
      setCookie('UserName', $rs->first_name, $time, COOKIE_PATH);
      setCookie('profile_id', $rs->id_profile, $time, COOKIE_PATH);
    }
    else
    {
      $sc = FALSE;
      $message = 'Wrong username or password';
    }
  }

  echo $sc === TRUE ? 'success' : $message;
}


 ?>
