Moodle Bar for Moodle 2.0+ Jeremy FitzPatrick adapted from Moodle Bar V1.0 by Lewis Carr | www.lewiscarr.co.uk

1) Copy the folder /moodlebar into the layout folder of your chosen theme (moodle/themes/mytheme/layout/moodlebar)
2) Move the mb folder to the pix directory of your chosen theme (moodle/themes/mytheme/pix/mb)
3) Add the following code to your layout files (e.g. mytheme/layout/general.php) just before the close body tag </body>
   <?php include ('moodlebar/moodle_bar.php'); ?>
4)Save the file and away you go.
5)If your Moodle site is not 100% width then there is a line of CSS to change in the file (moodle_bar.php), 
set this to your desired width in pixels.  I've put a comment in the file as to where this is.