<?php
/// This file contains a few configuration variables that control
/// how Moodle uses this theme.
////////////////////////////////////////////////////////////////////////////////
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Version details
 *
 * @package    Theme
 * @subpackage Aadar 
 * @copyright  2013 eabyas <eabyas.in>
 * @author     Niranjan Reddy <niranjan@eabyas.in>  
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepre = $PAGE->blocks->region_has_content('side-pre', $OUTPUT);
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
$showsidepre = $hassidepre && !$PAGE->blocks->region_completely_docked('side-pre', $OUTPUT);
$showsidepost = $hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT);
$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();
if ($showsidepre && !$showsidepost) {
    $bodyclasses[] = 'side-pre-only';
} else if ($showsidepost && !$showsidepre) {
    $bodyclasses[] = 'side-post-only';
} else if (!$showsidepost && !$showsidepre) {
    $bodyclasses[] = 'content-only';
}
if ($hascustommenu) {
    $bodyclasses[] = 'has_custom_menu';
}
if ($hasnavbar) {
    $bodyclasses[] = 'hasnavbar';
}

$courseheader = $coursecontentheader = $coursecontentfooter = $coursefooter = '';
if (empty($PAGE->layout_options['nocourseheaderfooter'])) {
    $courseheader = $OUTPUT->course_header();
    $coursecontentheader = $OUTPUT->course_content_header();
    if (empty($PAGE->layout_options['nocoursefooter'])) {
        $coursecontentfooter = $OUTPUT->course_content_footer();
        $coursefooter = $OUTPUT->course_footer();
    }
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <?php echo $OUTPUT->standard_head_html() ?>

	<style>

.center
{
margin:auto;
width:100%;
top: 10px;
height: 130px;
}


#innerrightinfo {
	height: 120px;
	position: absolute;
	right: 0px;
	top: 10px;
}

#innerrightinfo img.userpicture {
    background: none repeat scroll 0 0 #FFFFFF;
    border-color: #EEEEEE #DADADA #CCCCCC;
    border-style: solid solid solid none;
    border-width: 1px 1px 1px medium;
    float: right;
    height: 58px;
    margin-top: 17px;
    padding: 8px 8px 17px;
    width: 48px;
}

#innerrightinfo div.logininfo {
    border-right: medium none;
    float: right;
    height: 43px;
    margin-top: 70px;
    padding: 10px;
    text-align: center;
    width: 120px;
    font-size: 100%;
    font-weight: 300;
}

</style>

</head>
<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">
<?php if ($hasheading || $hasnavbar || !empty($courseheader)) { ?>
    <div id="page-header">
        <div class="rounded-corner top-left"></div>
        <div class="rounded-corner top-right"></div>
        <?php if ($hasheading) { ?>
        <h1 class="headermain">

<img src="https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQJ1RzzNh7UO3_uBsDGg0j2qFj_DciNFpsqjO0cIPhdc1Ehdwsv">
 </h1>
<div class="center"> 
</br>     
<h1>ARCADE RSDH </h1>
<h2>St. John's National Academy of Health Science </h2>
</div>
     

          <div>
             <?php
		echo "<div id='innerrightinfo'>";
 		if (isloggedin())
                    {
 			echo ''.$OUTPUT->user_picture($USER, array('size'=>55)).'';
 			}
 			else {
 			?>
 			<img class="userpicture" src="<?php echo $OUTPUT->pix_url('image', 'theme')?>" />
 			<?php
 			}

		

             echo $OUTPUT->login_info();
          //  if (!empty($PAGE->layout_options['langmenu'])) {
                echo $OUTPUT->lang_menu();
         //   }
            echo $PAGE->headingmenu
        ?></div><?php } ?>

        




<?php if (!empty($courseheader)) { ?>
            <div id="course-header"><?php echo $courseheader; ?></div>
        <?php } ?>
        <?php if ($hascustommenu) { ?>
            <div id="custommenu"><?php echo $custommenu; ?></div>
		<?php } ?>

        <?php if ($hasnavbar) { ?>
            <div class="navbar clearfix">
                <div class="breadcrumb"><?php echo $OUTPUT->navbar(); ?></div>
                <div class="navbutton"><?php echo $PAGE->button; ?></div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
<!-- END OF HEADER -->

    <div id="page-content">
        <div id="region-main-box">
            <div id="region-post-box">

                <div id="region-main-wrap">
                    <div id="region-main">
                        <div class="region-content">
                            <?php echo $coursecontentheader; ?>
                            <?php echo $OUTPUT->main_content() ?>
                            <?php echo $coursecontentfooter; ?>
                        </div>
                    </div>
                </div>

                <?php if ($hassidepre) { ?>
                <div id="region-pre" class="block-region">
                    <div class="region-content">
                        <?php echo $OUTPUT->blocks_for_region('side-pre') ?>
                    </div>
                </div>
                <?php } ?>

                <?php if ($hassidepost) { ?>
                <div id="region-post" class="block-region">
                    <div class="region-content">
                        <?php echo $OUTPUT->blocks_for_region('side-post') ?>
                    </div>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>

<!-- START OF FOOTER -->
    <?php if (!empty($coursefooter)) { ?>
        <div id="course-footer"><?php echo $coursefooter; ?></div>
    <?php } ?>
    <?php if ($hasfooter) { ?>
    <div id="page-footer" class="clearfix">
        <p class="helplink">

<p>
    
       
            St. Johns National Academy of Health Science </br>
         This website is funded by the European Union Seventh Framework program (No. 281930)</br>
        <?php /*echo page_doc_link(get_string('moodledocslink')) */?>
&nbsp;&nbsp;       
<a href="https://facebook.com">
<img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcStNh2xR2VaoVKIngrFPdsp-YAfFQat-l1gjDX5OkYA_mwMh97UOg" >
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="https://twitter.com/">
<img src="http://abstractions.deidreadams.com/wp-content/themes/photocrati-theme/images/social/small-twitter.png">
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="https://plus.google.com">
<img src="http://imaging.ubmmedica.com/all/editorial/icon-google-plus.jpg">
&nbsp;&nbsp;&nbsp;&nbsp;
<a href="http://www.linkedin.com/">
<img src="https://infopeople.org/sites/all/themes/infopeople/social/linkedin.png">
        </p>



     </p>
             
<?php /*echo page_doc_link(get_string('moodledocslink'))*/ ?></p>
        <?php
        /*echo $OUTPUT->login_info();*/
        /*echo $OUTPUT->home_link();*/
        /*echo $OUTPUT->standard_footer_html();*/
        ?>
        <div class="rounded-corner bottom-left"></div>
        <div class="rounded-corner bottom-right"></div>
    </div>
    <?php } ?>
  <div class="clearfix"></div>
</div>
<?php echo $OUTPUT->standard_end_of_body_html() ?>
</body>
</html>
