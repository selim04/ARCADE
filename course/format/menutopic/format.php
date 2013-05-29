<?php 

    require_once($CFG->dirroot.'/mod/forum/lib.php');

    // Bounds for block widths
    define('BLOCK_L_MIN_WIDTH', 100);
    define('BLOCK_L_MAX_WIDTH', 210);
    define('BLOCK_R_MIN_WIDTH', 100);
    define('BLOCK_R_MAX_WIDTH', 210);
    optional_variable($preferred_width_left,  blocks_preferred_width($pageblocks[BLOCK_POS_LEFT]));
    optional_variable($preferred_width_right, blocks_preferred_width($pageblocks[BLOCK_POS_RIGHT]));
    $preferred_width_left = min($preferred_width_left, BLOCK_L_MAX_WIDTH);
    $preferred_width_left = max($preferred_width_left, BLOCK_L_MIN_WIDTH);
    $preferred_width_right = min($preferred_width_right, BLOCK_R_MAX_WIDTH);
    $preferred_width_right = max($preferred_width_right, BLOCK_R_MIN_WIDTH);
    
    if ($topic>0) {
        $displaysection = $topic;
        $USER->display[$course->id] = $topic;
        if (isteacher($course->id)) { 
            $course->marker = $topic;
            if (! set_field("course", "marker", $topic, "id", $course->id)) {
                error("Could not mark that topic for this course");
            }
        }
        
    } else {
        if ($USER->display[$course->id]>0) {      
            $displaysection = $USER->display[$course->id];
        } else {
            if (!$displaysection = get_field("course", "marker", "id", $course->id)) {
                $displaysection = 0;
            }
        }
    }


    $streditsummary   = get_string('editsummary');
    $stradd           = get_string('add');
    $stractivities    = get_string('activities');
    $strshowalltopics = get_string('showalltopics');
    $strtopic         = get_string('topic');
    $strgroups        = get_string('groups');
    $strgroupmy       = get_string('groupmy');
    $editing          = $PAGE->user_is_editing();

    if ($editing) {
        $strstudents = moodle_strtolower($course->students);
        $strtopichide = get_string('topichide', '', $strstudents);
        $strtopicshow = get_string('topicshow', '', $strstudents);
        $strmoveup = get_string('moveup');
        $strmovedown = get_string('movedown');
    }

/// Layout the whole page as three big columns.
    echo '<table id="layout-table" cellspacing="0"><tr>';

/// The left column ...

    echo '<td style="width: '.$preferred_width_left.'px;" id="left-column"><div id="navcontainer"><ul id="navlist">';
    

/// make a list of all the topics

    
    $section = 0;

    while ($section <= $course->numsections) {

        if (!empty($sections[$section])) {
            $thissection = $sections[$section];

        } else {
            unset($thissection);
            $thissection->course = $course->id;   // Create a new section structure
            $thissection->section = $section;
            $thissection->summary = '';
            $thissection->visible = 1;
            if (!$thissection->id = insert_record('course_sections', $thissection)) {
                notify('Error inserting new topic!');
            }
        }
        
        // skip section 0
        if  ($section == 0) {
            $section = 1;
            continue;        
        }
        
        // display the topic unless it should be hidden        
        
        if ($editing or (($thissection->visible or !$course->hiddensections) and ($thissection->sequence or $thissection->summary))) {
                        
            if (preg_match('/.*?<\/h\d>/i',$thissection->summary,$headings)) {
                $strsummary = strip_tags($headings[0]); //the first <h.> tag contains the title
            } else {
                $strsummary = strip_tags($thissection->summary);
                if (empty($strsummary)) {
                    $strsummary = $strtopic.' '.$section;
                }
                elseif (strlen($strsummary) > 30) {
                    $strsummary = substr($strsummary, 0, 25).'...';
                }
            }
            // set style
            if (!empty($displaysection) and $displaysection == $section) {
                echo '<li class="selected">';
                //echo '<a href="'.$CFG->wwwroot.'/course/format/more/view.php?section='.$section.'&amp;ajax=true" class="ask target-middle-column">';
                p($strsummary);
                echo ' <img src="../pix/i/knipperlicht.gif"></li>';
            } else {
                echo '<li>';
                echo '<a href="'.$CFG->wwwroot.'/course/view.php?id='.$course->id.'&amp;topic='.$section.'">';
                p($strsummary);
                echo '</a></li>';
                
            }   
        }        
        $section++;
        continue;
    }     
    echo '</ul></div>';   

    // continue with rest of the blocks
    if (blocks_have_content($pageblocks, BLOCK_POS_LEFT) || $editing) {
        blocks_print_group($PAGE, $pageblocks, BLOCK_POS_LEFT);
    }
    echo '</td>';

