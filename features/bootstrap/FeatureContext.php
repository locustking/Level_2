<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $widgetpane;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->widgetpane = new WidgetPane();
    }

    /**
     * @Given there is a link to about page
     */
    public function thereIsALinkToAboutPage()
    {
        throw new PendingException();
    }

    /**
     * @When I open the about page
     */
    public function iOpenTheAboutPage()
    {
        throw new PendingException();
    }

    /**
     * @Then I should have the about page with a full description about the tools that can help my book online
     */
    public function iShouldHaveTheAboutPageWithAFullDescriptionAboutTheToolsThatCanHelpMyBookOnline()
    {
        throw new PendingException();
    }

    /**
     * @Given there is a :arg1, which is listed on Amazon
     */
    public function thereIsAWhichIsListedOnAmazon($arg1)
    {
        throw new PendingException();
    }

    /**
     * @When I add :arg1
     */
    public function iAdd($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should have :arg1 book in the widget pane
     */
    public function iShouldHaveBookInTheWidgetPane($arg1)
    {
        PHPUnit_Framework_Assert::assertCount(
            intval($arg1),
            $this->widgetpane
        );
       
    }

    /**
     * @Then the book title should be :arg1
     */
    public function theBookTitleShouldBe($arg1)
    {
        throw new PendingException();
    }
}

