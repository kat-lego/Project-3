# Project-3
# Product description
#### A competitive programming platform for Computer Science students.

This is a platform where lectures can setup Competitive Assignments (or CAs for short) for their eager pupils. A Competitive Assignment will follow a simple recipe, a set of challenges will be posed to students and they shall be ranked based on some metric defined by the CA creator. These Competitive Assignments run for some time and at the end of each one, students will be allocated marks depending on how they rank on the leader board.

This platform supports different ways to structure the Competitive Assignments. Here are descriptions for the Assignment modes we will support.
*  **Classic mode**: This follows the format of your typical programming competition. The ranking of the competitors is according to the number of questions they solve and tie breakers are the number of attempts made (less is better) followed by how soon they made their last correct submission. 

* ***Fastest mode***: Can your code put the pedal to the medal? Given a set of problems your job is to submit solutions to each that will run faster than the solutions of your peers. The ranking will be based on an ascending order of the average runtime for all problems. Ties will be broken by who submitted the correct solution first.

*  ***AI mode***: Given a set of rules for some multiplayer game and a game agent, you are to give your agent enough 'skill' to smash your opposition. The better your agent performs, the higher it will be ranked.

*  ***Tournament mode*** : Players (Students) will be put head-to-head in a round robin type of setting. Players(Students) will be required to submit solutions (in code) for a given problem and the code will be ran against every other players who have made a submission for the assignment. For every matching between two players, 1 point will be assigned to each player in the case of a tie, otherwise 3 points and 0 points will be assigned to the winner and looser respectively. Ranking will be done based on how many points a student has.

The platform is an integration to the wits moodle site, adding a new dimension to the marking system. The platform will be limited to programming languages featured in the Wits Computer Science Curriculum. Here is a mentioning of the languages.
*  Python 3.x
* Java 1.8
* C++ 11
* mySQL
