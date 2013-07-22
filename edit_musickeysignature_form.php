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
 * @copyright  2009 Eric Bisson (ebrisson@winona.edu)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
* musickeysignature question editing form definition.
*
* @copyright  2013 Jay Huber (jhuber@colum.edu)
* @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
*/


class qtype_musickeysignature_edit_form extends question_edit_form {

    protected function definition_inner($mform) {
        $mform->addElement('select', 'orignoteletter', 
            get_string('orignoteletter','qtype_musickeysignature'),
            array( "C"  => get_string('C','qtype_musickeysignature'),
            "D"  => get_string('D','qtype_musickeysignature'),
            "E"  => get_string('E','qtype_musickeysignature'),
		    "F"  => get_string('F','qtype_musickeysignature'),
		    "G"  => get_string('G','qtype_musickeysignature'),
		    "A"  => get_string('A','qtype_musickeysignature'),
		    "B"  => get_string('B','qtype_musickeysignature'),
		));
		
		$mform->addHelpButton('orignoteletter', 'orignoteletter', 'qtype_musickeysignature');

        $mform->addElement('select', 'orignoteaccidental', 
            get_string('orignoteaccidental','qtype_musickeysignature'),
            array( ""  => "&#9838",
            "#"  => "&#9839",
            "b"  => "&#9837",
        ));

		$mform->addHelpButton('orignoteaccidental', 'orignoteaccidental', 'qtype_musickeysignature');

        $mform->addElement('select', 'mode', get_string('mode','qtype_musickeysignature'), 
            array( "major"  => get_string('mode_major','qtype_musickeysignature'),
            "minor"  => get_string('mode_minor','qtype_musickeysignature'),
        ));

		$mform->addHelpButton('mode', 'mode', 'qtype_musickeysignature');

        $mform->addElement('select', 'clef', get_string('clef','qtype_musickeysignature'),
            array( "t"  => get_string('treble','qtype_musickeysignature'),
            "b"  => get_string('bass','qtype_musickeysignature'),
            ));

		$mform->addHelpButton('clef', 'clef', 'qtype_musickeysignature');

		$this->add_per_answer_fields($mform, get_string('answerno', 'qtype_musickeysignature', '{no}'),
		        	question_bank::fraction_options(), 1, 1);

		//this adds the hint options
		$this->add_interactive_settings();	
    }

    public function validation($data, $files) {
        $errors = parent::validation($data, $files);

        // Make sure the selected key has a key signature
        if (!(
            (
            ($data['mode']=="major") &&
            (
            (($data['orignoteletter']=="C") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="G") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="D") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="A") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="E") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="B") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="F") && ($data['orignoteaccidental']=="#")) ||
            (($data['orignoteletter']=="C") && ($data['orignoteaccidental']=="#")) ||
            (($data['orignoteletter']=="F") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="B") && ($data['orignoteaccidental']=="b")) ||
            (($data['orignoteletter']=="E") && ($data['orignoteaccidental']=="b")) ||
            (($data['orignoteletter']=="A") && ($data['orignoteaccidental']=="b")) ||
            (($data['orignoteletter']=="D") && ($data['orignoteaccidental']=="b")) ||
            (($data['orignoteletter']=="G") && ($data['orignoteaccidental']=="b")) ||
            (($data['orignoteletter']=="C") && ($data['orignoteaccidental']=="b")) 
            )
            ||
            (
            ($data['mode']=="minor") &&
            (
            (($data['orignoteletter']=="A") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="E") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="B") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="F") && ($data['orignoteaccidental']=="#")) ||
            (($data['orignoteletter']=="C") && ($data['orignoteaccidental']=="#")) ||
            (($data['orignoteletter']=="G") && ($data['orignoteaccidental']=="#")) ||
            (($data['orignoteletter']=="D") && ($data['orignoteaccidental']=="#")) ||
            (($data['orignoteletter']=="A") && ($data['orignoteaccidental']=="#")) ||
            (($data['orignoteletter']=="D") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="G") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="C") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="F") && ($data['orignoteaccidental']=="")) ||
            (($data['orignoteletter']=="B") && ($data['orignoteaccidental']=="b")) ||
            (($data['orignoteletter']=="E") && ($data['orignoteaccidental']=="b")) ||
            (($data['orignoteletter']=="A") && ($data['orignoteaccidental']=="b")) 
            ))))
           ) {
            $errors['orignoteletter']=get_string('nonexistentkey','qtype_musickeysignature');
        }

        $answers = $data['answer'];
        $answercount = 0;
        $maxgrade = false;
        foreach ($answers as $key => $answer) {
            $trimmedanswer = trim($answer);
            if ($trimmedanswer !== ''){
                $answercount++;
                if ($data['fraction'][$key] == 1) {
                    $maxgrade = true;
                }
            } else if ($data['fraction'][$key] != 0 || !html_is_blank($data['feedback'][$key]['text'])) {
                $errors["answer[$key]"] = get_string('answermustbegiven', 'qtype_shortanswer');
                $answercount++;
            }
        }

        if ($answercount==0){
            $errors['answer[0]'] = get_string('notenoughanswers', 'question', 1);
        }
        if ($maxgrade == false) {
            $errors['fraction[0]'] = get_string('fractionsnomax', 'question');
        }

        return $errors;
    }

    protected function data_preprocessing($question) {
        $question = parent::data_preprocessing($question);
        $question = $this->data_preprocessing_answers($question);
        $question = $this->data_preprocessing_hints($question);

        return $question;
    }

    public function qtype() {
        return 'musickeysignature';
    }
}
