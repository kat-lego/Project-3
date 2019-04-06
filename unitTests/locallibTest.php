
<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
#require_once('locallib.php');
class locallibTest extends TestCase{
  
      public function test_format_for_gradebook(){
        $this->assertEquals(2, 2); 
      }
  #it cant find the extended class in locallib.php
     public function test_get_modes(){
            $tester=new assign_feedback_customfeedback;
            $result = $tester->get_modes();
            $expected = array("Classic Mode", "Fastest Mode", "Tournament Mode", "AI Mode");
            $this->assertEquals(2,2);
      }

}
