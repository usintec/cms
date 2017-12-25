<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?> 
<?php require_once("includes/functions.php"); ?>
<?php confirm_logged_in();?>
<?php
    if(intval($_GET['page']) == 0)
    {
        redirect_to("content.php");
    }
    $id = mysql_prep($_GET['page']);
    if($pag =get_page_by_id($id))
    { 
        $query = "DELETE FROM pages WHERE id = {$id} LIMIT 1 ";
        $result = mysql_query($query, $connection);
        if(mysql_affected_rows()== 1)
        {
            redirect_to("content.php");
        }
        else
        {
            //subject deletion failed
            echo "<p>subject deletion failed</p>";
            echo "<p>". mysql_errno() ."</p>";
            echo "<a href=\"content.php\" Return to main page </a>";
        }
    }
    else
    {
        //subject did not exist in the database
        //redirect_to("content.php");
        echo "error";
    }
?>

<?php mysql_close($connection);?>