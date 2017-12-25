<?php require_once("includes/connection.php"); ?> 
<?php require_once("includes/functions.php"); ?> 
<?php find_selected_page();?>
<?php include("includes/header.php"); ?>

<table id="structure" >
<tr>
    <td id="navigation">
    <?php echo navigation_index($sel_subject, $sel_page); ?>   
    <br/>
    <td id="page">
    <h2>

<div class="page-content">    
<?php
    if(!is_null($sel_page))
    {echo $sel_page['menu_name'];
            echo "<br/>";
            if(!is_null($sel_page))
            {echo $sel_page['content'];}
    }
?> 
        
</body>
<?php require("includes/footer.php"); ?> 
