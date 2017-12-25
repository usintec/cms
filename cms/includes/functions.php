<?php
    // this is the place to store all basic functions
    function mysql_prep($value)
    {
        $magic_quotes_active = get_magic_quotes_gpc();
        $new_enough_php = function_exists("mysql_real_escape_string");
        //i.e PHP >= v4.3.0
        if($new_enough_php)
        {
            //php v4.3.0 or higher
            //undo any magic quote effects so mysql_real_escape_string
            //can do the work
            if($magic_quotes_active)
            { $value = stripslashes($value);}
            $value = mysql_real_escape_string($value);
        }
        else
        {
            //before PHP v4.3.0
            //if magic quotes aren't already on then add slashes manually
            if (!$magic_quotes_active)
            { $addslashes($value); }
            // if magic quotes are active, then the slaches already exist
        }
        return $value;
    }
    
    function redirect_to($location = NULL)
    {
        if($location != NULL)
            {
                header("Location: {$location}");
                exit;
            }
    }                                                                                         
    
    function confirm_query($result_set)
    {
    if(!$result_set)
        {
            die("Database query failed: " . mysql_error());
        }
    }
    
    function get_all_subjects($public = true)
    {
        global $connection;
        $query = "SELECT * FROM subjects ";
        if ($public)
        {$query .= "WHERE visible = 1";} 
        $query .=  " ORDER BY position ASC";
        $subject_set = mysql_query($query, $connection);
        confirm_query($subject_set);
        return $subject_set;
    }
    
    function get_pages_for_subject($subject_id, $public=true)
    {
        global $connection;
        $query = "SELECT * FROM pages ";
        $query .= "WHERE subject_id= {$subject_id} ";
        if ($public)
        {$query .= "AND visible = 1";}
        $query .=  " ORDER BY position ASC";   
        $pages_set = mysql_query($query, $connection);
        confirm_query($pages_set);
        return $pages_set;
    }
                               
    function get_subject_by_id($subject_id)
    {
        global $connection;
        $query = "SELECT * ";
        $query .= "FROM subjects ";
        $query .= "WHERE id=" . $subject_id . " ";
        $query .= "LIMIT 1";
        $result_set = mysql_query($query, $connection);
        confirm_query($result_set);
        //remember
        //if no rows are returned, fetch array will return false
        if($subject = mysql_fetch_array($result_set))
        {return $subject;}
        else
        {return NULL;}
    }
    
    function get_page_by_id($page_id)
    {
        global $connection;
        $query = "SELECT * ";
        $query .= "FROM pages ";
        $query .= "WHERE id=" . $page_id . " ";
        $query .= "LIMIT 1";
        $result_set = mysql_query($query, $connection);
        confirm_query($result_set);
        //remember 
        //if now rows are returned, fetch array will return false
        if($page = mysql_fetch_array($result_set))
        {return $page;}
        else
        {return NULL;}
        
        
    }
    
    function get_all_pages()
    {
        global $connection;
        $query = "SELECT * ";
        $query .= "FROM pages ORDER BY id ASC";
        $result_set = mysql_query($query, $connection);
        confirm_query($result_set);
        return $result_set;
    }
    
    function find_selected_page()
    {   
        global $sel_subject;
        global $sel_page;
           if (isset($_GET['subj']))
        {                            
            $sel_subject = get_subject_by_id($_GET['subj']);
            $sel_page = get_default_page($sel_subject['id']);
                 
        }            
        elseif(isset($_GET['page']))
        {
            $sel_page = get_page_by_id($_GET['page']);
            $sel_subject = NULL; 
        }
        else
        {
            $sel_subject = NULL; 
            $sel_page = NULL;
        }
    }
    
    function find_selpage_display_firstpage_of_subject()
    {   
        global $sel_subject;
        global $sel_page;
        global $connection;
           if (isset($_GET['subj']))
        {                            
            $sel_subject = NULL;
            //$sel = get_page_by_id($_GET['subj']);
             $query = "SELECT id FROM pages WHERE subject_id={$_GET['subj']} ORDER BY id ASC LIMIT 1";
             $result = mysql_query($query, $connection);
             confirm_query($result);
             $page_first = mysql_fetch_array($result); 
             $sel_page = get_page_by_id($page_first['id']);      
             return $sel_page;
        }            
        elseif(isset($_GET['page']))
        {
            $sel_page = get_page_by_id($_GET['page']);
            $sel_subject = NULL; 
        }
        else
        {
            $sel_subject = NULL; 
            $sel_page = NULL;
        }        
    }

    
    function navigation($sel_subject, $sel_page, $public = false)  
    {
                $output = "<ul class=\"subjects\">";
                $subject_set = get_all_subjects($public);
                while($subject = mysql_fetch_array($subject_set))
                {
                     $output .= "<li>";
                     if($subject["id"] == $sel_subject['id'])
                     { $output .= "class =\"selected\""; }
                     $output .= "<a href=\"edit_subject.php?subj=". urlencode($subject["id"]).
                     "\"> {$subject["menu_name"]} </a></li>"; 
                    $pages_set = get_pages_for_subject($subject["id"], $public);
                    $output .= "<ul class=\"page\">";
                    while($page = mysql_fetch_array($pages_set))
                    {
                        $output .= "<li>";
                        if($page["id"] == $sel_page['id'])
                        {$output .= "class =\"selected\"";}                      
                        $output .= "<a href=\"edit_page.php?page=". urlencode($page["id"]). 
                        "\"> {$page["menu_name"]} </a> </li>"; 
                    }
                    $output .= "</ul>";
                }
            $output .= "</ul>";
            return $output;
    }
    
    function navigation_index($sel_subject, $sel_page, $public = true)  
    {
               // $first_page_subject = find_selpage_display_firstpage_of_subject();
                $output = "<ul class=\"subjects\">";
                $subject_set = get_all_subjects($public);
                while($subject = mysql_fetch_array($subject_set))
                {
                     $output .= "<li>";
                     if($subject["id"] == $sel_subject['id'])
                     { $output .= "class =\"selected\""; }
                     $output .= "<a href=\"index.php?subj=". urlencode($subject["id"]).
                     "\"> {$subject["menu_name"]} </a></li>";  
                     if($subject["id"] == $sel_subject['id'])
                     {
                        $pages_set = get_pages_for_subject($subject["id"], $public);
                        $output .= "<ul class=\"page\">";
                        while($page = mysql_fetch_array($pages_set))
                        {
                            $output .= "<li>";
                            if($page["id"] == $sel_page['id'])
                            {$output .= "class =\"selected\"";}                      
                            $output .= "<a href=\"index.php?page=". urlencode($page["id"]). 
                            "\"> {$page["menu_name"]} </a> </li>"; 
                        }                  
                        $output .= "</ul>";
                     }
                }
            $output .= "</ul>";
            return $output;
    }
    
    function navigation_page($sel_subject, $sel_page)
    {       
                $output = "<ul class=\"subjects\">";
                $subject_set = get_all_subjects();
                while($subject = mysql_fetch_array($subject_set))
                {     
                     $output .= "<li>";
                     if($subject["id"] == $sel_subject['id'])
                     { $output .= "class =\"selected\""; }
                     $output .= "<a href=\"edit_subject.php?subj=". urlencode($subject["id"]).
                     "\"> {$subject["menu_name"]} </a></li>"; 
                    $pages_set = get_pages_for_subject($subject["id"]);
                    $output .= "<ul class=\"page\">";
                    while($page = mysql_fetch_array($pages_set))
                    {
                        $output .= "<li>";
                        if($page["id"] == $sel_page['id'])
                        {$output .= "class =\"selected\"";}                      
                        $output .= "<a href=\"edit_page.php?page=". urlencode($page["id"]). 
                        "\"> {$page["menu_name"]} </a> </li>"; 
                    }
                    $output .= "</ul>";
                }
            $output .= "</ul>";
            return $output;
    }
    
    function list_of_pages_in_selected_subject($sel_subject)
    {
                 global $sel_page;
                 $pages_set = get_pages_for_subject($sel_subject, false);
                 //$output .= "<ul class=\"page\">";
                 while($page = mysql_fetch_array($pages_set))
                    {
                        $output = "<li>";
                        if($page["id"] == $sel_page['id'])
                        {$output .= "class =\"selected\"";}                      
                        $output .= "<a href=\"edit_page.php?page=". urlencode($page["id"]). 
                        "\"> {$page["menu_name"]} </a> </li>"; 
                        $output .= "</ul>";
                        echo $output;
                    }
    }
    
    function get_default_page($subject_id)
    {
        $page_set = get_pages_for_subject($subject_id, true);
        if($first_page=mysql_fetch_array($page_set))
        {
            return $first_page;
        }   
        else
        {
        return NULL;
        }
    }
?>
