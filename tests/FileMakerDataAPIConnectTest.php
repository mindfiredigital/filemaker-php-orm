<?php

use PHPUnit\Framework\TestCase;
use FilemakerPhpOrm\Filemaker\FileMakerDataAPIConnect;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;

class FileMakerDataAPIConnectTest extends TestCase
{
    protected $fileMakerConnect;

    use MockeryPHPUnitIntegration;

    /**
     * @var FileMakerDataAPIConnect|MockInterface
     */
    private $fileMakerMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fileMakerConnect = new FileMakerDataAPIConnect();

        // Create a Mockery mock for the FileMakerDataAPIConnect class
        $this->fileMakerMock = \Mockery::mock(FileMakerDataAPIConnect::class);
        $this->fileMakerMock->shouldAllowMockingProtectedMethods();

        // Set the baseURL, username, and password properties
        // $this->fileMakerMock->baseURL = 'https://example.com';
        // $this->fileMakerMock->username = 'test_user';
        // $this->fileMakerMock->password = 'test_password';
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Close Mockery
        \Mockery::close();
    }

    public function testConstructor()
    {
        $this->assertInstanceOf(FileMakerDataAPIConnect::class, $this->fileMakerConnect);
    }

    public function testLoginFMDatabase()
    {
        // Set expectations for the getBaseURL method
        $this->fileMakerMock->shouldReceive('getBaseURL')
            ->once()
            ->andReturn('https://example.com');

        // Set the expected parameters for the curlRequest method
        $this->fileMakerMock->shouldReceive('curlRequest')
            ->once()
            ->with(
                'https://example.com/sessions',
                'POST',
                \Mockery::any(),
                \Mockery::any() // This is where you specify that any array should be passed
            )
            ->andReturn('mocked_response');

        // Call the method being tested
        $result = $this->fileMakerMock->loginFMDatabase();

        // Assert the result based on the expected mocked response
        $this->assertEquals('mocked_response', $result);
    }

    // public function testPostRequest()
    // {
    //     $endpoint = 'test-endpoint';
    //     $token = 'test-token';
    //     $data = ['key' => 'value'];

    //     $this->fileMakerMock->shouldReceive('url')
    //         ->andReturn('https://example.com');

    //     $this->fileMakerMock->shouldReceive('curlRequest')
    //         ->once()
    //         ->with(
    //             'https://example.com/test-endpoint',
    //             'POST',
    //             \Mockery::on(function ($headers) use ($token) {
    //                 return in_array('Authorization: Bearer ' . $token, $headers);
    //             }),
    //             \Mockery::on(function ($options) use ($data) {
    //                 return $options[CURLOPT_POSTFIELDS] === json_encode($data);
    //             })
    //         )
    //         ->andReturn('mocked_response');

    //     // Call the method being tested
    //     $result = $this->fileMakerMock->postRequest($endpoint, $token, $data);

    //     // Assert the result based on the expected mocked response
    //     $this->assertEquals('mocked_response', $result);
    // }

    // public function testPatchRequest()
    // {
    //     $endpoint = 'test-endpoint';
    //     $token = 'test-token';
    //     $data = ['key' => 'value'];

    //     $this->fileMakerMock->shouldReceive('url')
    //         ->andReturn('https://example.com');

    //     $this->fileMakerMock->shouldReceive('curlRequest')
    //         ->once()
    //         ->with(
    //             'https://example.com/test-endpoint',
    //             'PATCH',
    //             \Mockery::on(function ($headers) use ($token) {
    //                 return in_array('Authorization: Bearer ' . $token, $headers);
    //             }),
    //             \Mockery::on(function ($options) use ($data) {
    //                 return $options[CURLOPT_POSTFIELDS] === json_encode($data);
    //             })
    //         )
    //         ->andReturn('mocked_response');

    //     // Call the method being tested
    //     $result = $this->fileMakerMock->patchRequest($endpoint, $token, $data);

    //     // Assert the result based on the expected mocked response
    //     $this->assertEquals('mocked_response', $result);
    // }

    // public function testGetRequest()
    // {
    //     $endpoint = 'test-endpoint';
    //     $token = 'test-token';

    //     $this->fileMakerMock->shouldReceive('url')
    //         ->andReturn('https://example.com');

    //     $this->fileMakerMock->shouldReceive('curlRequest')
    //         ->once()
    //         ->with(
    //             'https://example.com/test-endpoint',
    //             'GET',
    //             \Mockery::on(function ($headers) use ($token) {
    //                 return in_array('Authorization: Bearer ' . $token, $headers);
    //             }),
    //             \Mockery::type('array') // Ensure an array is passed as the fourth argument
    //         )
    //         ->andReturn('mocked_response');

    //     // Call the method being tested
    //     $result = $this->fileMakerMock->getRequest($endpoint, $token);

    //     // Assert the result based on the expected mocked response
    //     $this->assertEquals('mocked_response', $result);
    // }

    // public function testDelete()
    // {
    //     $endpoint = 'test-endpoint';
    //     $token = 'test-token';

    //     $this->fileMakerMock->shouldReceive('url')
    //         ->andReturn('https://example.com');

    //     $this->fileMakerMock->shouldReceive('curlRequest')
    //         ->once()
    //         ->with(
    //             'https://example.com/test-endpoint',
    //             'DELETE',
    //             \Mockery::on(function ($headers) use ($token) {
    //                 return in_array('Authorization: Bearer ' . $token, $headers);
    //             }),
    //             \Mockery::type('array') // Ensure an array is passed as the fourth argument
    //         )
    //         ->andReturn('mocked_response');

    //     // Call the method being tested
    //     $result = $this->fileMakerMock->deleteRequest($endpoint, $token);

    //     // Assert the result based on the expected mocked response
    //     $this->assertEquals('mocked_response', $result);
    // }

    // public function testPerformScript()
    // {
    //     $endpoint = 'test-endpoint';
    //     $token = 'test-token';

    //     $this->fileMakerMock->shouldReceive('url')
    //         ->andReturn('https://example.com');

    //     $this->fileMakerMock->shouldReceive('curlRequest')
    //         ->once()
    //         ->with(
    //             'https://example.com/test-endpoint',
    //             'GET',
    //             \Mockery::on(function ($headers) use ($token) {
    //                 return in_array('Authorization: Bearer ' . $token, $headers);
    //             }),
    //             \Mockery::type('array') // Ensure an array is passed as the fourth argument
    //         )
    //         ->andReturn('mocked_response');

    //     // Call the method being tested
    //     $result = $this->fileMakerMock->performScriptRequest($endpoint, $token);

    //     // Assert the result based on the expected mocked response
    //     $this->assertEquals('mocked_response', $result);
    // }

    // public function testUpload()
    // {
    //     $endpoint = 'test-endpoint';
    //     $token = 'test-token';
    //     $file = 'path/to/test/file.txt';

    //     $this->fileMakerMock->shouldReceive('url')
    //         ->andReturn('https://example.com');

    //     $this->fileMakerMock->shouldReceive('curlRequest')
    //         ->once()
    //         ->with(
    //             'https://example.com/test-endpoint',
    //             'POST',
    //             \Mockery::on(function ($headers) use ($token) {
    //                 return in_array('Authorization: Bearer ' . $token, $headers);
    //             }),
    //             \Mockery::type('array') // Ensure an array is passed as the fourth argument
    //         )
    //         ->andReturn('mocked_response');

    //     // Call the method being tested
    //     $result = $this->fileMakerMock->uploadRequest($endpoint, $token, $file);

    //     // Assert the result based on the expected mocked response
    //     $this->assertEquals('mocked_response', $result);
    // }
}
