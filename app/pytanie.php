<?php
class DatabaseConnection {
    // Prywatna statyczna zmienna przechowująca jedyną instancję klasy
    private static $instance;
    
    // Prywatne pole przechowujące połączenie z bazą danych
    private $conn;

    // Prywatny konstruktor, który jest wywoływany tylko raz, tworząc jedną instancję klasy
    private function __construct() {
        // Inicjalizacja połączenia z bazą danych (MySQL)
        $this->conn = new mysqli("localhost", "root", "", "testy");

        // Sprawdzenie czy połączenie się udało
        if ($this->conn->connect_error) {
            die("nie udało się połączyć z bazą danych");
        }
    }

    // Metoda statyczna zwracająca jedyną instancję klasy
    public static function getInstance() {
        // Jeśli instancja nie istnieje, utwórz nową
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    // Metoda zwracająca połączenie z bazą danych
    public function getConnection() {
        return $this->conn;
    }
}

class TestQuestion {
    // Metoda dodająca pytanie do bazy danych
    public function addQuestion($test_nazwa, $tresc_pytania, $poprawna_odpowiedz) {
        // Pobranie połączenia z bazą danych za pomocą Singletona
        $conn = DatabaseConnection::getInstance()->getConnection();

        // Zapytanie SQL do dodania pytania do bazy danych
        $sql = "INSERT INTO pytania (test_nazwa, pytanie, popr_odp) VALUES ('$test_nazwa', '$tresc_pytania', '$poprawna_odpowiedz')";

        // Wykonanie zapytania i obsługa błędów
        if ($conn->query($sql) === TRUE) {
            echo "pytanie dodane humor gituwa";
        } else {
            echo "no cusz przykra sprawa";
        }
    }
}

// Inicjalizacja sesji
session_start();

// Sprawdzenie, czy użytkownik jest zalogowany jako prowadzący
if (isset($_SESSION['role']) && $_SESSION['role'] === 'prowadzacy') {
    $user_id = $_SESSION['user_id'];

    // Sprawdzenie, czy żądanie jest metodą POST
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Utworzenie obiektu klasy TestQuestion i dodanie pytania
        $testQuestion = new TestQuestion();
        $testQuestion->addQuestion($_POST["id_test"], $_POST["pytanie"], $_POST["popr_odp"]);
    }

    // Pobranie instancji połączenia z bazą danych za pomocą Singletona
    $conn = DatabaseConnection::getInstance()->getConnection();

    // Zapytanie SQL do pobrania nazw testów dla danego użytkownika
    $sql_tests = "SELECT nazwa FROM test WHERE id_osoby = $user_id";

    // Wykonanie zapytania
    $result_tests = $conn->query($sql_tests);
}
?>

<!-- Reszta kodu HTML dla formularza dodawania pytania -->
