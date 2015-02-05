<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Event\ScenarioEvent,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\RawMinkContext;
use Behat\MinkExtension\Context\MinkContext;

//
// Require 3rd-party libraries here:
//
   require_once 'PHPUnit/Autoload.php';
   require_once 'PHPUnit/Framework/Assert/Functions.php';
//

/**
 * Features context.
 */
class FeatureContext extends RawMinkContext
{
	
	/** @AfterScenario */
	public static function errors(ScenarioEvent $event)
	{
		if ($event->getResult() == '4'){
			print 'URL:' . $event->getContext()->getSubcontext('mink')->printCurrentUrl();
			print 'Last Response: ' . $event->getContext()->getSubcontext('mink')->printLastResponse();
		}
	}
	
    /**
     * Initializes context.
     * Every scenario gets it's own context object.
     *
     * @param array $parameters context parameters (set them up through behat.yml)
     */
    public function __construct(array $parameters)
    {
        $this->useContext('mink', new MinkContext);
    }

    /**
     * @Then /^I should see the following in the repeated "([^"]*)" element within the context of the "([^"]*)" element:$/
     */
    public function assertRepeatedElementContainsText($element, $parentElement, TableNode $table)
    {
        $elementSelector = $parentElement . ' ' . $element;
        $elements = $this->getSession()->getPage()->findAll('css', $elementSelector);
    
        foreach ($table->getHash() as $n => $repeatedElement) {
            $singleElements = $elements[$n];
    
            \PHPUnit_Framework_Assert::assertEquals(
                $singleElements->getText(),
                $repeatedElement['text']
            );
        }
    }
    
    /**
     * @Then /^I should see that "([^"]*)" in "([^"]*)" is selected$/
     */
    public function inShouldBeSelected($optionValue, $select) {
        $selectElement = $this->getSession()->getPage()->find('named', array('select', "\"{$select}\""));
        $optionElement = $selectElement->find('named', array('option', "\"{$optionValue}\""));
        //it should have the attribute selected and it should be set to selected
        assertTrue($optionElement->hasAttribute("selected"));
        assertTrue($optionElement->getAttribute("selected") == "selected");
    }
}
