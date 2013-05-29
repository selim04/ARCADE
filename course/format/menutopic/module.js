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
 * JavaScript library for the menutopic course format.
 *
 * @since 2.3
 * @package contribution
 * @copyright 2012 David Herney Bernal - cirano
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

M.format_menutopic = M.format_menutopic || {};

M.format_menutopic.init_tree = function (Y) {
	if (!load_tree('id_treecode')) {
		alert(M.str.format_menutopic.error_jsontree);
		return;
	}

	// Instantiate a Panel from markup
	YUI.tree_admin.panel_edit_sheet = new Y.Panel({
		srcNode      : "#panel_edit_sheet", 
		visible      : false,
		draggable    : true,
		headerContent: M.str.format_menutopic.title_panel_sheetedit,
	    plugins      : [Y.Plugin.Drag]
	});
	YUI.tree_admin.panel_edit_sheet.render();

	Y.one('#id_submitbutton').on('click', save_tree_config);
};

M.format_menutopic.init_menu = function(Y, position) {
	var container;
	var box = Y.one('#id_menu_box');
	var success = false;

	if (position == 'right') {
		container = Y.one('#region-post');
		if (container) {
			container.prepend(box);
			container.set('className', container.get('className') + " format_menutopic_correctstyle");
			success = true;
		}
	}
	else if (position == 'left'){
		container = Y.one('#region-pre');
		if (container) {
			container.prepend(box);
			container.set('className', container.get('className') + " format_menutopic_correctstyle");
			success = true;
		}
	}
	else {
		Y.one('#region-original').prepend(box);
		box.set('className', box.get('className') + " yui3-menu-horizontal");
		success = true;
	}
	
	//If the side have not blocks, not exist the container
	if (!success) {
		//Always exist the region-original container
		Y.one('#region-original').prepend(box);
		box.set('className', box.get('className') + " yui3-menu-horizontal");
	}
};

M.format_menutopic.init_jsmenu = function(Y, position) {
	
	M.format_menutopic.init_menu(Y, position);
	
	Y.on("contentready", function () {
        this.plug(Y.Plugin.NodeMenuNav, { autoSubmenuDisplay: true, mouseOutHideDelay: 750 });
    }, "#id_menu_box");
};