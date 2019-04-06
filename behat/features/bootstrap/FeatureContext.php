<?php
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Session;
/**
 * Features context.
 */
class FeatureContext implements Context{
	private $driver;
	private $session;
	public function __construct() {
		$this->driver = new GoutteDriver();
		$this->session = new Session($this->driver);
		$this->session->start();
	}
	/**
	 * @Given /^I am going to "([^"]*)"$/
	 */
	public function iAmGoingTo($url) {
		$this->session->visit($url);
	}
	
	/**
     * @Then I should see :arg1
     */
    public function iShouldSee($arg1)
    {
        $responseBody = $this->session->getPage()->find('css', 'title');
        if (($responseBody->getText()) != $arg1) {
            throw new \Exception("The requested string: [".$arg1."] could not be found. Instead we found: [".$responseBody->getText()."]");
        }
    }

	/**
     * @Given I click on :arg1
     */
    public function iClickOn($arg1)
    {
        $clickOn = $this->session->getPage()->find("xpath",".//a[text()='" . $arg1 . "']")->click();
    }

    /**
     * @Given I click enter :arg1
     */
    public function iEnterMessage($arg1)
    {
        $iEnterMessage = $this->session->getPage()->find('css', 'col-md-9 form-inline felement');
        $iEnterMessage->setValue($arg1);
    }
}
