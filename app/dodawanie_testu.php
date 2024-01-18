<?php
session_start();
// Strategia dodawania testów
interface TestAdditionStrategy {
    public function addTest($user_id, $nazwa_testu);
}

// Konkretna strategia dla dodawania testów do bazy danych
class DatabaseTestAddition implements TestAdditionStrategy {
    public function addTest($user_id, $nazwa_testu) {
        // Nawiązanie połączenia z bazą danych
        $conn = new mysqli("localhost", "root", "", "testy");

        // Sprawdzenie czy połączenie zostało poprawnie nawiązane
        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        // Zapytanie SQL dodające test do bazy danych
        $sql = "INSERT INTO test (nazwa, id_osoby) VALUES ('$nazwa_testu', '$user_id')";

        // Wykonanie zapytania SQL
        if ($conn->query($sql) === TRUE) {
            echo "Dodano test.";
        } else {
            echo "Nie dodano testu :c " . $conn->error;
        }

        // Zamknięcie połączenia z bazą danych
        $conn->close();
    }
}

// Klasa zarządzająca strategią dodawania testów
class TestService {
    private $additionStrategy;

    public function __construct(TestAdditionStrategy $additionStrategy) {
        $this->additionStrategy = $additionStrategy;
    }

    public function addTest($user_id, $nazwa_testu) {
        // Delegowanie zadania dodania testu do konkretnej strategii
        $this->additionStrategy->addTest($user_id, $nazwa_testu);
    }
}

// Sprawdzenie roli użytkownika (czy jest prowadzącym)
if (isset($_SESSION['role']) && $_SESSION['role'] === 'prowadzacy') {
    // Sprawdzenie czy żądanie zostało wysłane metodą POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Pobranie danych z sesji i formularza
        $user_id = $_SESSION['user_id'];
        $nazwa_testu = $_POST["nazwa_testu"];

        // Utworzenie obiektu konkretnej strategii (dodawania do bazy danych)
        $strategy = new DatabaseTestAddition();

        // Utworzenie obiektu zarządzającego strategią
        $testService = new TestService($strategy);

        // Wywołanie metody dodawania testu z użyciem strategii
        $testService->addTest($user_id, $nazwa_testu);
    }
} else {
    // Przekierowanie na stronę logowania, jeśli użytkownik nie jest prowadzącym
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
