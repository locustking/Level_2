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
	private $bookbeatjson;
	private $bookbeatlist;

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
        $this->bookbeatjson = new BookBeatJSON();
        $this->bookbeatlist = new BookBeatList();
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
    public function thereIsAWhichIsListedOnAmazon($arg1)
    {
        $this->bookbeat->setBookTitle($arg1);
    }

    /**
     * @When I view the BookBeat page
     */
    public function iViewTheBookbeatPage()
    {
        $this->bookbeat->viewBookbeatPage();
    }

    /**
     * @Then I should see :arg1
     */
    public function iShouldSee($arg1)
    {
        PHPUnit_Framework_Assert::assertSame(
            $arg1,
            $this->bookbeat->getBookTitle()
        );
    }

    /**
     * @Then I should see the Amazon sales rank of greater than :arg1
     */
    public function iShouldSeeTheAmazonSalesRankOfGreaterThan($arg1)
    {
        PHPUnit_Framework_Assert::assertGreaterThan(
            $arg1,
            $this->bookbeat->getSalesRank()
        );
    }

    /**
     * @Then I should see the number of reviews of :arg1 or greater
     */
    public function iShouldSeeTheNumberOfReviewsOfOrGreater($arg1)
    {
        PHPUnit_Framework_Assert::assertGreaterThanOrEqual(
            $arg1,
            $this->bookbeat->getNumReviews()
        );
    }

    /**
     * @Then I should see the average rating of greater or equal than :arg1 and less or equal :arg2
     */
    public function iShouldSeeTheAverageRatingOfGreaterOrEqualThanAndLessOrEqual($arg1, $arg2)
    {
        PHPUnit_Framework_Assert::assertGreaterThanOrEqual(
            $arg1,
            $this->bookbeat->getAvgRating()
        );
        PHPUnit_Framework_Assert::assertLessThanOrEqual(
            $arg2,
            $this->bookbeat->getAvgRating()
        );
    }

    /**
     * @Given there is a :arg1 which is listed on Amazon
     */
    public function thereIsAWhichIsListedOnAmazon2($arg1)
    {
        $this->bookbeat->setBookTitle($arg1);
    }

    /**
     * @Given there is a file :arg1 with ISBN numbers
     */
    public function thereIsAFileWithIsbnNumbers($arg1)
    {
		$this->bookbeatjson->setFilename($arg1);
		
		//bb4
		$this->bookbeatlist->setBookBeatJSON($this->bookbeatjson);
    }

    /**
     * @When I verify the file :arg1
     */
    public function iVerifyTheFile($arg1)
    {
        $this->bookbeatjson->verifyJSON($arg1);
    }

    /**
     * @Then I should see ISBN numbers of greater or equal than :arg1 and less or equal :arg2
     */
    public function iShouldSeeIsbnNumbersOfGreaterOrEqualThanAndLessOrEqual($arg1, $arg2)
    {
        PHPUnit_Framework_Assert::assertGreaterThanOrEqual(
            $arg1,
            $this->bookbeatjson->countBooks()
        );
        PHPUnit_Framework_Assert::assertLessThanOrEqual(
            $arg2,
            $this->bookbeatjson->countBooks()
        );
    }

    /**
     * @Then I should see each ISBN digits of greater or equal than :arg1 and less or equal :arg2
     */
    public function iShouldSeeEachIsbnDigitsOfGreaterOrEqualThanAndLessOrEqual($arg1, $arg2)
    {
        PHPUnit_Framework_Assert::assertGreaterThanOrEqual(
            $arg1,
            $this->bookbeatjson->countLeastIsbnDigits()
        );
        PHPUnit_Framework_Assert::assertLessThanOrEqual(
            $arg2,
            $this->bookbeatjson->countMostIsbnDigits()
        );
    }

    /**
     * @Then I should see each ISBN has ASIN
     */
    public function iShouldSeeEachIsbnHasAsin()
    {
        PHPUnit_Framework_Assert::assertEquals(
            $this->bookbeatjson->countBooks(),
            $this->bookbeatjson->countAsin()
        );
    }

    /**
     * @Then I should see at least :arg1 author's book in the list
     */
    public function iShouldSeeAtLeastAuthorSBookInTheList($arg1)
    {
        PHPUnit_Framework_Assert::assertGreaterThanOrEqual(
            $arg1,
            $this->bookbeatjson->countAuthorSBook()
        );
    }

    /**
     * @When I pull the sales rank
     */
    public function iPullTheSalesRank()
    {
		$this->bookbeatlist->updateSalesRank();
    }

    /**
     * @Then I should see the sales rank of each book
     */
    public function iShouldSeeTheSalesRankOfEachBook()
    {
        PHPUnit_Framework_Assert::assertEquals(
            $this->bookbeatlist->countBooks(),
            $this->bookbeatlist->countSalesRanks()
        );
	}

    /**
     * @Then the sales rank should be integer
     */
    public function theSalesRankShouldBeInteger()
    {
        PHPUnit_Framework_Assert::assertContainsOnly(
            "integer",
            $this->bookbeatlist->getAllRanks()
        );
    }

    /**
     * @Then I should see number of reviews
     */
    public function iShouldSeeNumberOfReviews()
    {
        PHPUnit_Framework_Assert::assertEquals(
            $this->bookbeatlist->countBooks(),
            $this->bookbeatlist->countNumberofReviews()
        );
    }

    /**
     * @Then the number of reviews should be integer
     */
    public function theNumberOfReviewsShouldBeInteger()
    {
        PHPUnit_Framework_Assert::assertContainsOnly(
            "integer",
            $this->bookbeatlist->getAllReviews()
        );
    }

    /**
     * @Then I should see average ratings
     */
    public function iShouldSeeAverageRatings()
    {
        PHPUnit_Framework_Assert::assertEquals(
            $this->bookbeatlist->countBooks(),
            $this->bookbeatlist->countAvgRatings()
        );
    }

    /**
     * @Then the average rating should be float
     */
    public function theAverageRatingShouldBeFloat()
    {
        PHPUnit_Framework_Assert::assertContainsOnly(
            "float",
            $this->bookbeatlist->getAllRatings()
        );
    }

    /**
     * @Then the list of book should be ranked by sales rank
     */
    public function theListOfBookShouldBeRankedBySalesRank()
    {
        throw new PendingException();
    }

    /**
     * @When I sort the order by :arg1
     */
    public function iSortTheOrderBy($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should see the books are sorted by :arg1
     */
    public function iShouldSeeTheBooksAreSortedBy($arg1)
    {
        throw new PendingException();
    }

}
