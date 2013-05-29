<?php
//
// You can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// It is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 *
 * @since 2.4
 * @package contribution
 * @copyright 2012 David Herney Bernal - cirano
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
require_once($CFG->dirroot.'/course/format/renderer.php');

/**
 * Basic renderer for menutopic format.
 *
 * @copyright 2012 David Herney Bernal - cirano
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class format_menutopic_renderer extends format_section_renderer_base {

    /**
     * Generate the starting container html for a list of sections
     * @return string HTML to output.
     */
    protected function start_section_list() {
        return html_writer::start_tag('ul', array('class' => 'topics'));
    }

    /**
     * Generate the closing container html for a list of sections
     * @return string HTML to output.
     */
    protected function end_section_list() {
        return html_writer::end_tag('ul');
    }

    /**
     * Generate the title for this section page
     * @return string the page title
     */
    protected function page_title() {
        return get_string('topicoutline');
    }

    /**
     * Generate the edit controls of a section
     *
     * @param stdClass $course The course entry from DB
     * @param stdClass $section The course_section entry from DB
     * @param bool $onsectionpage true if being printed on a section page
     * @return array of links with edit controls
     */
    protected function section_edit_controls($course, $section, $onsectionpage = false) {
        global $PAGE;

        if (!$PAGE->user_is_editing()) {
            return array();
        }

        if (!has_capability('moodle/course:update', context_course::instance($course->id))) {
            return array();
        }

        if ($onsectionpage) {
            $url = course_get_url($course, $section->section);
        } else {
            $url = course_get_url($course);
        }
        $url->param('sesskey', sesskey());

        $controls = array();
        if ($course->marker == $section->section) {  // Show the "light globe" on/off.
            $url->param('marker', 0);
            $controls[] = html_writer::link($url,
                                html_writer::empty_tag('img', array('src' => $this->output->pix_url('i/marked'),
                                    'class' => 'icon ', 'alt' => get_string('markedthistopic'))),
                                array('title' => get_string('markedthistopic'), 'class' => 'editing_highlight'));
        } else {
            $url->param('marker', $section->section);
            $controls[] = html_writer::link($url,
                            html_writer::empty_tag('img', array('src' => $this->output->pix_url('i/marker'),
                                'class' => 'icon', 'alt' => get_string('markthistopic'))),
                            array('title' => get_string('markthistopic'), 'class' => 'editing_highlight'));
        }

        return array_merge($controls, parent::section_edit_controls($course, $section, $onsectionpage));
    }

    /**
     * Generate next/previous section links for navigation
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param int $sectionno The section number in the coruse which is being dsiplayed
     * @return array associative array with previous and next section link
     */
    protected function get_nav_links($course, $sections, $sectionno) {
        // FIXME: This is really evil and should by using the navigation API.
        $canviewhidden = has_capability('moodle/course:viewhiddensections', context_course::instance($course->id))
            or !$course->hiddensections;

        $links = array('previous' => '', 'next' => '');
        $back = $sectionno - 1;

        while ((($back > 0 && $course->realcoursedisplay == COURSE_DISPLAY_MULTIPAGE) || ($back >= 0 && $course->realcoursedisplay != COURSE_DISPLAY_MULTIPAGE)) &&
                empty($links['previous'])) {
            if ($canviewhidden || $sections[$back]->visible) {
                $params = array();
                if (!$sections[$back]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $previouslink = html_writer::tag('span', $this->output->larrow(), array('class' => 'larrow'));
                $previouslink .= get_section_name($course, $sections[$back]);
                $links['previous'] = html_writer::link(course_get_url($course, $back), $previouslink, $params);
            }
            $back--;
        }

        $forward = $sectionno + 1;
        while ($forward <= $course->numsections and empty($links['next'])) {
            if ($canviewhidden || $sections[$forward]->visible) {
                $params = array();
                if (!$sections[$forward]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $nextlink = get_section_name($course, $sections[$forward]);
                $nextlink .= html_writer::tag('span', $this->output->rarrow(), array('class' => 'rarrow'));
                $links['next'] = html_writer::link(course_get_url($course, $forward), $nextlink, $params);
            }
            $forward++;
        }

        return $links;
    }

    /**
     * Generate next/previous section links for navigation according to menu configuration
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param int $sectionno The section number in the coruse which is being dsiplayed
     * @param string $nodesnavigation List of section numbers, separated with coma
     * @return array associative array with previous and next section link
     */
    protected function get_custom_nav_links($course, $sections, $sectionno, $nodesnavigation) {
        // FIXME: This is really evil and should by using the navigation API.
        $canviewhidden = has_capability('moodle/course:viewhiddensections', context_course::instance($course->id))
            or !$course->hiddensections;

        $links = array('previous' => '', 'next' => '');
        $current_exists = false;

    	$ids_topics = explode(',', $nodesnavigation);

		$pos = 0;
		foreach($ids_topics as $id_topic) {
			if (trim($id_topic) == $sectionno) {
				$current_exists = true;
				break;
			}
			$pos++;
		}
		
		if($current_exists) {

			if ($pos >= count($ids_topics)) {
				$previous_topic = $next_topic = null;
			}
			else if ($pos == 0) {
				$previous_topic = null;
				$next_topic = $ids_topics[1];
			}
			else if ($pos == (count($ids_topics) - 1)) {
				$previous_topic = $ids_topics[$pos - 1];
				$next_topic = null;
			}
			else {
				$previous_topic = $ids_topics[$pos - 1];
				$next_topic = $ids_topics[$pos + 1];
			}
			
            if (($canviewhidden || $sections[$previous_topic]->visible) && isset($sections[$previous_topic])) {
                $params = array();
                if (!$sections[$previous_topic]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $previouslink = html_writer::tag('span', $this->output->larrow(), array('class' => 'larrow'));
                $previouslink .= get_section_name($course, $sections[$previous_topic]);
                $links['previous'] = html_writer::link(course_get_url($course, $previous_topic), $previouslink, $params);
            }
			
            if (($canviewhidden || $sections[$next_topic]->visible) && isset($sections[$next_topic])) {
                $params = array();
                if (!$sections[$next_topic]->visible) {
                    $params = array('class' => 'dimmed_text');
                }
                $nextlink = get_section_name($course, $sections[$next_topic]);
                $nextlink .= html_writer::tag('span', $this->output->rarrow(), array('class' => 'rarrow'));
                $links['next'] = html_writer::link(course_get_url($course, $next_topic), $nextlink, $params);
            }
		}
        return $links;
    }
    
    /**
     * Output the html for a single section page .
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param array $mods used for print_section()
     * @param array $modnames used for print_section()
     * @param array $modnamesused used for print_section()
     * @param int $displaysection The section number in the course which is being displayed
     */
    public function print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE, $DB;
        
        $real_course_display = $course->realcoursedisplay;
        $modinfo = get_fast_modinfo($course);
        $course = course_get_format($course)->get_course();
        $course->realcoursedisplay = $real_course_display; 
        $sections = $modinfo->get_section_info_all();
                
        // Can we view the section in question?
        $context = context_course::instance($course->id);
        $canviewhidden = has_capability('moodle/course:viewhiddensections', $context);

        if (!isset($sections[$displaysection])) {
            // This section doesn't exist
            print_error('unknowncoursesection', 'error', null, $course->fullname);
            return;
        }

        if ($PAGE->user_is_editing()) {
        	
	        echo html_writer::start_tag('form', array('method' => 'post'));
	        echo html_writer::empty_tag('input', array('type' => 'submit', 'value'=>get_string('editmenu', 'format_menutopic')));
	        echo html_writer::empty_tag('input', array('type' => 'hidden', 'value'=>'true', 'name'=>'editmenumode'));
	        echo html_writer::empty_tag('input', array('type' => 'hidden', 'value'=>$course->id, 'name'=>'id'));
	        echo html_writer::end_tag('form');
        }

        if (!$sections[$displaysection]->visible && !$canviewhidden) {
            if (!$course->hiddensections) {
                echo $this->start_section_list();
                echo $this->section_hidden($displaysection);
                echo $this->end_section_list();
            }
            // Can't view this section.
            return;
        }
        
        // Copy activity clipboard..
        echo $this->course_activity_clipboard($course, $displaysection);

        // General section if non-empty and course_display is multiple.
        if ($course->realcoursedisplay == COURSE_DISPLAY_MULTIPAGE) {
            $thissection = $sections[0];
            if ($thissection->summary or $thissection->sequence or $PAGE->user_is_editing()) {
                echo $this->start_section_list();
                echo $this->section_header($thissection, $course, true);
                print_section($course, $thissection, $mods, $modnamesused, true, '100%', false, $displaysection);
                if ($PAGE->user_is_editing()) {
                    print_section_add_menus($course, 0, $modnames, false, false, $displaysection);
                }
                echo $this->section_footer();
                echo $this->end_section_list();
            }
        }

        //Load configuration data
        if(!($format_data = $DB->get_record('format_menutopic', array('course'=>$course->id)))){
        	$format_data = new stdClass();
        	$format_data->course	= $course->id;

        	if (!($format_data->id = $DB->insert_record('format_menutopic', $format_data))) {
        		debugging('Not is possible save the course format data in menutopic format', DEBUG_DEVELOPER);
        	}
        }
        
		$config_menu = new object();
		$config_menu->jsdefault = true;
		$config_menu->cssdefault = true;
		$config_menu->menuposition = 'middle';
		$config_menu->linkinparent = false;
		$config_menu->templatetopic = false;
		$config_menu->icons_templatetopic = false;
		$config_menu->displaynousedmod = false;
		$config_menu->displaynavigation = 'nothing';
		$config_menu->nodesnavigation = '';

		if (is_object($format_data) && property_exists($format_data, 'config') && !empty($format_data->config)) {
			$config_saved = @unserialize($format_data->config);
			
			if (!is_object($config_saved)) {
				$config_saved = new object();
			}
			
			if (isset($config_saved->jsdefault)) { $config_menu->jsdefault = $config_saved->jsdefault; }

			if (isset($config_saved->cssdefault)) { $config_menu->cssdefault = $config_saved->cssdefault; }

			if (isset($config_saved->menuposition)) { $config_menu->menuposition = $config_saved->menuposition; }
			
			if (isset($config_saved->linkinparent)) { $config_menu->linkinparent = $config_saved->linkinparent; }
			
			if (isset($config_saved->templatetopic)) { $config_menu->templatetopic = $config_saved->templatetopic; }

			if (isset($config_saved->icons_templatetopic)) { $config_menu->icons_templatetopic = $config_saved->icons_templatetopic; }

			if (isset($config_saved->displaynousedmod)) { $config_menu->displaynousedmod = $config_saved->displaynousedmod; }

			if (isset($config_saved->displaynavigation)) { $config_menu->displaynavigation = $config_saved->displaynavigation; }

			if (isset($config_saved->nodesnavigation)) { $config_menu->nodesnavigation = $config_saved->nodesnavigation; }
		}
		
		$format_data->config_menu = $config_menu;

        $this->print_menu($format_data, $course, $sections, $mods, $modnames, $modnamesused, $displaysection);
        
        // Start single-section div
        echo html_writer::start_tag('div', array('class' => 'single-section'));

        // Title with section navigation links.
        
        if (empty($format_data->config_menu->nodesnavigation)) {
        	$sectionnavlinks = $this->get_nav_links($course, $sections, $displaysection);
        }
        else {
        	$sectionnavlinks = $this->get_custom_nav_links($course, $sections, $displaysection, $format_data->config_menu->nodesnavigation);
        }

        $sectiontitle = '';
	    $sectiontitle .= html_writer::start_tag('div', array('class' => 'section-navigation header headingblock'));
        
        if ($format_data->config_menu->displaynavigation == 'top' || $format_data->config_menu->displaynavigation == 'both') {
	        $sectiontitle .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
	        $sectiontitle .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
        }
        // Title attributes
        $titleattr = 'mdl-align title';
        if (!$sections[$displaysection]->visible) {
            $titleattr .= ' dimmed_text';
        }
        $sectiontitle .= html_writer::tag('div', get_section_name($course, $sections[$displaysection]), array('class' => $titleattr));
        $sectiontitle .= html_writer::end_tag('div');
        echo $sectiontitle;

        // Now the list of sections..
        echo $this->start_section_list();

        // The requested section page.
        $thissection = $sections[$displaysection];
        echo $this->section_header($thissection, $course, true, $displaysection);
        // Show completion help icon.
        $completioninfo = new completion_info($course);
        echo $completioninfo->display_help_icon();

        print_section($course, $thissection, $mods, $modnamesused, true, '100%', false, $displaysection);
        if ($PAGE->user_is_editing()) {
            print_section_add_menus($course, $displaysection, $modnames, false, false, $displaysection);
        }
        echo $this->section_footer();
        echo $this->end_section_list();

        if ($format_data->config_menu->displaynavigation == 'bottom' || $format_data->config_menu->displaynavigation == 'both') {
	        // Display section bottom navigation.
	        $sectionbottomnav = '';
	        $sectionbottomnav .= html_writer::start_tag('div', array('class' => 'section-navigation mdl-bottom'));
	        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['previous'], array('class' => 'mdl-left'));
	        $sectionbottomnav .= html_writer::tag('span', $sectionnavlinks['next'], array('class' => 'mdl-right'));
	        $sectionbottomnav .= html_writer::end_tag('div');
	        echo $sectionbottomnav;
        }

        // close single-section div.
        echo html_writer::end_tag('div');
    }

    /**
     * Output the html for a edit mode page.
     *
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param array $mods used for print_section()
     * @param array $modnames used for print_section()
     * @param array $modnamesused used for print_section()
     * @param int $displaysection The section number in the course which is being displayed
     */
    public function print_edition_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
        global $PAGE, $CFG, $DB, $OUTPUT;
        
        if (!$PAGE->user_is_editing()) {
        	$this->print_single_section_page($course, $sections, $mods, $modnames, $modnamesused, $displaysection);
        	return;
        }
        
        echo html_writer::start_tag('form', array('method' => 'GET'));
        echo html_writer::empty_tag('input', array('type' => 'submit', 'value'=>get_string('end_editmenu', 'format_menutopic')));
        echo html_writer::empty_tag('input', array('type' => 'hidden', 'value'=>$displaysection, 'name'=>'section'));
        echo html_writer::empty_tag('input', array('type' => 'hidden', 'value'=>$course->id, 'name'=>'id'));
        echo html_writer::end_tag('form');
        
		$menuaction = optional_param('menuaction', 'config', PARAM_ALPHA);
		
		$options = array('config', 'tree', 'jstemplate', 'csstemplate');
	
		if (!in_array($menuaction, $options)) {
			$menuaction = 'config';
		}
	
		$course_link 		= new moodle_url($CFG->wwwroot.'/course/view.php', array('id'=>$course->id, 'editmenumode'=>'true', 'section'=>$displaysection));
		$course_cancel_link = new moodle_url($CFG->wwwroot.'/course/view.php', array('id'=>$course->id, 'section'=>$displaysection));

		$tabs = array();
	
		$tabs[] = new tabobject("tab_configmenu_config", $course_link . '&menuaction=config',
		'<font style="white-space:nowrap">' . get_string('config_editmenu', 'format_menutopic') . "</font>", get_string('config_editmenu', 'format_menutopic'));
		$tabs[] = new tabobject("tab_configmenu_tree", $course_link . '&menuaction=tree',
		'<font style="white-space:nowrap">' . get_string('tree_editmenu', 'format_menutopic') . "</font>", get_string('tree_editmenu', 'format_menutopic'));
		$tabs[] = new tabobject("tab_configmenu_jstemplate", $course_link . '&menuaction=jstemplate',
		'<font style="white-space:nowrap">' . get_string('jstemplate_editmenu', 'format_menutopic') . "</font>", get_string('jstemplate_editmenu', 'format_menutopic'));
		$tabs[] = new tabobject("tab_configmenu_csstemplate", $course_link . '&menuaction=csstemplate',
		'<font style="white-space:nowrap">' . get_string('csstemplate_editmenu', 'format_menutopic') . "</font>", get_string('csstemplate_editmenu', 'format_menutopic'));
	
		print_tabs(array($tabs), "tab_configmenu_" . $menuaction);
		
	    // Start box container
        echo html_writer::start_tag('div', array('class' => 'box generalbox'));

        if(!($format_data = $DB->get_record('format_menutopic', array('course'=>$course->id)))){
        	$format_data = new stdClass();
        	$format_data->course	= $course->id;

        	if (!($format_data->id = $DB->insert_record('format_menutopic', $format_data))) {
        		debugging('Not is possible save the course format data in menutopic format', DEBUG_DEVELOPER);
        		redirect($course_cancel_link);
        	}
        }

		include $CFG->dirroot . '/course/format/menutopic/form_' . $menuaction . '.php';

        // close box container
        echo html_writer::end_tag('div');
    }

    /**
     * Print the custom menu
     *
     * @param stdClass $format_data Data used to create menu and other functionality in the format
     * @param stdClass $course The course entry from DB
     * @param array $sections The course_sections entries from the DB
     * @param array $mods used for print_section()
     * @param array $modnames used for print_section()
     * @param array $modnamesused used for print_section()
     * @param int $displaysection The section number in the course which is being displayed
     */
    public function print_menu($format_data, $course, $sections, $mods, $modnames, $modnamesused, $displaysection) {
    	global $CFG, $PAGE;

		if (!empty($format_data->tree) && $format_data->config_menu->menuposition != 'hide') {
			$treecode = stripslashes($format_data->tree);

			require_once $CFG->dirroot . '/course/format/menutopic/menu.php';
			
			$menu = new format_menutopic_menu();
			$menu->treecode = $treecode;
			
			$print_for_menu = '';
			
			//Not is used when jsdefault is true because when module is added the CSSs are included
			if ($format_data->config_menu->cssdefault && !$format_data->config_menu->jsdefault) {
				$print_for_menu .= '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/lib/yui/3.5.1/build/node-menunav/assets/node-menunav-core.css" />';
				$print_for_menu .= '<link rel="stylesheet" type="text/css" href="'.$CFG->wwwroot.'/lib/yui/3.5.1/build/node-menunav/assets/skins/sam/node-menunav.css" />';
			}

			if ($format_data->config_menu->jsdefault) {
				$PAGE->requires->js_module(array(
			        'name' => 'format_menutopic',
			        'fullpath' => '/course/format/menutopic/module.js',
			        'requires' => array('node-menunav'),
			        'strings' => array(
			            array('error_jsontree', 'format_menutopic'),
			            array('title_panel_sheetedit', 'format_menutopic')
			        ),
		    	));
		        $PAGE->requires->js_init_call('M.format_menutopic.init_jsmenu', array($format_data->config_menu->menuposition));
			}
			else {
				$PAGE->requires->js_init_call('M.format_menutopic.init_menu', array($format_data->config_menu->menuposition));
			}

			if (!empty($format_data->js)) {
				$PAGE->requires->js_init_code($format_data->js, true);
			}
				
			if (!empty($format_data->css)) {
				$print_for_menu .= html_writer::start_tag('style');
				$print_for_menu .= $format_data->css;
				$print_for_menu .= html_writer::end_tag('style');
			}

			//HTML code for load the menu
			$print_for_menu .= '<div style="display: none;">' . $menu->script_menu($format_data->config_menu) . '</div><div id="region-original"></div>';
			
			echo $print_for_menu;
	
		}
    }
    
}
