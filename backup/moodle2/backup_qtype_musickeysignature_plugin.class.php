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
 * @package    qtype
 * @subpackage musickeysignature
 * @copyright  2013 Jay Huber (jhuber@colum.edu)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
 
defined('MOODLE_INTERNAL') || die();

/**
* Provides the information to backup musickeysignature questions
*
* @copyright  2013 Jay Huber (jhuber@colum.edu)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/

class backup_qtype_musickeysignature_plugin extends backup_qtype_plugin {
    
    /**
     * Returns the qtype information to attach to question element
     */
    protected function define_question_plugin_structure() {

        // Define the virtual plugin element with the condition to fulfill
        $plugin = $this->get_plugin_element(null, '../../qtype', 'musickeysignature');

        // Create one standard named plugin element (the visible container)
        $pluginwrapper = new backup_nested_element($this->get_recommended_name());

        // connect the visible container ASAP
        $plugin->add_child($pluginwrapper);

        // This qtype uses standard question_answers, add them here
        // to the tree before any other information that will use them
        $this->add_question_question_answers($pluginwrapper);

        // Now create the qtype own structures
        $musickeysignature = new backup_nested_element('musickeysignature', array('id'), array(
            'orignoteletter', 'orignoteaccidental', 'mode', 'clef'));

        // Now the own qtype tree
        $pluginwrapper->add_child($musickeysignature);

        // set source to populate the data
        $musickeysignature->set_source_table('question_musickeysignature',
                array('questionid' => backup::VAR_PARENTID));

        // don't need to annotate ids nor files

        return $plugin;
    }
    
    
    
}