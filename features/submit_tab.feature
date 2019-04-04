@wits @sub_wits_submit_tab
Feature: Submit Tab Lecturer Post
	Given that I am a lecturer of the system,
	when I login into the web platform then 
	I should be able to see that the web 
	application has been integrated into Moodle.

	Scenario: Posting a competitive assignment as a lecturer
		Given I am going to "http://1710409.ms.wits.ac.za/moodleDev/course/modedit.php?add=assign&type=&course=2&section=1&return=0&sr=0"
		Then I should see:
		"""
		SAMSUNG
		"""


