<?php
    function check_required_fields($required_array)
    {
        $field_errors = array();
        //Form Validation
        foreach($required_array as $fieldname)
        {
            if(!isset($_POST[$fieldname]) || (empty($_POST[$fieldname]) && is_int($_POST[$fieldname])))
            ///&& (!is_int($_POST[$fieldname])) does not work form me to pass zero succefully in $visible
            {
                $field_errors[] = $fieldname;
            }               
        } 
        return $field_errors;
    }
    function check_max_field_length($field_length_array)
    {
        $field_errors = array();
        foreach($field_length_array as $fieldname => $maxlength)
        {
            if(strlen(trim(mysql_prep($_POST[$fieldname]))) > $maxlength)
            {
                $field_errors[] = $fieldname;
            }      
        }  
        return $field_errors;                                    
    }
    
    function display_errors($error_array)
    {   
        echo "<p class=\"errors\">";
        echo "please reveiw the following fields: <br/>";
        foreach($error_array as $error)
        {
            echo "- ". $error ."<br/>";
        }
        echo "</p>";
    }
?>
    
<?php   function form_process($sel_subject) 
    {                                       ?>
        <form action="edit_subject.php?subj=<?php echo urlencode($sel_subject['id']); ?>" method="post">
                <p>Subject name: <input type="text" name="menu_name"
                value="<?Php echo $sel_subject['menu_name'];?>" id="menu_name" /> </p>
                <p>Position: 
                    <select name="position">
                        <?php
                            $subject = get_all_subjects();
                            $subject_count = mysql_num_rows($subject);
                            for($count = 1; $count <= $subject_count + 1; $count++)
                            {   echo "<option value=\"{$count}\"";
                                if($sel_subject['position'] == $count)
                                {   
                                    echo " selected";
                                }
                            echo ">{$count}</option>";
                            }
                        ?>
                    </select>
                </p>
                <p>Visible:
                    <input type="radio" name="visible" value="0" 
                    <?php if($sel_subject['visible'] == 0) {echo " checked";}?>
                    /> No
                    &nbsp;
                    <input type="radio" name="visible" value="1"
                    <?php if($sel_subject['visible'] == 1) {echo " checked";}?>
                    />Yes
                </p>
                <input type="submit" name="submit" value="Edit Subject" />
                &nbsp; &nbsp;
                <a href="delete_subject.php?subj=<?php echo 
                urlencode($sel_subject['id']);?>" onclick="return confirm('Are you sure?');">Delete Subject</a>
             </form>
  <?php
    }
?>  

