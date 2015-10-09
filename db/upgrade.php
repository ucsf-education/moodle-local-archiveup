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
 * @param int $oldversion the version we are upgrading from
 * @return bool result
 */
function xmldb_local_archiveup_upgrade($oldversion) {
    global $CFG, $DB;

    require_once($CFG->libdir.'/db/upgradelib.php');   // Core Upgrade-related functions

    $dbman = $DB->get_manager();

    // Moodle v2.4.6 release upgrade line
    // Put any upgrade step following this

    if ($oldversion < 2013100400.01) {

        // Define table grade_outcomes_history_au to be created
        $table = new xmldb_table('grade_outcomes_history_au');

        // Adding fields to table grade_outcomes_history_au
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('action', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('oldid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('source', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('loggeduser', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('shortname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('fullname', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('scaleid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('descriptionformat', XMLDB_TYPE_INTEGER, '2', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table grade_outcomes_history_au
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('oldid', XMLDB_KEY_FOREIGN, array('oldid'), 'grade_outcomes', array('id'));
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        $table->add_key('scaleid', XMLDB_KEY_FOREIGN, array('scaleid'), 'scale', array('id'));
        $table->add_key('loggeduser', XMLDB_KEY_FOREIGN, array('loggeduser'), 'user', array('id'));

        // Adding indexes to table grade_outcomes_history_au
        $table->add_index('action', XMLDB_INDEX_NOTUNIQUE, array('action'));

        // Conditionally launch create table for grade_outcomes_history_au
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Define table grade_categories_history_au to be created
        $table = new xmldb_table('grade_categories_history_au');

        // Adding fields to table grade_categories_history_au
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('action', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('oldid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('source', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('loggeduser', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('parent', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('depth', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('path', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('fullname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('aggregation', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('keephigh', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('droplow', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('aggregateonlygraded', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('aggregateoutcomes', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('aggregatesubcats', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('hidden', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');

        // Adding keys to table grade_categories_history_au
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('oldid', XMLDB_KEY_FOREIGN, array('oldid'), 'grade_categories', array('id'));
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        $table->add_key('parent', XMLDB_KEY_FOREIGN, array('parent'), 'grade_categories', array('id'));
        $table->add_key('loggeduser', XMLDB_KEY_FOREIGN, array('loggeduser'), 'user', array('id'));

        // Adding indexes to table grade_categories_history_au
        $table->add_index('action', XMLDB_INDEX_NOTUNIQUE, array('action'));

        // Conditionally launch create table for grade_categories_history_au
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Define table grade_items_history_au to be created
        $table = new xmldb_table('grade_items_history_au');

        // Adding fields to table grade_items_history_au
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('action', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('oldid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('source', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('loggeduser', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('categoryid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('itemname', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('itemtype', XMLDB_TYPE_CHAR, '30', null, XMLDB_NOTNULL, null, null);
        $table->add_field('itemmodule', XMLDB_TYPE_CHAR, '30', null, null, null, null);
        $table->add_field('iteminstance', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('itemnumber', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('iteminfo', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('idnumber', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('calculation', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('gradetype', XMLDB_TYPE_INTEGER, '4', null, XMLDB_NOTNULL, null, '1');
        $table->add_field('grademax', XMLDB_TYPE_NUMBER, '10, 5', null, XMLDB_NOTNULL, null, '100');
        $table->add_field('grademin', XMLDB_TYPE_NUMBER, '10, 5', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('scaleid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('outcomeid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('gradepass', XMLDB_TYPE_NUMBER, '10, 5', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('multfactor', XMLDB_TYPE_NUMBER, '10, 5', null, XMLDB_NOTNULL, null, '1.0');
        $table->add_field('plusfactor', XMLDB_TYPE_NUMBER, '10, 5', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('aggregationcoef', XMLDB_TYPE_NUMBER, '10, 5', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('sortorder', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('hidden', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('locked', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('locktime', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('needsupdate', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('display', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('decimals', XMLDB_TYPE_INTEGER, '1', null, null, null, null);

        // Adding keys to table grade_items_history_au
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('oldid', XMLDB_KEY_FOREIGN, array('oldid'), 'grade_items', array('id'));
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        $table->add_key('categoryid', XMLDB_KEY_FOREIGN, array('categoryid'), 'grade_categories', array('id'));
        $table->add_key('scaleid', XMLDB_KEY_FOREIGN, array('scaleid'), 'scale', array('id'));
        $table->add_key('outcomeid', XMLDB_KEY_FOREIGN, array('outcomeid'), 'grade_outcomes', array('id'));
        $table->add_key('loggeduser', XMLDB_KEY_FOREIGN, array('loggeduser'), 'user', array('id'));

        // Adding indexes to table grade_items_history_au
        $table->add_index('action', XMLDB_INDEX_NOTUNIQUE, array('action'));

        // Conditionally launch create table for grade_items_history_au
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }


        // Define table scale_history_au to be created
        $table = new xmldb_table('scale_history_au');

        // Adding fields to table scale_history_au
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('action', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('oldid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('source', XMLDB_TYPE_CHAR, '255', null, null, null, null);
        $table->add_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('loggeduser', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('name', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('scale', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);
        $table->add_field('description', XMLDB_TYPE_TEXT, null, null, XMLDB_NOTNULL, null, null);

        // Adding keys to table scale_history_au
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));
        $table->add_key('oldid', XMLDB_KEY_FOREIGN, array('oldid'), 'scale', array('id'));
        $table->add_key('courseid', XMLDB_KEY_FOREIGN, array('courseid'), 'course', array('id'));
        $table->add_key('loggeduser', XMLDB_KEY_FOREIGN, array('loggeduser'), 'user', array('id'));

        // Adding indexes to table scale_history_au
        $table->add_index('action', XMLDB_INDEX_NOTUNIQUE, array('action'));

        // Conditionally launch create table for scale_history_au
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // archiveup savepoint reached
        upgrade_plugin_savepoint(true, 2013100400.01, 'local', 'archiveup');
    }

    if ($oldversion < 2015100400) {

        // Define table logstore_standard_log_au to be created.
        $table = new xmldb_table('logstore_standard_log_au');

        // Adding fields to table grade_outcomes_history_au
        $table->add_field('id', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, XMLDB_SEQUENCE, null);
        $table->add_field('eventname', XMLDB_TYPE_CHAR, '255', null, XMLDB_NOTNULL, null, null);
        $table->add_field('component', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('action', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('target', XMLDB_TYPE_CHAR, '100', null, XMLDB_NOTNULL, null, null);
        $table->add_field('objecttable', XMLDB_TYPE_CHAR, '50', null, null, null, null);
        $table->add_field('objectid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('crud', XMLDB_TYPE_CHAR, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('edulevel', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, null);
        $table->add_field('contextid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('contextlevel', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('contextinstanceid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('courseid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('relateduserid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);
        $table->add_field('anonymous', XMLDB_TYPE_INTEGER, '1', null, XMLDB_NOTNULL, null, '0');
        $table->add_field('other', XMLDB_TYPE_TEXT, null, null, null, null, null);
        $table->add_field('timecreated', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, null);
        $table->add_field('origin', XMLDB_TYPE_CHAR, '10', null, null, null, null);
        $table->add_field('ip', XMLDB_TYPE_CHAR, '45', null, null, null, null);
        $table->add_field('realuserid', XMLDB_TYPE_INTEGER, '10', null, null, null, null);

        // Adding keys to table grade_outcomes_history_au
        $table->add_key('primary', XMLDB_KEY_PRIMARY, array('id'));

        // Adding indexes to table grade_outcomes_history_au
        $table->add_index('timecreated', XMLDB_INDEX_NOTUNIQUE, array('timecreated'));
        $table->add_index('course-time', XMLDB_INDEX_NOTUNIQUE, array('courseid', 'anonymous', 'timecreated'));
        $table->add_index('user-module', XMLDB_INDEX_NOTUNIQUE, array('userid', 'contextlevel', 'contextinstanceid', 'crud', 'edulevel', 'timecreated'));

        // Conditionally launch create table for logstore_standard_log_au
        if (!$dbman->table_exists($table)) {
            $dbman->create_table($table);
        }

        // Standard savepoint reached.
        upgrade_plugin_savepoint(true, 2015100400, 'local', 'archiveup');
    }
    return true;
}
