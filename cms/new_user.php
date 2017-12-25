<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?> 
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in();?> 
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
            $query = "INSERT INTO users( username, hashed_password)
                      VALUES('{$username}', '{$hashed_password}')";
            $query_result = mysql_query($query, $connection);
            if($query_result)
            {
                //success
                $message = "The user was successfully created.";
            }
            else
            {
                $message = "The user could not be created";
                $message .= "<br/>" . mysql_errno();
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
        $username = "";
        $password = "";
    
    }

?>
<table id="structure" >
    <tr>
        <td id="navigation">
         <a href="staff.php">Return to Menu</a>
        </td>
        <td id="page">
        <h2>Create New User</h2>
        <?php if(!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
        <?php if(!empty($errors)) {display_errors($errors);} ?>
        
        <form action="new_user.php" method="post">
        <p>Username: <input type="text" name="username" maxlength="30" value="<?php
         echo htmlentities($username)?>" /> </p>
        <p>Password: <input type="password" name="password" maxlength="30" value="<?php
         echo htmlentities($password)?>"/> </p>
        <p><input type="submit"  name="submit" value="Create User" /> </p>
        </form>
        </td>
  </tr>
</table>
<?php require("includes/footer.php"); ?> 
      



