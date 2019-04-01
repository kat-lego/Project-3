@wits @sub_wits_submit_tab
Feature: Submit Tab Lecturer Post
	Given that I am a lecturer of the system,
	when I login into the web platform then 
	I should be able to see that the web 
	application has been integrated into Moodle.

	Scenario: Posting a competitive assignment as a lecturer
		Given I am on "moodleDev/course/view.php?id=3&notifyeditingon=1"
		When I press "Add an activity or resource"
		And I press "Assignment"
		And I press "Add"
		Then I should see "Adding a new Assignment"


