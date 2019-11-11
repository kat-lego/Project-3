
<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
class locallibTest extends TestCase{


    public function test_get_name(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_name();
        $expected = "Competitive Assignment";
        $this->assertEquals($expected,$result,"correct!");
    }

    public function test_get_modes(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_modes();
        $expected = array("Fastest Mode", "OptiMode" , "Classic Mode", "Tournament Mode", "AI Mode");
        $this->assertEquals($expected,$result,"correct!");
    }

    public function test_get_languages(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_languages();
        $expected =  array('Java', 'Python', 'C++', 'PythonDocker');
        $this->assertEquals($expected,$result,"correct!");
    }

    public function test_get_language_code(){
        $tester=new assign_feedback_customfeedback;
        $langs = array('Java' => 2, 'Python' => 4, 'C++' => 12, 'PythonDocker' => 16);

        foreach ($langs as $lang => $code) {
            $this->assertEquals($tester->get_language_code($lang),$code);
        }
    }


    public function test_get_order_options(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_order_options();
        $expected =  array('Ascending' ,'Descending');
        $this->assertEquals($expected,$result,"correct!");
    }

    public function test_get_mode_code(){
        $tester=new assign_feedback_customfeedback;
        $modes = $tester->get_modes();
        $n = count($modes);

        for($i=0;$i<$n;$i++){
            $this->assertEquals($tester->get_mode_code($modes[$i]), $i);
        }

    }

    public function test_get_rerun_options(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_rerun_options();
        $expected = array(1,2,4,8,16);
        $this->assertEquals($expected,$result,"correct!");
    }

    public function test_get_question_numbers(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_question_numbers();

        $expected = array(1,2,3,4,5,6,7,8,9,10);
        $this->assertEquals($result,$expected);
    }

    public function test_get_time_limits(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_time_limits();
        $expected = array(1,3,5,10,20,60,120,240,360,500);
        $this->assertEquals($expected,$result,"correct!");
    }

    public function test_get_memory_limits(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_memory_limits();
        $expected = array(1,2,4,16,32,64,512,1024);
        $this->assertEquals($expected,$result,"correct!");
    }

    public function test_get_cron_option(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_cron_option();
        $expected = array(1,2,5,12,24);
        $this->assertEquals($expected,$result,"correct!");
    }

    public function test_get_testcase_filearea(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_testcase_filearea(1);
        $expected = 'competition_testcases1';
        $this->assertEquals($result,$expected);
    }



    public function test_get_callback_url(){
        $tester=new assign_feedback_customfeedback;
        $result = $tester->get_callback_url(1,1);
        $expected = 'someWebserver/mod/assign/feedback/customfeedback/update_record.php?assign_id=1&question_id=1';

        $this->assertEquals($result,$expected);
    }





    public function test_mform_title(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_title($mform);

        $expected = array(
            '<h2 id = "assignfeedback_customfeedback_tittle">Competitive Assignment Form</h2> <hr>' => array('type'=>'html')
        );

        $this->assertEquals($mform->elements,$expected);
    }

    public function test_mform_type_selection(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_type_selection($mform);

        $expected = array(
            'assignfeedback_customfeedback_mode' => 
             array('type'=>'select')
        );

        $this->assertEquals($mform->elements,$expected);
    }

    public function test_mform_ordering_selection(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_ordering_selection($mform);

        $expected = array(
            'assignfeedback_customfeedback_order' => 
             array('type'=>'select')
        );

        $this->assertEquals($mform->elements,$expected);
    }
    


    public function test_mform_rerun_selection(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_rerun_selection($mform);

        $expected = array(
            'assignfeedback_customfeedback_rerun' => 
             array('type'=>'select')
        );

        $this->assertEquals($mform->elements,$expected);
    }
    

    public function test_mform_default_score_inputbox(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_default_score_inputbox($mform);

        $expected = array(
            'assignfeedback_customfeedback_default_score' => 
             array('type'=>'text')
        );

        $this->assertEquals($mform->elements,$expected);
    }


    public function test_mform_unit_inputbox(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_unit_inputbox($mform);

        $expected = array(
            'assignfeedback_customfeedback_scoreunits' => 
             array('type'=>'text')
        );

        $this->assertEquals($mform->elements,$expected);
    }

    
    public function test_mform_language_selection(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_language_selection($mform);

        $expected = array(
            'assignfeedback_customfeedback_language' => 
             array('type'=>'select')
        );

        $this->assertEquals($mform->elements,$expected);
    }

    

    public function test_mform_grading_options(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_grading_options($mform);

        $expected = array(
            'assignfeedback_customfeedback_autograding_option' => 
             array('type'=>'checkbox'),
             'assignfeedback_customfeedback_autograding_cron' => 
             array('type'=>'select'),
             'assignfeedback_customfeedback_autograde_script' => 
             array('type'=>'filemanager')
        );

        $this->assertEquals($mform->elements,$expected);
    }

    public function test_mform_rejudge_checkbox(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_rejudge_checkbox($mform);

        $expected = array(
            'assignfeedback_customfeedback_rejudge' => 
             array('type'=>'checkbox')
        );

        $this->assertEquals($mform->elements,$expected);
    }

