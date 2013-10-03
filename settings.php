<?php
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
 * local plugin archive up
 *
 * @package    local_archiveup
 * @author     Carson Tam <carson.tam@ucsf.edu>
 * @copyright  2013 University of California, San Francisco {@link http://www.ucsf.edu}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG, $PAGE;

if ($hassiteconfig) { // speedup for non-admins, add all caps used on this page
    // New settings page
    $page = new admin_settingpage('archiveup', get_string('pluginname', 'local_archiveup'));

    // Archive logs
    $warning = '';
    if (!empty($CFG->loglifetime)) {
        $warning = '<div class="form-warning">' . new lang_string('loglifetimewarning', 'local_archiveup', $CFG->loglifetime) . '</div>';
    }

    $page->add(new admin_setting_configselect('local_archiveup/loglifetime', new lang_string('loglifetime', 'local_archiveup'),
                                              $warning . new lang_string('configloglifetime', 'local_archiveup'), 0, 
                                              array(0 => new lang_string('neverarchivelogs', 'local_archiveup'),
                                                    1000 => new lang_string('numdays', '', 1000),
                                                    365 => new lang_string('numdays', '', 365),
                                                    180 => new lang_string('numdays', '', 180),
                                                    150 => new lang_string('numdays', '', 150),
                                                    120 => new lang_string('numdays', '', 120),
                                                    90 => new lang_string('numdays', '', 90),
                                                    60 => new lang_string('numdays', '', 60),
                                                    35 => new lang_string('numdays', '', 35),
                                                    10 => new lang_string('numdays', '', 10),
                                                    5 => new lang_string('numdays', '', 5),
                                                    2 => new lang_string('numdays', '', 2))));

    // Add settings page to navigation tree
    $ADMIN->add('localplugins', $page);
}
