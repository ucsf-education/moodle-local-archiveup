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

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Archive Up';
$string['international'] = 'All languages';
$string['loglifetime'] = 'Archive logs older than';
$string['configloglifetime'] = 'This specifies the length of time you want to keep logs about user activity. Logs that are older than this age are automatically archived to a different table. It is best to keep logs as long as possible, in case you need them, but if you have a very busy server and are experiencing performance problems, then you may want to lower the log lifetime. Values lower than 30 are not recommended because statistics may not work properly.';
$string['neverarchivelogs'] = 'Never archive logs';
$string['loglifetimewarning'] = '<strong>Cleanup logs</strong> is currently set to <em>{$a} days</em>.  To prevent loss of important log data, please visit the <a href="settings.php?section=cleanup">cleanup setting page</a> and set "<strong>Keep logs for</strong>" to <em>Never delete logs</em>.';
