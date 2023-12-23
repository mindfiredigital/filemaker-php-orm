<?php
use PHPUnit\Framework\TestCase;
use FilemakerPhpOrm\Filemaker\FileMakerDataAPI;

class FileMakerDataAPITest extends TestCase
{
    protected $fileMakerDataAPI;
    protected function setUp(): void
    {
        parent::setUp();
        // You can initialize any necessary objects or configurations here
        $this->fileMakerDataAPI = new FileMakerDataAPI();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(FileMakerDataAPI::class, $this->fileMakerDataAPI);
    }

    protected function tearDown(): void
    {
        // You can perform any necessary cleanup here
        parent::tearDown();
    }
}
