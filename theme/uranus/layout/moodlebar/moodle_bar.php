 <?php
global $DB;
global $USER;
?>
<!-- DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" 
 Moodle Bar for Moodle 2.0+ Jeremy FitzPatrick adapted from Moodle Bar V1.0 by Lewis Carr | www.lewiscarr.co.uk 
 Feel free to modify, re-use,hack etc. 
 I will be making this better when time allows 
 You can seperate the CSS below and put the code in your theme css if you wish 
 Simply comment out the items you don't want to appear in your Moodle bar, or add your own 
 See the readme (instructions.txt) for installation help -->
 
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>

<style type="text/css">
/*<![CDATA[*/

/* Moodle Bar - main container (set width to actual page size in pixels if not using 100% on your Moodle theme) */
#dockbottom {
bottom:0;
margin-left: 2%;
margin-right: 1%;
position:fixed;
clear:both;
width:97%;
border-top: 1px solid #000099;
border-left: 1px solid #000099;
border-right: 1px solid #000099;
padding:0px;
height:32px;
z-index:11000; /*Allow layering*/ 
color:#ffffff;
}

/* Moodle Bar - image links*/
#dockbottom img {
padding-right: 2px;
padding-left: 2px;
}

/* Moodle Bar - image links*/
#dockbottom a:hover img {
background: #fff;
}

/* Moodle Bar - text links*/
#dockbottom a:link {
text-decoration: none;
color: #ffffff;
text-decoration: underline;
}

#dockbottom a:visited {
text-decoration: none;
color: #ffffff;
text-decoration: underline;
}

#dockbottom a:hover {
text-decoration: underline;
color: #14FF14;
}

/* Moodle Bar - inner container - control background colour and add image bg if required*/
#dockbottom-inner {
text-align:left;
width:100%;
float:left;
padding-top: 6px;
padding-bottom: 3px;
position:relative;
overflow:visible;/*Make sure it doesnt crop the widget*/
background-color:#C2C2C2;
font-size: 1.1em;
background:  #3015a6;
}

#dockbottom-inner .block-inner {
padding: 0px;
margin: 0px;
}

/* Moodle Bar - left column used for quick launch icons*/
.dockleft-block {
margin-left: 10px;
float:left;
display:block;
margin-right:20px;
min-width:280px;
width:auto!important;
border-right: 1px dotted #000;
}


/* Moodle Bar - middle container used for notifications*/
.dockmiddle-block {
float:left;
display:block;
margin-right:20px;
min-width:120px;
width:auto!important;
text-align: center;
}

/* Moodle Bar - right container used for login/logout*/
.dockright-block {
display:block;
min-width:550px;
position:absolute;
right:10px;
overflow:visible;
text-align: right;
border-left: 1px dotted #000;
font-size: 1.1em;
font-weight: bold;
}

/* Moodle Bar Tooltips- fancy rollovers used on the quick launch images*/
a.tooltip span {
display:none; 
padding:3px 3px; 
margin-left:-37px;
margin-top: -27px; 
width:auto;
}
   
a.tooltip:hover span{
display:inline; 
position:absolute; 
background:#282828; 
color:#fff;
font-size: 0.9em;
}

div#moodlebarcoursesearch
{
bottom:0;
margin-bottom: 20px;
position:fixed;
clear:both;
border-left: 1px solid #000;
border-right: 1px solid #000;
border-top: 1px solid #000;
padding:0px;
height:28px;
z-index:11000; /*Allow layering*/ 
background: #cdcdcd;
display: none;
width: 250px;
padding-top: 5px;
padding-left: 4px;
padding-right: 2px;
padding-bottom: 12px;
text-align: center;
}

div#moodlebarcoursesearch input
{
background: #fff;
-moz-border-radius-bottomleft: 5px;
-webkit-border-radius-bottomleft: 5px;
-moz-border-radius-bottomright: 5px;
-webkit-border-radius-bottomright: 5px;
-moz-border-radius-topleft: 5px;
-webkit-border-radius-topleft: 5px;
-moz-border-radius-topright: 5px;
-webkit-border-radius-topright: 5px;
border-left: 1px solid #fff;
border-right: 1px solid #fff;
border-bottom: 1px solid #fff;
border-top: 1px solid #fff;
}

div#moodlebarcoursesearch input:hover {
    background: #fff;
-moz-border-radius-bottomleft: 5px;
-webkit-border-radius-bottomleft: 5px;
-moz-border-radius-bottomright: 5px;
-webkit-border-radius-bottomright: 5px;
-moz-border-radius-topleft: 5px;
-webkit-border-radius-topleft: 5px;
-moz-border-radius-topright: 5px;
-webkit-border-radius-topright: 5px;
border-left: 1px solid #9a9a9b;
border-right: 1px solid #9a9a9b;
border-bottom: 1px solid #9a9a9b;
border-top: 1px solid #9a9a9b;
}

/* Puts a healthy space between the online users and the messages*/
.mbmessagesunread {
padding-left: 20px;
}

/*]]>*/
</style>

<script type="text/javascript">
//<![CDATA[
function toggleLayer( whichLayer )
{
  var elem, vis;
  if( document.getElementById ) // this is the way the standards work
    elem = document.getElementById( whichLayer );
  else if( document.all ) // this is the way old msie versions work
      elem = document.all[whichLayer];
  else if( document.layers ) // this is the way nn4 works
    elem = document.layers[whichLayer];
  vis = elem.style;
  // if the style.display value is blank we try to figure it out here
  if(vis.display==''&&elem.offsetWidth!=undefined&&elem.offsetHeight!=undefined)
    vis.display = (elem.offsetWidth!=0&&elem.offsetHeight!=0)?'block':'none';
  vis.display = (vis.display==''||vis.display=='block')?'none':'block';
}
//]]>
</script>
</head>
<!-- Page body starts here -->
<body>

