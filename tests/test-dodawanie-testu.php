<?php

use PHPUnit\Framework\TestCase;

class DodawanieTestuTest extends TestCase
{
    public function testProwadzacyMozeDodacNowyTest()
    {
        $_SESSION['role'] = 'prowadzacy';
        $_SESSION['user_id'] = 1;

        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST["nazwa_testu"] = "Test Jednostkowy";

        ob_start();
        include 'dodawanie_testu.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Nowy test został dodany.', $output);
    }

    public function testNieprowadzacyNieMozeDodacTestu()
    {
        $_SESSION['role'] = 'uczen';
        $_SESSION['user_id'] = 1;

        $_SERVER["REQUEST_METHOD"] = "POST";
        $_POST["nazwa_testu"] = "Test Jednostkowy";

        ob_start();
        include 'dodawanie_testu.php';
        $output = ob_get_clean();

        $this->assertStringContainsString('Location: login.php', implode(' ', xdebug_get_headers()));
    }
}
