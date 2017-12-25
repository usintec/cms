<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?> 
<?php require_once("includes/functions.php"); ?> 
<?php confirm_logged_in();?>
<?php require_once("includes/form_functions.php");?>

<?php
    if(intval($_GET['subj']) == 0)
    {
        redirect_to("content.php");
    }
    if(isset($_POST['submit']))
    {
        $errors = array();
        
        $required_fields = array('menu_name', 'position', 'visible');
        $errors = array_merge($errors, check_required_fields($required_fields));
        
        $fields_with_lengths = array('menu_name' => 30);
        $errors = array_merge($errors, check_max_field_length($fields_with_lengths));

        // perform update
        $id = mysql_prep($_GET['subj']);
        $menu_name = mysql_prep($_POST['menu_name']) ;
        $position =  mysql_prep($_POST['position']) ;
        $visible =   mysql_prep($_POST['visible']) ;
        
        if(empty($errors))
        {
        
            $query = "UPDATE subjects SET
                      menu_name = '{$menu_name}',
                      position = {$position},
                      visible = {$visible}
                      WHERE id = {$id}";
            $result = mysql_query($query, $connection);
            if(mysql_affected_rows() == 1)
            {
                //success
                $message = "The subject was successfully updated";
            }
            else
            {
                //failed
                $message = "The subject update failed";
                $message .= mysql_errno();
            }
        }
        else
        {
            //errors occured
             $message = "The were ". count($errors) ." errors in the form.";
        } 
        

    } // end: if(isset($_POST['submit']))
?>
<?php find_selected_page();?>
<?php include("includes/header.php"); ?>

<div class="page-content">
<table id="structure" >
    <tr>
        <td id="navigation">
        <?php echo navigation($sel_subject, $sel_page); ?>
        </td>
        <td id="page">
            <h2>Edit Subject <?php echo $sel_subject['menu_name']; ?></h2>
            <?php
                if(!empty($message))
                {
                    echo "<p>{$message}</p>";
                }
            ?>
            <?php
                //output a list of fields that have errors.
                if(!empty($errors))
                {
                    echo "<p class=\"errors\">";
                    echo "please reveiw the following fields: <br/>";
                    foreach($errors as $error)
                    {
                        echo "- ". $error ."<br/>";
                    }
                    echo "</p>";
                }
            ?>
             <?php form_process($sel_subject);?>
            <a href="content.php">Cancel</a>          
            </div>
        </td>
      </tr>
      <tr>
        <td>
        <td>
            <br/>
            Pages in this subject
            <?php    
                list_of_pages_in_selected_subject($sel_subject["id"]);  
            ?>
            <br/>
            <a href="new_page.php?subj=<?php echo $sel_subject['id']; ?>">Add a new page to this subject</a>
        </td>
        </td>
      </tr>
   </table>
<?php require("includes/footer.php"); ?> 
      

