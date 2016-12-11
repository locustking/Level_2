<?php

use PHPUnit\Framework\TestCase;

class DefaultTest extends PHPUnit_Extensions_Selenium2TestCase
{
    public function setUp()
    {
        $this->setHost('localhost');
        $this->setBrowserUrl('http://staging1.bookbeatapp.com');
        $this->setBrowser('firefox');
    }

    public function testTitle() {
	$this->url('http://staging1.bookbeatapp.com/book-beat');
	$this->assertEquals("Book Beat App | Book Beat", $this->title());
    }

    public function testTableDisplayed()
    {
        $this->url('http://staging1.bookbeatapp.com/book-beat');
        $this->assertTrue($this->byId('booklist')->displayed());
    } 
  
    public function tearDown()
    {
	$this->stop();
    }
}

?>
