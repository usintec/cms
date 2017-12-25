<?php require_once("includes/connection.php"); ?> 
<?php require_once("includes/functions.php"); ?> 
<?php find_selected_page();?>

<?php
    if(intval($_GET['subj']) == 0)
    {
        redirect_to("content.php");
    }
      
    include_once("includes/form_functions.php"); 
 
    //start form processing
    //only execute the form processing if the form has been submitted
    if(isset($_POST['submit']))
    {
        $errors = array();
        
        //perform validation on the form
        $required_fields = array('menu_name', 'position', 'visible', 'content');
        $errors = array_merge($errors, check_required_fields($required_fields));
        
        $fields_with_lengths = array('menu_name' => 30);
        $errors = array_merge($errors, check_max_field_length($fields_with_lengths));
        
        //clean up the data before putting it to the database
        $subject_id = mysql_prep(($_GET['subj']));
        $menu_name = trim(mysql_prep($_POST['menu_name']));
        $position = mysql_prep($_POST['position']);
        $visible = mysql_prep($_POST['visible']);
        $content = mysql_prep($_POST['content']);
        
        //database submmision only proceed if there are no errors
        if(empty($errors))
        {
            $query = "INSERT INTO pages  
                      (subject_id, menu_name, position, visible, content)
                      VALUES ( {$subject_id}, '{$menu_name}', {$position}, {$visible}, '{$content}' )";
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


<?php include("includes/header.php"); ?> 

<table id="structure" >
    <tr>
        <td id="navigation">
        <?php echo navigation($sel_subject, $sel_page); ?>   
        <br/>
         <a href="new_subject.php">+ Add new subject</a>
        </td>
        <td> 
        <h2><?php echo "Adding New Page: ". $sel_subject['menu_name']; ?></h2>
        <?php if(!empty($message)) {echo "<p class=\"message\">" . $message . "</p>";} ?>
        <?php if(!empty($errors)) {display_errors($errors);} ?>     
        <form action="new_page.php?subj=<?php echo $sel_subject['id']; ?>"  method="post">  
                <?php $new_page = true; ?>
                <?php include "page_form.php" ?>                                      
                <input type="submit" name="submit" value="Create Page" ></input> &nbsp; &nbsp; 
        </form>                                                           
        <br/>
        <a href="content.php?=<?php echo $sel_page['id']; ?>"> Cancel </a>
    
        </td> 
    </tr> 
</table>      
<?php require("includes/footer.php"); ?>  