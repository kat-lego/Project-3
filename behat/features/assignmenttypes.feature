@moodle @assignmenttypes
Feature: Assignment Types
	Given that I am a lecturer of the system when 
	I choose to create a competitive assignment 
	then I should be able to select one of the 
	assignment types

	Scenario: Lecturer selecting different assignment types
		Given I am going to "http://1710409.ms.wits.ac.za/moodleDev/course/modedit.php?add=assign&type=&course=2&section=1&return=0&sr=0"
		And I click on "Feedback Types"
		And I click on "Competitive Assignment"
		Then I should see "Assignment Mode"