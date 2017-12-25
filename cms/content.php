<?php require_once("includes/session.php"); ?>
<?php require_once("includes/connection.php"); ?> 
<?php require_once("includes/functions.php"); ?> 
<?php confirm_logged_in();?>
<?php find_selected_page();?>
<?php include("includes/header.php"); ?>
<table id="structure" >
    <tr>
        <td id="navigation">
        <?php echo navigation($sel_subject, $sel_page); ?>   
        <br/>
         <a href="new_subject.php">+ Add new subject</a>
         <br/>
         <br/>
         <a href="staff.php">+ Staff Area</a>
        </td>
        <td id="page">
        <h2>
        <?php
            if(!is_null($sel_subject))           
            {echo "Subject Name: " . $sel_subject['menu_name'];}
            elseif(!is_null($sel_page))
            {echo "Page Name: " . $sel_page['menu_name'];}
            else
            {echo "Select a subject or page to edit";}
        ?>
        </h2>
        <div class="page-content">
        <?php
            if(!is_null($sel_page))
            {echo $sel_page['content'];}
        ?>   
        <br/>
        <?php if(isset($sel_page))
        {echo "<a href='edit_page.php?page= $sel_page[id]'>Edit this page</a>";} ?>        
        </div>
    </td>
  </tr>
</table>
<?php require("includes/footer.php"); ?> 
      

