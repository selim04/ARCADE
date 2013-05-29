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
 * @since 2.3
 * @package contribution
 * @copyright 2012 David Herney Bernal - cirano
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class format_menutopic_menu {
	
	public $treecode = '';
	private $_config;
	
	// Object construct
	public function __construct($config = null) {
		if (!empty($config) && is_object($config)) {
			$this->_config	= $config;
		}
		else {
			$this->_config = new object();
			$this->_config->jsdefault = true;
			$this->_config->cssdefault = true;
			$this->_config->usehtml = false;
			$this->_config->menuposition = 'middle';
			$this->_config->linkinparent = false;			
		}
	}
	
	public function list_code_horizontal_menu () {
		if (empty($this->treecode)){
			return '';
		}
		
		$json = json_decode($this->treecode);
		$codetree = '<ul>';

		if (isset($json->topics) && is_array($json->topics)){
			foreach ($json->topics as $one_topic) {
				$codetree .= $this->list_item_menu ($one_topic);
			}
		}

		return $codetree . '</ul>';
		
	}
	
	private function list_item_menu ($node) {
		$url = '';
		if (empty($node->url)) {
			if (!empty($node->topicnumber) || $node->topicnumber === "0") {
				global $course, $CFG;
				$url = new moodle_url($CFG->wwwroot.'/course/view.php', array('id'=>$course->id, 'section'=>$node->topicnumber));
			}
		}
		else {
			$url = $node->url;
		}

		if (isset($node->subtopics) && is_array($node->subtopics) && count($node->subtopics) > 0) {
			$id = rand();
			$submenu = '';
			foreach ($node->subtopics as $one_topic) {
				$submenu .= $this->list_item_menu ($one_topic);
			}
			
			// If parent will linking with the url
			if (isset($this->_config->linkinparent) && $this->_config->linkinparent) {
				$item = '<li>
							<a class="yui3-menu-label" href="%s">%s</a>
							<div id="%s" class="yui3-menu">
								<div class="yui3-menu-content">                                        
									<ul>
										%s
									</ul>
								</div>
							</div>
						 </li>';
				$res = sprintf($item, $url, $node->name, $id, $submenu);
			}
			else {
				$item = '<li>
							<a class="yui3-menu-label" href="#%2$s"><em>%1$s</em></a>
							<div id="%2$s" class="yui3-menu">
								<div class="yui3-menu-content">
									<ul>
										%3$s
									</ul>
								</div>
							</div>
						 </li>';
				$res = sprintf($item, $node->name, $id, $submenu);
			}
		}
		else {
			$item = '<li class="yui3-menuitem">
						<a class="yui3-menuitem-content" href="%s" target="%s">%s</a>
					 </li>';

			$res = sprintf($item, $url, $node->target, $node->name);
		}
		
		return $res;
	}

	public function list_menu () {
		$print_for_menu = '';
		
		$print_for_menu .= '<div class="yui3-menu-content">';

		$print_for_menu .= $this->list_code_horizontal_menu ();
		$print_for_menu .= '</div>';

		return $print_for_menu;
	}

	public function script_menu($config) {
		$this->_config = $config;
		$print_for_menu = '<div id="id_menu_box" class="yui3-menu';

		if (!$this->_config->linkinparent) {
			 $print_for_menu .= ' yui3-menubuttonnav';
		}
		$print_for_menu .= '">';

		$print_for_menu .= $this->list_menu();
		
		$print_for_menu .= '</div>';

		return $print_for_menu;
	}
}
