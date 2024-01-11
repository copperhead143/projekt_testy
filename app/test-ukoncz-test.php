<?php
use PHPUnit\Framework\TestCase;

require_once 'TestCompletionService.php';

class TestCompletionServiceTest extends TestCase
{
    public function testSuccessfulTestCompletion()
    {
        $_SESSION['role'] = 'uczen';
        $_SESSION['user_id'] = 1;
        $_GET['test_id'] = 'test1';
        $_SERVER["REQUEST_METHOD"] = "POST";
        
        $mockConnection = $this->createMock(mysqli::class);
        $mockConnection->method('query')->willReturn(new stdClass());

        $mockService = $this->getMockBuilder(TestCompletionService::class)
                           ->setConstructorArgs([$mockConnection])
                           ->getMock();

        $result = $mockService->completeTest();

        $this->assertStringContainsString("Odpowiedzi zostały zapisane.", $result);
    }

    public function testInvalidRequest()
    {
        $_SESSION['role'] = 'uczen';
        $_GET['test_id'] = 'test1';
        $_SERVER["REQUEST_METHOD"] = "GET";
        
        $mockConnection = $this->createMock(mysqli::class);

        $mockService = $this->getMockBuilder(TestCompletionService::class)
                           ->setConstructorArgs([$mockConnection])
                           ->getMock();

        $result = $mockService->completeTest();

        $this->assertStringContainsString("Niepoprawne żądanie.", $result);
    }

    public function testRedirectsToLoginWhenNotLoggedIn()
    {
        $_SESSION['role'] = null;
        $_GET['test_id'] = 'test1';
        
        $mockConnection = $this->createMock(mysqli::class);

        $mockService = $this->getMockBuilder(TestCompletionService::class)
                           ->setConstructorArgs([$mockConnection])
                           ->getMock();

        $result = $mockService->completeTest();

        $this->assertStringContainsString("Location: login.php", xdebug_get_headers());
    }

}
?>
