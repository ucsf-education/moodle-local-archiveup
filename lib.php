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

/**
 * Archive Up plugin cron task
 */
function local_archiveup_cron() {
    global $DB, $CFG;

    $dbman = $DB->get_manager();
    $timenow  = time();
    mtrace(" ArchiveUp: Running archive tasks (current time: ".date('r',$timenow)."...");

    // c.f. Delete old logs in lib/cronlib.php
    $loglifetime = get_config('local_archiveup', 'loglifetime');
    if (!empty($loglifetime)) {  // value in days

        $loglifetime = $timenow - ($loglifetime * 3600 * 24);
        $lifetimep = array($loglifetime);

        $archivetablename = "log_archiveup";
        if ($dbman->table_exists($archivetablename)) {
            mtrace("  Looking for old record(s) in legacy log table...");

            $start = time();
            $totalrecords = 0;
            while ($min = $DB->get_field_select("log", "MIN(time)", "time < ?", $lifetimep)) {
                // Break this down into chunks to avoid transaction for too long and generally thrashing database.
                // Experiments suggest deleting one day takes up to a few seconds; probably a reasonable chunk size usually.
                // If the cleanup has just been enabled, it might take e.g a month to clean the years of logs.
                $params = array(min($min + 3600 * 24, $loglifetime));
                $totalrecords += $DB->count_records_select("log", "time < ?", $params);

                $transaction = $DB->start_delegated_transaction();
                $DB->execute("INSERT INTO {{$archivetablename}} SELECT * FROM {log} WHERE time < ?", $params);
                $DB->delete_records_select("log", "time < ?", $params);
                $transaction->allow_commit();
                if (time() > $start + 300) {
                    // Do not churn on log deletion for too long each run.
                    break;
                }
            }
            mtrace("  ... archived $totalrecords old record(s) from legacy log table.");
        }

        $archivetablename = "logstore_standard_log_au";
        if ($dbman->table_exists($archivetablename)) {
            mtrace("  Looking for old record(s) in standard logstore...");

            $start = time();
            $totalrecords = 0;
            while ($min = $DB->get_field_select("logstore_standard_log", "MIN(timecreated)", "timecreated < ?", $lifetimep)) {
                // Break this down into chunks to avoid transaction for too long and generally thrashing database.
                // Experiments suggest deleting one day takes up to a few seconds; probably a reasonable chunk size usually.
                // If the cleanup has just been enabled, it might take e.g a month to clean the years of logs.
                $params = array(min($min + 3600 * 24, $loglifetime));
                $totalrecords += $DB->count_records_select("logstore_standard_log", "timecreated < ?", $params);

                $transaction = $DB->start_delegated_transaction();
                $DB->execute("INSERT INTO {{$archivetablename}} SELECT * FROM {logstore_standard_log} WHERE timecreated < ?", $params);
                $DB->delete_records_select("logstore_standard_log", "timecreated < ?", $params);
                $transaction->allow_commit();
                if (time() > $start + 300) {
                    // Do not churn on log deletion for too long each run.
                    break;
                }
            }
            mtrace("  ... archived $totalrecords old record(s) from standard logstore.");
        }
    }

    // c.f. grade_cron() in lib/gradelib.php
    $gradehistorylifetime = get_config('local_archiveup', 'gradehistorylifetime');
    if (!empty($gradehistorylifetime)) {
        $histlifetime = $timenow - ($gradehistorylifetime * 3600 * 24);
        $tables = array('grade_outcomes_history', 'grade_categories_history', 'grade_items_history', 'grade_grades_history', 'scale_history');
        foreach ($tables as $table) {
            $archivetable = $table . '_au';
            if ($dbman->table_exists($archivetable)) {
                mtrace("  Looking for old grade history records in '$table'...");
                $totalrecords = $DB->count_records_select($table, "timemodified < ?", array($histlifetime));
                if ($totalrecords > 0) {
                    $transaction = $DB->start_delegated_transaction();
                    $DB->execute("INSERT INTO {{$archivetable}} SELECT * FROM {{$table}} WHERE timemodified < ?", array($histlifetime));
                    $DB->delete_records_select($table, "timemodified < ?", array($histlifetime));
                    $transaction->allow_commit();
                    mtrace("  ... moved $totalrecords old grade history record(s) in '$table' to '$archivetable'.");
                } else {
                    mtrace("  ... nothing to archive.");
                }
            }
        }
    }

    mtrace(" ... archived old log records.");
}