/// Start main column
    echo '<td id="middle-column">';

    echo '<table class="topics" width="100%">';

/// If currently moving a file then show the current clipboard
    if (ismoving($course->id)) {
        $stractivityclipboard = strip_tags(get_string('activityclipboard', '', addslashes($USER->activitycopyname)));
        $strcancel= get_string('cancel');
        echo '<tr class="clipboard">';
        echo '<td colspan="3">';
        echo $stractivityclipboard.'&nbsp;&nbsp;(<a href="mod.php?cancelcopy=true&amp;sesskey='.$USER->sesskey.'">'.$strcancel.'</a>)';
        echo '</td>';
        echo '</tr>';
    }
    
                
/// Print Section 0

    $section = 0;
    $thissection = $sections[$section];

    if ($thissection->summary or $thissection->sequence or isediting($course->id)) {
        echo '<tr id="section-0" class="section main">';
        echo '<td class="left side">&nbsp;</td>';
        echo '<td class="content">';
        
        echo '<div class="summary">';
        $summaryformatoptions->noclean = true;
        echo format_text($thissection->summary, FORMAT_HTML, $summaryformatoptions);

        //Accessibility: added Alt, filled empty Alt-link text.
        if (isediting($course->id)) {
            echo '<a title="'.$streditsummary.'" '.
                 ' href="editsection.php?id='.$thissection->id.'"><img src="'.$CFG->pixpath.'/t/edit.gif" '.
                 ' height="11" width="11" border="0" alt="'.$streditsummary.'" /></a><br /><br />';
        }
        echo '</div>';

        print_section($course, $thissection, $mods, $modnamesused);

        if (isediting($course->id)) {
            print_section_add_menus($course, $section, $modnames);
        }

        echo '</td>';
        echo '<td class="right side">&nbsp;</td>';
        echo '</tr>';
        echo '<tr class="section separator"><td colspan="3" class="spacer"></td></tr>';
    }
                
