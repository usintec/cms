<?php require_once("includes/session.php"); ?>  
<?php require_once("includes/connection.php"); ?> 
<?php require_once("includes/functions.php"); ?> 
<?php
    if(logged_in())
    {
        redirect_to("staff.php");
    }
?>
<?php include("includes/header.php"); ?>
<?php include_once("includes/form_functions.php");?>
<?php
    //start form processing
    if(isset($_POST['submit']))
    {
        $errors = array();
        
        //perform validation on the form
        $required_fields = array('username', 'password');
        $errors = array_merge($errors, check_required_fields($required_fields));
        
        $fields_with_lengths = array('username' => 30, 'password' => 30);
        $errors = array_merge($errors, check_max_field_length($fields_with_lengths));
        
        //clean up the data before putting it to the database
        $username = trim(mysql_prep($_POST['username']));
        $password = trim (mysql_prep($_POST['password']));
        //$hashed_password = md5($password);
        $hashed_password = sha1($password);
        //database submmision only proceed if there are no errors
        if(empty($errors))
        {
            $query = "SELECT id, username FROM users WHERE 
                      username = '{$username}' AND
                      hashed_password = '{$hashed_password}' LIMIT 1";
            $query_result = mysql_query($query, $connection);
            confirm_query($query_result);
            if(mysql_num_rows($query_result) == 1)
            {
                //username and password authenticated
                //and only 1 match
                $found_user = mysql_fetch_array($query_result);
                $_SESSION['user_id'] = $found_user['id'];
                $_SESSION['username'] = $found_user['username'];
                redirect_to("staff.php");
            }
            else
            {
                //username/password combo not found in the database
                $message = "Username/Password combination incorrect.</br>
                            Please make sure your caps lock key is off and try again";
            }
        }
        else
        {
            if(count($errors) == 1)
            {
                $message = "There was 1 error in the form.";
            }
            else
            {
                $message = "There were ". count($errors) ." in the form.";
            }
        }
        //END OF FORM PROCESSING
    }
    
    else
    {
        //form has not been submited
        if(isset($_GET['logout']) && $_GET['logout'] == 1)
        {
            $message = "you are now logged out";
        }
        $username = "";
        $password = "";
    
    }

?>
<table id="structure" >
    <tr>
        <td id="navigation">
         <a href="index.php">Return to public site</a>
        </td>
        <td id="page">
        <h2>Staff Login</h2>
        <?php if(!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
        <?php if(!empty($errors)) {display_errors($errors);} ?>
        
        <form action="login.php" method="post">
        <p>Username: <input type="text" name="username" maxlength="30" value="<?php
         echo htmlentities($username)?>" /> </p>
        <p>Password: <input type="password" name="password" maxlength="30" value="<?php
         echo htmlentities($password)?>"/> </p>
        <p><input type="submit"  name="submit" value="Login" /> </p>
        </form>
        </td>
  </tr>
</table>
<?php require("includes/footer.php"); ?> 
      



