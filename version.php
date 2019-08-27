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
 * @package   assignfeedback_customfeedback
 * @copyright 2019, Moses Modupi <1614669@student.wits.ac.za>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version = 2019041408;
$plugin->requires = 2018050800; // Moodle 3.6
$plugin->cron = 0;
$plugin->component = 'assignfeedback_customfeedback';
$plugin->maturity = MATURITY_ALPHA;
$plugin->release = 'v1.0';