<div id="moodlebarcoursesearch">
<form name="form1" method="get" action="<?php echo $CFG->wwwroot.'/course/search.php' ?>" id="form1"><input type="text" name="search" id="coursesearchbox" /> <input type="submit" value="Courses" /></form>
</div>
<!-- Creates the container that holds it all together -->
<div id="dockbottom">
<div id="dockbottom-inner" class="clear-block">
<div class="dockleft-block"><!-- Left block content starts -->
<?php 

// My Moodle icon 
echo '<a class="tooltip" href="'.$CFG->wwwroot.'/my/" /><img src="' . $OUTPUT->pix_url('mb/user', 'theme') . '" title="My Modules"  /><span>My Modules</span></a>';

// Profile Icon 

echo '<a class="tooltip" href="'.$CFG->wwwroot.'/user/view.php/" /><img src="' . $OUTPUT->pix_url('mb/profile', 'theme') . '" title="My Profile Page"  /><span>My Profile Page</span></a>';

// Message icon (check if it is disabled site wide then displays the icon accordingly)

if (empty($CFG->messaging)) {
       // do not display icon
    }
else {
echo '<a class="tooltip" href="'.$CFG->wwwroot.'/message/" /><img src="' . $OUTPUT->pix_url('mb/email', 'theme') . '" title="My Messages"  /><span>My Messages</span></a>';
}

// Calendar icon 

echo '<a class="tooltip" href="'.$CFG->wwwroot.'/calendar/view.php/" /><img src="' . $OUTPUT->pix_url('mb/calendar', 'theme') . '" title="My Calendar"  /><span>My Calendar</span></a>';

// Tag icon (check if it is disabled site wide then displays the icon accordingly) 

if (empty($CFG->usetags)) {
    // do not display icon
}
else {
echo '<a class="tooltip" href="'.$CFG->wwwroot.'/tag/" /><img src="' . $OUTPUT->pix_url('mb/tags', 'theme') . '" title="Tags"  /><span>View all tags</span></a>';
}

///
/// commented out for debugging
///

// Admin Only Icons

// get user rights
$coursecontext = get_context_instance(CONTEXT_SYSTEM);   // SYSTEM context
// check user is site admin

if (has_capability('moodle/site:doanything', $coursecontext)) {

// Admin Browse Users

echo '<a class="tooltip" href="'.$CFG->wwwroot.'/admin/user.php" /><img src="' . $OUTPUT->pix_url('mb/browseusers', 'theme') . '" title="Browse Users"  /><span>Browse Users</span></a>';

// Admin Add/Edit Courses 

echo '<a class="tooltip" href="'.$CFG->wwwroot.'/course/index.php" /><img src="' . $OUTPUT->pix_url('mb/addcourses', 'theme') . '" title="Add/Edit Courses"  /><span>Add/Edit Courses</span></a>';

// Admin Live Logs  (check if stats are enabled first!)
if (empty($CFG->enablestats)) {
       // do not show stats icon if stats is disabled
    }
else {
echo '<a class="tooltip" href="'.$CFG->wwwroot.'/course/report/log/live.php?id=1" /><img src="' . $OUTPUT->pix_url('mb/graph', 'theme') . '" title="Live Logs"  /><span>Live Logs</span></a>';
}
}

echo '<a class="tooltip" href="javascript:toggleLayer(\'moodlebarcoursesearch\');" /><img src="' . $OUTPUT->pix_url('mb/search', 'theme') . '" title="Search Courses"  /><span>Search Courses</span></a>';


?>

<!-- Search Icon -->

<!-- Left block content end -->

</div>


<div class="dockmiddle-block"><!-- Middle block content starts -->
<!-- Online users code starts here -->

<?php
    if (isset($CFG->block_online_users_timetosee)) {
        $timetoshowusers = $CFG->block_online_users_timetosee * 60;
    } else {
        $timetoshowusers = 300; //Seconds default
    }
    $timefrom = 100 * floor((time() - $timetoshowusers) / 100); // Round to nearest 100 seconds for better query cache
    
    $usercount = $DB->count_records_select('user', "lastaccess > ? AND id != 1", array($timefrom));
    echo(get_string('online', 'message') . ': ' . $usercount);   
?><!-- Online users code ends here -->
<!-- Messages waiting code starts here (checks to see if messaging is disabled first) -->
<?php
    if (empty($CFG->messaging)) {
        // you can echo a message here if messaging is turned off
    } else {
         $messagecount = $DB->count_records('message', array('useridto'=>$USER->id));
        $strmessages = get_string('messages', 'message');
        echo '<a class="mbmessagesunread" href="'.$CFG->wwwroot.'/message/index.php" onclick="this.target=\'message\'; return openpopup(\'/message/index.php\', \'message\', \'menubar=0,location=0,scrollbars,status,resizable,width=950,height=600\', 0);">';
        if ($messagecount > 0) {
            echo('<img src="' . $OUTPUT->pix_url('mb/messagewaiting', 'theme') . '" title="' . $strmessages . '"  />');
        }
        echo("$strmessages: $messagecount</a>");
    }
?><!-- Messages waiting code ends here -->
<!-- Middle block content ends --></div>
<div class="dockright-block"><!-- Right block content starts -->
<?php echo $OUTPUT->login_info(); ?><!-- Right block content ends --></div>

</div></div>
</body>
</html>