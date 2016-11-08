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
    private $bookbeat;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->bookbeat = new BookBeat();
    }


    /**
     * @Given I am online
     */
    public function iAmOnline()
    {
        throw new PendingException();
    }

    /**
     * @Given I search for tools that can help my book sales
     */
    public function iSearchForToolsThatCanHelpMyBookSales()
    {
        throw new PendingException();
    }

    /**
     * @When I google search for bookbeat
     */
    public function iGoogleSearchForBookbeat()
    {
        throw new PendingException();
    }

    /**
     * @When I view the page
     */
    public function iViewThePage()
    {
        throw new PendingException();
    }

    /**
     * @Then I should have the bookbeat website describing its app
     */
    public function iShouldHaveTheBookbeatWebsiteDescribingItsApp()
    {
        throw new PendingException();
    }

    /**
     * @Given there is a :arg1, which is listed on Amazon
     */
    public function thereIsAWhichIsListedOnAmazon($book_title)
    {
        $this->bookbeat->setBookTitle($book_title);
    }

    /**
     * @When I view a :arg1
     */
    public function iViewA($book_title)
    {
        $this->bookbeat->viewBookTitle($book_title);
    }

    /**
     * @Then I should see :arg1
     */
    public function iShouldSee($book_title)
    {
        PHPUnit_Framework_Assert::assertSame(
            $book_title,
            $this->bookbeat->getBookTitle()
        );
    }

    /**
     * @Then I should see the Amazon sales rank of :arg1
     */
    public function iShouldSeeTheAmazonSalesRankOf($sales_rank)
    {
        PHPUnit_Framework_Assert::assertEquals(
            $sales_rank,
            $this->bookbeat->getSalesRank()
        );
    }

    /**
     * @Then I should see the number of reviews of :arg1
     */
    public function iShouldSeeTheNumberOfReviewsOf($num_reviews)
    {
        PHPUnit_Framework_Assert::assertEquals(
            $num_reviews,
            $this->bookbeat->getNumReviews()
        );
    }

    /**
     * @Then I should see the comments of :arg1
     */
    public function iShouldSeeTheCommentsOf($comments)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see the average rating of :arg1
     */
    public function iShouldSeeTheAverageRatingOf($avg_rating)
    {
        PHPUnit_Framework_Assert::assertEquals(
            $avg_rating,
            $this->bookbeat->getAvgRating()
        );
    }

    /**
     * @Then I should see the Amazon sales rank
     */
    public function iShouldSeeTheAmazonSalesRank()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see the number of reviews
     */
    public function iShouldSeeTheNumberOfReviews()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see the average rating
     */
    public function iShouldSeeTheAverageRating()
    {
        throw new PendingException();
    }
}
?>