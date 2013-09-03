<?php // $Id: migrate2utf8.php,v 1.1 2008/11/04 20:46:42 arborrow Exp $
function migrate2utf8_label_name($recordid){
    global $CFG, $globallang;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$label = get_record('skype','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }
    if ($globallang) {
        $fromenc = $globallang;
    } else {
        $sitelang   = $CFG->lang;
        $courselang = get_course_lang($label->course);  //Non existing!
        $userlang   = get_main_teacher_lang($label->course); //N.E.!!

        $fromenc = get_original_encoding($sitelang, $courselang, $userlang);
    }

/// We are going to use textlib facilities
    
/// Convert the text
    if (($fromenc != 'utf-8') && ($fromenc != 'UTF-8')) {
        $result = utfconvert($label->name, $fromenc);

        $newlabel = new object;
        $newlabel->id = $recordid;
        $newlabel->name = $result;
        migrate2utf8_update_record('skype',$newlabel);
    }
/// And finally, just return the converted field
    return $result;
}

function migrate2utf8_label_content($recordid){
    global $CFG, $globallang;

/// Some trivial checks
    if (empty($recordid)) {
        log_the_problem_somewhere();
        return false;
    }

    if (!$label = get_record('skype','id',$recordid)) {
        log_the_problem_somewhere();
        return false;
    }
    if ($globallang) {
        $fromenc = $globallang;
    } else {
        $sitelang   = $CFG->lang;
        $courselang = get_course_lang($label->course);  //Non existing!
        $userlang   = get_main_teacher_lang($label->course); //N.E.!!

        $fromenc = get_original_encoding($sitelang, $courselang, $userlang);
    }

/// We are going to use textlib facilities
    
/// Convert the text
    if (($fromenc != 'utf-8') && ($fromenc != 'UTF-8')) {
        $result = utfconvert($label->content, $fromenc);

        $newlabel = new object;
        $newlabel->id = $recordid;
        $newlabel->content = $result;
        migrate2utf8_update_record('skype',$newlabel);
    }
/// And finally, just return the converted field
    return $result;
}
?>