    public function test_mform_judge_nochange(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_judge_nochange($mform);

        $expected = array(
            'assignfeedback_customfeedback_judge_nochange' => 
             array('type'=>'checkbox')
        );

        $this->assertEquals($mform->elements,$expected);
    }
    

    public function test_mform_num_question_options(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_num_question_options($mform);

        $expected = array(
            'assignfeedback_customfeedback_numQ' => 
             array('type'=>'select')
        );

        $this->assertEquals($mform->elements,$expected);
    }

    public function test_mform_add_question(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_add_question(0,$mform);

        $expected = array(
            'assignfeedback_customfeedback_timelimitQ0' => 
             array('type'=>'select'),
            'assignfeedback_customfeedback_memorylimitQ0' => 
             array('type'=>'select'),
            'assignfeedback_customfeedback_testcasesQ0' => 
             array('type'=>'filemanager')
        );

        $this->assertEquals($mform->elements,$expected);
    }

    public function test_mform_disable_form(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->mform_disable_form($mform, null,null);

        $expected = array (
         'assignfeedback_customfeedback_mode' => true,
         'assignfeedback_customfeedback_rerun' => true,
         'assignfeedback_customfeedback_language' => true,
         'assignfeedback_customfeedback_numQ' => true,
         'assignfeedback_customfeedback_order' => true,
         'assignfeedback_customfeedback_scoreunits' => true,
         'assignfeedback_customfeedback_default_score' => true,
         'assignfeedback_customfeedback_autograding_option' => true,
         'assignfeedback_customfeedback_autograding_cron' => true,
         'assignfeedback_customfeedback_autograde_script' => true,
         'assignfeedback_customfeedback_rejudge' => true,
         'assignfeedback_customfeedback_judge_nochange' => true,
         'assignfeedback_customfeedback_timelimitQ0' => true,
         'assignfeedback_customfeedback_memorylimitQ0' => true,
         'assignfeedback_customfeedback_testcasesQ0' => true,
         'assignfeedback_customfeedback_timelimitQ1' => true,
         'assignfeedback_customfeedback_memorylimitQ1' => true,
         'assignfeedback_customfeedback_testcasesQ1' => true,
         'assignfeedback_customfeedback_timelimitQ2' => true,
         'assignfeedback_customfeedback_memorylimitQ2' => true,
         'assignfeedback_customfeedback_testcasesQ2' => true,
         'assignfeedback_customfeedback_timelimitQ3' => true,
         'assignfeedback_customfeedback_memorylimitQ3' => true,
         'assignfeedback_customfeedback_testcasesQ3' => true,
         'assignfeedback_customfeedback_timelimitQ4' => true,
         'assignfeedback_customfeedback_memorylimitQ4' => true,
         'assignfeedback_customfeedback_testcasesQ4' => true,
         'assignfeedback_customfeedback_timelimitQ5' => true,
         'assignfeedback_customfeedback_memorylimitQ5' => true,
         'assignfeedback_customfeedback_testcasesQ5' => true,
         'assignfeedback_customfeedback_timelimitQ6' => true,
         'assignfeedback_customfeedback_memorylimitQ6' => true,
         'assignfeedback_customfeedback_testcasesQ6' => true,
         'assignfeedback_customfeedback_timelimitQ7' => true,
         'assignfeedback_customfeedback_memorylimitQ7' => true,
         'assignfeedback_customfeedback_testcasesQ7' => true,
         'assignfeedback_customfeedback_timelimitQ8' => true,
         'assignfeedback_customfeedback_memorylimitQ8' => true,
         'assignfeedback_customfeedback_testcasesQ8' => true,
         'assignfeedback_customfeedback_timelimitQ9' => true,
         'assignfeedback_customfeedback_memorylimitQ9' => true,
         'assignfeedback_customfeedback_testcasesQ9' => true
        );

        $this->assertEquals($mform->disabled,$expected);
        
    }

