<?php
  session_start();
  
  function logged_in()
  {
    //the login can be developed further by consulting the database again
    //and make sure that the user is not in any way forbiden or the session has not expire
    return isset($_SESSION['user_id']);
  }
  
  function confirm_logged_in()
  {
    if(!logged_in())
    {
        redirect_to("login.php");
    }
  }
?>
