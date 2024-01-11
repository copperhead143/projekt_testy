<?php
use PHPUnit\Framework\TestCase;

require_once 'LoginService.php';

class LoginServiceTest extends TestCase
{
    public function testSuccessfulLoginRedirectsToCorrectPage()
    {
        $_POST["login"] = "testuser";
        $_POST["password"] = "testpassword";

        $mockConnection = $this->createMock(mysqli::class);
        $mockConnection->method('query')->willReturn(new stdClass()); 

        $mockService = $this->getMockBuilder(LoginService::class)
                           ->setConstructorArgs([$mockConnection])
                           ->getMock();

        $result = $mockService->login();

        $this->assertTrue($result);
        $this->assertContains("Location: uczen.php", xdebug_get_headers());
    }

    public function testFailedLoginShowsErrorMessage()
    {
        $_POST["login"] = "nonexistentuser";
        $_POST["password"] = "wrongpassword";

        $mockConnection = $this->createMock(mysqli::class);
        $mockConnection->method('query')->willReturn(new stdClass());

        $mockService = $this->getMockBuilder(LoginService::class)
                           ->setConstructorArgs([$mockConnection])
                           ->getMock();

        $result = $mockService->login();

        $this->assertFalse($result);
        $this->assertStringContainsString("Użytkownik o podanym loginie nie istnieje.", $GLOBALS['error_message']);
    }

}
?>
