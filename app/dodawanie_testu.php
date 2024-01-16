<?php
session_start();
//wzorzec strategia
interface TestAdditionStrategy {
    public function addTest($user_id, $nazwa_testu);
}

class DatabaseTestAddition implements TestAdditionStrategy {
    public function addTest($user_id, $nazwa_testu) {
        $conn = new mysqli("localhost", "root", "", "testy");

        if ($conn->connect_error) {
            die("bazunia nie działa D: " . $conn->connect_error);
        }

        $sql = "INSERT INTO test (nazwa, id_osoby) VALUES ('$nazwa_testu', '$user_id')";

        if ($conn->query($sql) === TRUE) {
            echo "dodano tescia.";
        } else {
            echo "nie dodano tescia :c " . $conn->error;
        }

        $conn->close();
    }
}

class TestService {
    private $additionStrategy;

    public function __construct(TestAdditionStrategy $additionStrategy) {
        $this->additionStrategy = $additionStrategy;
    }

    public function addTest($user_id, $nazwa_testu) {
        $this->additionStrategy->addTest($user_id, $nazwa_testu);
    }
}

if (isset($_SESSION['role']) && $_SESSION['role'] === 'prowadzacy') {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $user_id = $_SESSION['user_id'];
        $nazwa_testu = $_POST["nazwa_testu"];

        $strategy = new DatabaseTestAddition();
        $testService = new TestService($strategy);
        $testService->addTest($user_id, $nazwa_testu);
    }
} else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Test</title>
</head>
<body>
    <div class="main">
        <h1>Dodaj nowy test</h1>
        <form method="post" action="dodawanie_testu.php">
            <label for="nazwa_testu">Nazwa testu:</label>
            <input type="text" name="nazwa_testu" id="nazwa_testu" required>
            <br>
            <input type="submit" value="Dodaj test">
        </form>
        <a href="panel_prowadzacego.php">Powrót do panelu prowadzącego</a>
        <a href="pytanie.php">Dodaj pytania do testów</a>
    </div>
</body>
</html>
