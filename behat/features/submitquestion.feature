
@moodle @submitquestion
Feature: Submission of competitive solutions
	Given that I am a student of the system, when I have 
	completed a solution then I should be able to submit 
	the solution to the corresponding competitive assignment 
	posted on the web application.

	Scenario: A student wanting to submit a solution
		Given I am going to "http://1710409.ms.wits.ac.za/moodleDev/course/view.php?id=2"
		Then I should see "Topic 1"