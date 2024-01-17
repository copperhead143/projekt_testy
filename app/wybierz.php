<?php
session_start();
//dependency injection
class TestFetcher {
    private $conn;

    public function __construct(mysqli $conn) {
        $this->conn = $conn;
    }

    public function getTests() {
        $tests = [];

        $sql = "SELECT * FROM test";
        $result = $this->conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tests[] = $row;
            }
        }

        return $tests;
    }
}

$conn = new mysqli("localhost", "root", "", "testy");
$testFetcher = new TestFetcher($conn);

if (isset($_SESSION['role']) && $_SESSION['role'] === 'uczen') {
    $tests = $testFetcher->getTests();
} else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wybierz Test</title>
</head>
<body>
    <div class="main">
        <h1>Wybierz Test</h1>
        <ul>
            <?php foreach ($tests as $test): ?>
                <li>
                    <a href="test.php?test_id=<?php echo $test['nazwa']; ?>"><?php echo $test['nazwa']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="wyloguj.php">Wyloguj się</a>
    </div>
</body>
</html>
