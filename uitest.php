<?php
class UITest extends PHPUnit_Extensions_Selenium2TestCase
{
    protected function setUp()
    {
        $this->setBrowser('firefox');
        $this->setBrowserUrl('http://www.bookbeatapp.com/');
    }

    public function testTitle()
    {
        $this->url('http://www.bookbeatapp.com/');
        $this->assertContains('Book Beat', $this->title());
    }

}
?>