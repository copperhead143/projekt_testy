<?php
use PHPUnit\Framework\TestCase;

require_once 'QuestionService.php';

class QuestionServiceTest extends TestCase
{
    public function testSuccessfulQuestionAddition()
    {
        $_SESSION['role'] = 'prowadzacy';
        $_SESSION['user_id'] = 1;
        $_SERVER["REQUEST_METHOD"] = "POST";
        
        $mockConnection = $this->createMock(mysqli::class);
        $mockConnection->method('query')->willReturn(new stdClass()); 

        $mockService = $this->getMockBuilder(QuestionService::class)
                           ->setConstructorArgs([$mockConnection])
                           ->getMock();

        $result = $mockService->addQuestion();

        $this->assertStringContainsString("Nowe pytanie zostało dodane do testu.", $result);
    }

    public function testFailedQuestionAddition()
    {
        $_SESSION['role'] = 'prowadzacy';
        $_SESSION['user_id'] = 1;
        $_SERVER["REQUEST_METHOD"] = "POST";

        $mockConnection = $this->createMock(mysqli::class);
        $mockConnection->method('query')->willReturn(false); 

        $mockService = $this->getMockBuilder(QuestionService::class)
                           ->setConstructorArgs([$mockConnection])
                           ->getMock();

        $result = $mockService->addQuestion();

        $this->assertStringContainsString("Błąd podczas dodawania pytania:", $result);
    }

    public function testPageLoad()
    {
        $_SESSION['role'] = 'prowadzacy';
        $_SESSION['user_id'] = 1;
        $_SERVER["REQUEST_METHOD"] = "GET";

        $mockConnection = $this->createMock(mysqli::class);
        $mockConnection->method('query')->willReturn(new stdClass());

        $mockService = $this->getMockBuilder(QuestionService::class)
                           ->setConstructorArgs([$mockConnection])
                           ->getMock();

        $result = $mockService->loadPage();

        $this->assertStringContainsString("Dodaj nowe pytanie do testu", $result);
    }

}
?>
