@moodle @ca_integration
Feature: Submit Tab Lecturer Post
	Given that I am a lecturer of the system,
	when I login into the web platform then 
	I should be able to see that the web 
	application has been integrated into Moodle.

	Scenario: Posting a competitive assignment as a lecturer
		Given I am going to "http://1710409.ms.wits.ac.za/moodleDev/?redirect=0"
		And I click on "math_moss"
		Then I should see "Course: math_moss"
	