// Print the desired section
    
    $section = $displaysection;
    $thissection = $sections[$section];
    
    if ($displaysection == 0) {
                
        $options = NULL;
        $options->noclean = true;
        $options->para = false;
        echo '<tr class="section">';
        echo '<td class="left side">&nbsp;</td>';
        echo '<td class="content">';
        echo format_text($course->summary, FORMAT_MOODLE, $options,  $course->id);
        echo '</td>';
        echo '<td class="right side">&nbsp;</td>';
        echo '</tr>';
        echo '<tr class="section separator"><td colspan="3" class="spacer"></td></tr>';
    } else {    
        if (!$thissection->visible) {
            $sectionstyle = ' hidden';
        } else {
            $sectionstyle = '';
        }

        echo '<tr id="section-'.$displaysection.'" class="section'.$sectionstyle.'">';
        echo '<td class="left side">&nbsp;</td>';
        echo '<td class="content">';
        
        if (!isteacher($course->id) and !$thissection->visible) {   // Hidden for students
            echo get_string('notavailable');
        
        } else {                
                
            echo '<div class="summary">';
            if ($thissection->summary) {
                $summaryformatoptions->noclean = true;
                echo format_text($thissection->summary, FORMAT_HTML, $summaryformatoptions);
            } else {    
                echo '<h1>'.$strtopic.' '.$displaysection.'</h1>';
            }    
            if (isediting($course->id)) {
                echo ' <a title="'.$streditsummary.'" href="editsection.php?id='.$thissection->id.'">'.
                     '<img src="'.$CFG->pixpath.'/t/edit.gif" border="0" height="11" width="11" alt="" /></a><br /><br />';
                     
                     }
            echo '</div>';

            print_section($course, $thissection, $mods, $modnamesused);

            if (isediting($course->id)) {
                print_section_add_menus($course, $displaysection, $modnames);
            }
        }
        
        
        echo '</td>';

        echo '<td class="right side">';

        if (isediting($course->id)) {


            if ($thissection->visible) {        // Show the hide/show eye
                echo '<a href="view.php?id='.$course->id.'&amp;hide='.$displaysection.'&amp;sesskey='.$USER->sesskey.'#'.$displaysection.'" title="'.$strtopichide.'">'.
                     '<img src="'.$CFG->pixpath.'/i/hide.gif" vspace="3" height="16" width="16" border="0" alt="" /></a><br />';
            } else {
                echo '<a href="view.php?id='.$course->id.'&amp;show='.$displaysection.'&amp;sesskey='.$USER->sesskey.'#'.$displaysection.'" title="'.$strtopichide.'">'.
                     '<img src="'.$CFG->pixpath.'/i/show.gif" vspace="3" height="16" width="16" border="0" alt="" /></a><br />';
            }

            if ($displaysection > 1) {                       // Add a arrow to move section up
                echo '<a href="view.php?id='.$course->id.'&amp;section='.$displaysection.'&amp;move=-1&amp;sesskey='.$USER->sesskey.'#'.($displaysection-1).'" title="'.$strmoveup.'">'.
                     '<img src="'.$CFG->pixpath.'/t/up.gif" vspace="3" height="11" width="11" border="0" alt="" /></a><br />';
            }

            if ($displaysection < $course->numsections) {    // Add a arrow to move section down
                echo '<a href="view.php?id='.$course->id.'&amp;section='.$displaysection.'&amp;move=1&amp;sesskey='.$USER->sesskey.'#'.($displaysection+1).'" title="'.$strmovedown.'">'.
                     '<img src="'.$CFG->pixpath.'/t/down.gif" vspace="3" height="11" width="11" border="0" alt="" /></a><br />';
            }

        }

        echo '</td></tr>';
        echo '<tr class="section"><td colspan="3" class="spacer"></td></tr>';
    
    
    

    }
    echo '</table>';
    echo '</td>';

    // The right column
    if (blocks_have_content($pageblocks, BLOCK_POS_RIGHT) || $editing) {
        echo '<td style="width: '.$preferred_width_right.'px;" id="right-column">';
        blocks_print_group($PAGE, $pageblocks, BLOCK_POS_RIGHT);
        echo '</td>';
    }

    echo '</tr></table>';
    echo '<script language="JavaScript" type="text/javascript" src="'.$CFG->httpswwwroot.'/lib/prototype.js"></script>';  
echo '<script language="JavaScript" type="text/javascript" src="'.$CFG->httpswwwroot.'/lib/scriptaculous.js"></script>';  
echo "<script type='text/javascript'>";  
echo "Sortable.create('left-column',";  
echo "{tag:'div', hoverclass:'drophover', dropOnEmpty:true, handle:'header',";  
echo "containment:['left-column','right-column'],constraint:false";  
if ($editing) {  
   echo ",onUpdate:saveBlocks";  
}  
echo "});"; 

echo "Sortable.create('right-column',";  
echo "{tag:'div', hoverclass:'drophover', dropOnEmpty:true, handle:'header',";  
echo "containment:['left-column','right-column'],constraint:false";  
if ($editing) {  
    echo ",onUpdate:saveBlocks";  
}  
echo "});";  
if ($editing) {  
    echo "function saveBlocks(element) {";  
    echo "new Ajax.Request('".$CFG->wwwroot."/lib/ajaxlib.php',";  
    echo "{parameters: Sortable.serialize(element)});";  
    echo "}";  
 }  
 echo "</script>"; 

?>
