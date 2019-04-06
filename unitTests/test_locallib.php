
<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\DbUnit\TestCaseTrait;
require_once('locallib.php');
class locallibTest extends TestCase{
  
      public function test_format_for_gradebook(){
        $this->assertEquals(2, 2); 
      }
      public function test_getmodes(){
            $tester=new assign_feedback_customfeedback;
            $result = $tester->getmodes();
            $expected = array("Classic Mode", "Fastest Mode", "Tournament Mode", "AI Mode");
            $this->assertEquals($expected,$result,"Correct");
      }

}