    public function test_get_settings(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $tester->get_settings($mform);

        $expected = array(
            '<h2 id = "assignfeedback_customfeedback_tittle">Competitive Assignment Form</h2> <hr>' => array ('type'=>"html"),
            'assignfeedback_customfeedback_mode' => array ('type'=>"select"),
            'assignfeedback_customfeedback_order' => array('type'=>"select"),
            'assignfeedback_customfeedback_rerun' => array ('type'=>"select"),
            'assignfeedback_customfeedback_default_score' => array ('type'=>"text"),
            'assignfeedback_customfeedback_scoreunits' => array ('type'=>"text"),
            'assignfeedback_customfeedback_language' => array ('type'=>"select"),
            'assignfeedback_customfeedback_numQ' => array ('type'=>"select"),
            'assignfeedback_customfeedback_timelimitQ0' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ0' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ0' => array ('type' => "filemanager"),

            'assignfeedback_customfeedback_timelimitQ1' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ1' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ1' => array ('type' => "filemanager"),

            'assignfeedback_customfeedback_timelimitQ2' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ2' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ2' => array ('type' => "filemanager"),

            'assignfeedback_customfeedback_timelimitQ3' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ3' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ3' => array ('type' => "filemanager"),

            'assignfeedback_customfeedback_timelimitQ4' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ4' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ4' => array ('type' => "filemanager"),

            'assignfeedback_customfeedback_timelimitQ5' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ5' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ5' => array ('type' => "filemanager"),

            'assignfeedback_customfeedback_timelimitQ6' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ6' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ6' => array ('type' => "filemanager"),


            'assignfeedback_customfeedback_timelimitQ7' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ7' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ7' => array ('type' => "filemanager"),

            'assignfeedback_customfeedback_timelimitQ8' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ8' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ8' => array ('type' => "filemanager"),

            'assignfeedback_customfeedback_timelimitQ9' => array ('type'=>"select"),
            'assignfeedback_customfeedback_memorylimitQ9' => array ('type'=>"select"),
            'assignfeedback_customfeedback_testcasesQ9' => array ('type' => "filemanager"),

            'assignfeedback_customfeedback_rejudge' => array ('type'=>"checkbox"),
            'assignfeedback_customfeedback_judge_nochange' => array ('type'=>"checkbox"),
            'assignfeedback_customfeedback_autograding_option' => array ('type'=>"checkbox"),
            'assignfeedback_customfeedback_autograding_cron' => array ('type'=>"select"),
            'assignfeedback_customfeedback_autograde_script' => array ('type'=>"filemanager"),



        );

        $this->assertEquals($mform->elements,$expected);
    }


    public function test_ss_set_ranking_order(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $data = new stdClass();
        $data->assignfeedback_customfeedback_mode = OPTIMODE;
        $data->assignfeedback_customfeedback_order = "0";
        $result = $tester->ss_set_ranking_order($data);

        $expected =0;

        $this->assertEquals($result,$expected);
    }

    public function test_data_preprocessing(){
        $mform = new MoodleQuickForm;
        $tester=new assign_feedback_customfeedback;
        $defaults = array('assignfeedback_customfeedback_autograde_script' =>  10);
        for($i=0;$i<10;++$i){
            $defaultvalues['assignfeedback_customfeedback_testcasesQ'.$i] = $i;
        }
        $result = $tester->data_preprocessing($defaultvalues);

        // $expected =t;

        $this->assertTrue($result);
    }
    


    // public function test_save_settings(){
    //     $tester=new assign_feedback_customfeedback;
    //     $data = new stdClass();
    //     $data->assignfeedback_witsoj_enabled = true;


    //     $data->assignfeedback_customfeedback_mode = 0;
    //     $data->assignfeedback_customfeedback_language = 0;
    //     $data->assignfeedback_customfeedback_numQ = 0;

    //     $result = $tester->save_settings($data);
    //     $this->assertTrue(!$result);

    //     $data->assignfeedback_witsoj_enabled = false;
    //     for($i=0;$i<10;$i++){
    //         eval('$data->assignfeedback_customfeedback_timelimitQ'.$i.'=1;');
    //         eval('$data->assignfeedback_customfeedback_memorylimitQ'.$i.'=1;');
    //         eval('$data->assignfeedback_customfeedback_testcasesQ'.$i.'=1;');
    //     }



    //      $result = $tester->save_settings($data);
    //     $this->assertTrue($result);

    // }

    public function test_is_feedback_modified(){
        $tester = new assign_feedback_customfeedback;
        $data = new stdClass();
        $data->assignfeedbackcomments_editor = array("text"=>'comment');
        $grade = new stdClass();
        $grade->id = 2;

        $result = $tester->is_feedback_modified($grade, $data);
        $this->assertTrue(!$result);

        $data->assignfeedbackcomments_editor = array("text"=>'comment2');
        $grade->id = 2;

         $result = $tester->is_feedback_modified($grade, $data);
        $this->assertTrue($result);
    }


    public function test_can_upgrade(){
            $tester=new assign_feedback_customfeedback;
            $result = $tester->can_upgrade("upload", 2011112900);
            $expected = true;
            $this->assertEquals($expected,$result,"correct!"); 

            $result = $tester->can_upgrade("none", 2011112900);
            $expected = false;
            $this->assertEquals($expected,$result,"correct!"); 
    }
  
    // public function test_format_for_gradebook(){
    //     $this->assertEquals(2, 2,"correct!"); 
    // }
    
    public function test_supports_quickgrading(){
            $tester=new assign_feedback_customfeedback;
            $result = $tester->supports_quickgrading();
            $expected = false;
            $this->assertEquals($expected,$result,"correct!"); 
    }
	
	public function test_get_grading_actions(){
		$tester=new assign_feedback_customfeedback;
		$result = $tester->get_grading_actions();
        $expected = array();
        $this->assertEquals($expected,$result,"correct!"); 
	}
 
	public function test_get_grading_batch_operations(){
		$tester=new assign_feedback_customfeedback;
		$result = $tester-> get_grading_batch_operations();
        $expected = array();
        $this->assertEquals($expected,$result,"correct!"); 
	}
 
 
}
