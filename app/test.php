<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'uczen') {
    if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['test_id'])) {
        $test_id = $_GET['test_id'];
        
        $conn = new mysqli("localhost", "root", "", "testy");

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM pytania WHERE test_nazwa = $test_id";
        $result = $conn->query($sql);

        $questions = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) { //pobranie pytan z bazy do tabeli
                $questions[] = $row;
            }
        }

        $conn->close();
    } else {
        echo "Niepoprawne żądanie.";
    }
} else {
    header("Location: login.php");
}
?>


