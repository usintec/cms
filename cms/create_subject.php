<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?> 
<?php require_once("includes/functions.php"); ?> 
<?php confirm_logged_in();?>
<?php require_once("includes/form_process.php"); ?> 

<?php
     $errors = form_validate('menu_name', 'position', 'visible');
     if (!empty($errors))
        {
            redirect_to("new_subject.php");
        }
?>              

<?php
    $menu_name = mysql_prep($_POST['menu_name']) ;
    $position =  mysql_prep($_POST['position']) ;
    $visible =   mysql_prep($_POST['visible']) ;
?>
<?php
    $query = "INSERT INTO subjects (
    menu_name, position, visible) VALUES(
    '{$menu_name}', {$position}, {$visible})";
    $result = mysql_query($query, $connection);
    if($result)
    {                
        //success
        redirect_to("content.php");
        exit;
    }
    else
    {
        //display error message
        echo "<p>subject creation failed</p>";
        echo "<p>. mysql_error() .</p>";
    }
?>


<?php mysql_close($connection);?>
