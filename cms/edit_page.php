<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?> 
<?php require_once("includes/functions.php"); ?> 
<?php confirm_logged_in();?>
<?php find_selected_page();?>

<?php
    if(intval($_GET['page']) == 0)
    {
        redirect_to("content.php");
    }
      
    include_once("includes/form_functions.php"); 
 
    //start form processing
    //only execute the form processing if the form has been submitted
    if(isset($_POST['update']))
    {
        $errors = array();
        
        //perform validation on the form
        $required_fields = array('menu_name', 'position', 'visible', 'content');
        $errors = array_merge($errors, check_required_fields($required_fields));
        
        $fields_with_lengths = array('menu_name' => 30);
        $errors = array_merge($errors, check_max_field_length($fields_with_lengths));
        
        //clean up the data before putting it to the database
        $id = mysql_prep($_GET['page']);
        $menu_name = trim(($_POST['menu_name']));
        $position = mysql_prep($_POST['position']);
        $visible = mysql_prep($_POST['visible']);
        $content = mysql_prep($_POST['content']);
        
        //database submmision only proceed if there are no errors
        if(empty($errors))
        {
            $query = "UPDATE pages SET 
                      menu_name = '{$menu_name}', 
                      position = '{$position}',
                      visible = '{$visible}', 
                      content = '{$content}'
                      WHERE id = {$id}";
            $query_result = mysql_query($query, $connection);
            confirm_query($query_result);
            if(mysql_affected_rows()== 1)
            {
                //success
                $message = "The page was successfully updataed.";
            }
            else
            {
                $message = "The page could not be updated";
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
?>
<?php require_once("includes/header.php"); ?>
<table id="structure" >
    <tr>
        <td id="navigation">
        <?php echo navigation($sel_subject, $sel_page, false); ?>   
        </td>
        <td id="page">
        <h2> <?php {echo "Edit Page: ". $sel_page['menu_name'];}?></h2> 
        <?php if(!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
        <?php if(!empty($errors)) {display_errors($errors);} ?>
        
        <div class="page-content">
        <?/*php
            if(!is_null($sel_page))
            {echo $sel_page['content'];}
        */?>   
        <?php $page = $_GET['page'];?>
        <form action="edit_page.php?page=<?php echo $sel_page['id']; ?>"  method="post">
        <?php include "page_form.php"; ?>
        <input type="submit" name="update" value="Update Page" /> &nbsp; &nbsp;
        <a href="delete_page.php?page=<?php echo urldecode($sel_page['id']); ?>" 
        onclick="return confirm('Are you sure you want to delete this page)"> Delete Page </a>
         <br/> 
      </form>
      <a href="content.php?=<?php echo $sel_page['id']; ?>"> Cancel </a>
   </td>
  </tr>
</table>
<?php
    // 5. Close connection
    if(isset($connection))
    {      
    mysql_close($connection);
    }
?>

      

