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

    // Run cleanup core cron jobs, but not every time since they aren't too important.
    // These don't have a timer to reduce load, so we'll use a random number
    // to randomly choose the percentage of times we should run these jobs.
    srand ((double) microtime() * 10000000);
    $random100 = rand(0,100);
    if ($random100 < 20) {     // Approximately 20% of the time.
        mtrace(" ArchiveUp: Running archive tasks...");

        $timenow  = time();
        mtrace(" ArchiveUp current time: ".date('r',$timenow)."\n\n");

        $loglifetime = get_config('local_archiveup', 'loglifetime');
        if (!empty($loglifetime)) {  // value in days

            $loglifetime = $timenow - ($loglifetime * 3600 * 24);

            $dbman = $DB->get_manager();
            $archivetablename = "log_archiveup";

            if ($dbman->table_exists($archivetablename)) {
                mtrace(" Looking for old logs...");
                $totalrecords = $DB->count_records_select("log", "time < ?", array($loglifetime));

                if ($totalrecords > 0) {
                    $DB->execute("INSERT INTO {{$archivetablename}} SELECT * FROM {log} WHERE time < ?", array($loglifetime));
                    $DB->delete_records_select("log", "time < ?", array($loglifetime));
                } 
                mtrace("  archived $totalrecords old log record(s).");
            }
        }

        mtrace(" Archived old log records.");
    }
}
