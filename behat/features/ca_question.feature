@moodle @postquestion
Feature: Posting of competitive questions
	Given I am a lecturer of the system, when 
	I specify the details of a competitive question then 
	I should be able to post the question onto the web application.

	Scenario: Posting a competitive assignment as a lecturer
		Given I am going to "http://1710409.ms.wits.ac.za/moodleDev/course/modedit.php?add=assign&type=&course=2&section=1&return=0&sr=0"
		And I enter "Comptetitive Assignment - HackersOnly"
		And I click on "Save and Display"
		Then I should see "Course: math_moss"