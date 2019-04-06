
@moodle @viewleaderboard
Feature: Viewing of the leaderboard
	Given that I am a lecturer of the system when I am 
	on the platform then I should be able to see the 
	leaderboard


	Scenario: A lecturer/student wanting to view the leaderboard
		Given I am going to "http://1710409.ms.wits.ac.za/moodleDev/course/view.php?id=2"
		Then I should see "Leaderboard"