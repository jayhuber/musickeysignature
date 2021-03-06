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
 * Generates the output for musickeysignature questions.
 *
 * @copyright  2013 Jay Huber (jhuber@colum.edu)
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class qtype_musickeysignature_renderer extends qtype_renderer {

    public function formulation_and_controls(question_attempt $qa,
 	       question_display_options $options) {

		global $CFG;
		$output = "";

        $question = $qa->get_question();
		$inputname = $qa->get_qt_field_name('answer');
		$scriptname = preg_replace('/:[0-9]*_answer/', '', $inputname);
		
		$questiontext = $question->format_questiontext($qa);
		$response = $qa->get_last_qt_var('answer', 'none');


		$output .= html_writer::empty_tag('input', array(
			'id' => $inputname,
			'name' => $inputname,
			'type' => 'hidden',
			'value' => $response));

		$output .= html_writer::tag('div', $questiontext, array('class' => 'qtext'));
		$output .= html_writer::script('', $CFG->wwwroot.'/question/type/musickeysignature/swfobject/swfobject.js');
		$output .= html_writer::tag('div', "This text is replaced by the Flash movie.", array('id' => 'flashcontent_'.$question->id, 'class' => 'flashcontent'));

		$output .= isset($state->responses['']) ? $state->responses[''] : '';


        $flashvars = array(
	        'letter' => $question->orignoteletter,
	        'accidental' => $question->orignoteaccidental,
	        'mode' => $question->mode,
	        'clef' => $question->clef,
	        'response' => $response,    
	        'responseFunc' => 'setResponse_'.$scriptname.'_'.$question->id, 
	        'inMoodle' => 'true');
		$output .= html_writer::script('flashvars_'.$question->id.' = '.json_encode($flashvars).';', '');

		$swfobject = 'swfobject.embedSWF("'.$CFG->wwwroot.'/question/type/musickeysignature/keysignatures.swf", "flashcontent_'.$question->id.'", "500", "300", "8.0.0", false, flashvars_'.$question->id.');';
		$output .= html_writer::script($swfobject, '');

		$setresponse = 'function setResponse_'.$scriptname.'_'.$question->id.'(str) { if (str == "") { str = "none"; } document.getElementById("'.$inputname.'").value = str; }';
		$output .= html_writer::script($setresponse, '');

		return $output;
    }

	public function specific_feedback(question_attempt $qa) {
	    $question = $qa->get_question();
	    $response = $qa->get_last_qt_var('answer', '');

	    if ($response) {
	        return $question->format_text($question->feedback, $question->feedbackformat,
	                $qa, 'question', 'answerfeedback', $question->rightanswer);
	    } 
	}

    public function correct_response(question_attempt $qa) {
        $question = $qa->get_question();
        $response = $qa->get_last_qt_var('answer', '');
        $answer = strtolower($response);

        if (substr($answer, -1, 1) == ',') {
            $answer = substr($answer, 0, -1);
        }

        $output = "";
        $correct = 0;
        $out_answer = "";
        foreach ($question->answers as $a) {
            if ($answer == strtolower($a->answer)) {
                $correct = $a->fraction;
            }

            if ($out_answer != "") {
                $out_answer .= "|";
            }
            $out_answer .= strtolower($a->answer);
        }

        if ($correct > 0) {
            $output .= get_string('feedbackcorrectanswer', 'qtype_musickeysignature');
        } else {
            $output .= get_string('feedbackwronganswer', 'qtype_musickeysignature')."<br />";
            $output .= html_writer::tag('div', '', array('id' => 'answers'));
            $output .= str_replace("|","<br />or<br />",$out_answer);
        }

        return $output;
    }



}