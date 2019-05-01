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
 * This file adds the settings pages to the navigation menu
 * @package   assignfeedback_customfeedback
 * @copyright 2019, Moses Modupi <1614669@student.wits.ac.za>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;


$settings->add(new admin_setting_configcheckbox('assignfeedback_customfeedback/default',
    new lang_string('default', 'assignfeedback_customfeedback'),
    new lang_string('default_help', 'assignfeedback_customfeedback'), 0));

$settings->add(new admin_setting_configtext('assignfeedback_customfeedback/basepath',
                   new lang_string('basepath', 'assignfeedback_customfeedback'),
                   new lang_string('basepath_help', 'assignfeedback_customfeedback'), '/var/www/'));

$settings->add(new admin_setting_configtext('assignfeedback_customfeedback/handler',
                   new lang_string('handler', 'assignfeedback_customfeedback'),
                   new lang_string('handler_help', 'assignfeedback_customfeedback'), ''));

$settings->add(new admin_setting_configtext('assignfeedback_customfeedback/prefix',
                   new lang_string('prefix', 'assignfeedback_customfeedback'),
                   new lang_string('prefix_help', 'assignfeedback_customfeedback'), 'sub'));


$settings->add(new admin_setting_configtext('assignfeedback_customfeedback/secret',
                   new lang_string('secret', 'assignfeedback_customfeedback'),
                   new lang_string('secret_help', 'assignfeedback_customfeedback'), md5("Secret")));
/*
$settings->add(new admin_setting_configtext('assignfeedback_customfeedback/languages',
                   new lang_string('languages', 'assignfeedback_customfeedback'),
                   new lang_string('languages_help', 'assignfeedback_customfeedback'), ''));
*/
$settings->add(new admin_setting_configtext('assignfeedback_customfeedback/maxquestions',
                   new lang_string('maxquestions', 'assignfeedback_customfeedback'),
                   new lang_string('maxquestions_help', 'assignfeedback_customfeedback'), '10'));

if (isset($CFG->maxbytes)) {

    $name = new lang_string('maximumtestcasesize', 'assignfeedback_customfeedback');
    $description = new lang_string('configmaxbytes', 'assignfeedback_customfeedback');

    $maxbytes = get_config('assignfeedback_customfeedback', 'maxbytes');
    $element = new admin_setting_configselect('assignfeedback_customfeedback/maxbytes',
                                              $name,
                                              $description,
                                              $CFG->maxbytes,
                                              get_max_upload_sizes($CFG->maxbytes, 0, 0, $maxbytes));
    $settings->add($element);
}
