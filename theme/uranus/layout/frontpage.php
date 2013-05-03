<?php

$hasheading = ($PAGE->heading);
$hasnavbar = (empty($PAGE->layout_options['nonavbar']) && $PAGE->has_navbar());
$hasfooter = (empty($PAGE->layout_options['nofooter']));
$hassidepost = $PAGE->blocks->region_has_content('side-post', $OUTPUT);
$showsidepost = ($hassidepost && !$PAGE->blocks->region_completely_docked('side-post', $OUTPUT));

$custommenu = $OUTPUT->custom_menu();
$hascustommenu = (empty($PAGE->layout_options['nocustommenu']) && !empty($custommenu));

$bodyclasses = array();
if ($showsidepost) {
    $bodyclasses[] = 'side-post-only';
} else if (!$showsidepost) {
    $bodyclasses[] = 'content-only';
}

if ($hascustommenu) {
    $bodyclasses[] = 'has-custom-menu';
}

echo $OUTPUT->doctype() ?>
<html <?php echo $OUTPUT->htmlattributes() ?>>
<head>
    <title><?php echo $PAGE->title ?></title>
    <link rel="shortcut icon" href="<?php echo $OUTPUT->pix_url('favicon', 'theme')?>" />
    <meta name="description" content="<?php p(strip_tags(format_text($SITE->summary, FORMAT_HTML))) ?>" />
    <?php echo $OUTPUT->standard_head_html() ?>
</head>

<body id="<?php p($PAGE->bodyid) ?>" class="<?php p($PAGE->bodyclasses.' '.join(' ', $bodyclasses)) ?>">
<?php echo $OUTPUT->standard_top_of_body_html() ?>

<div id="page">

    <div id="wrapper" class="clearfix">


<!-- START OF HEADER -->

        <div id="page-header">
            <div id="page-header-wrapper" class="wrapper clearfix">
			
                <div class="headermenu">
                    <?php
                        echo $OUTPUT->login_info();
                        echo $OUTPUT->lang_menu();
                        echo $PAGE->headingmenu; ?>
			</br><form name="form1" method="get" align="right" action="<?php echo $CFG->wwwroot.'/course/search.php' ?>" id="form1"><input type="text" size="25" name="search" id="coursesearchbox" /> <input type="submit" value="Search Modules" /></form>
                </div>
			<a class="logo" title="Home" href="http://inspire.rcs.ac.uk/"><img src="<?php echo $OUTPUT->pix_url('/pix_core/logo', 'theme');?>" align="left"/></a>
                <h1 class="headermain"><?php echo $PAGE->heading ?></h1>				
				
                <?php if ($hascustommenu) { ?>
                <div id="custommenu"><?php echo $custommenu; ?></div>
                <?php } ?>
            </div>
        </div>

<!-- END OF HEADER -->

<!-- START OF CONTENT -->

        <div id="page-content-wrapper" class="wrapper clearfix">
            <div id="page-content">
                <div id="region-main-box">
                    <div id="region-post-box">

                        <div id="region-main-wrap">
                            <div id="region-main">
                                <div class="region-content">
                                    <?php echo $OUTPUT->main_content() ?>
                                </div>
                            </div>
                        </div>

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
            <div class="myclear"></div>

        </div>


<!-- END OF CONTENT -->
    <div class="myclear"></div>
       </div> <!-- END #wrapper -->

<!-- START OF FOOTER -->
   <div id="footer" class="myclear">
      </div>

<!-- END OF FOOTER -->

</div> <!-- END #page -->

<?php echo $OUTPUT->standard_end_of_body_html() ?>
<?php include ('moodlebar/moodle_bar.php'); ?>
</body>
</html>