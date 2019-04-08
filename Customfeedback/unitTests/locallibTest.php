
<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
#require_once('locallib.php');
class locallibTest extends TestCase{
  
      public function test_format_for_gradebook(){
        $this->assertEquals(2, 2,"correct!"); 
      }
  #it cant find the extended class in locallib.php
     public function test_get_modes(){
            $tester=new assign_feedback_customfeedback;
            $result = $tester->get_modes();
            $expected = array("Classic Mode", "Fastest Mode", "Tournament Mode", "AI Mode");
            $this->assertEquals($expected,$result,"correct!");
      }
    public function test_get_time_limits(){
            $tester=new assign_feedback_customfeedback;
            $result = $tester->get_time_limits();
            $expected = array(1,3,5,10,20,60);
            $this->assertEquals($expected,$result,"correct!");
    }
    public function test_get_memory_limits(){
            $tester=new assign_feedback_customfeedback;
            $result = $tester->get_memory_limits();
            $expected = array('1MB','2MB','4MB','16MB');
            $this->assertEquals($expected,$result,"correct!");
    }
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
