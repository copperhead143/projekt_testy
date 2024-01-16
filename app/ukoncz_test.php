<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'uczen') {
    if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['test_id'])) {
        $test_id = $_GET['test_id'];
        
        $conn = new mysqli("localhost", "root", "", "testy");

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM pytania WHERE test_nazwa = '$test_id'";
        $result = $conn->query($sql);

        $questions = [];

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $questions[] = $row;
            }
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $user_id = $_SESSION['user_id'];

            foreach ($questions as $position => $question) {
                $answer_field_name = "answer_" . $position;
                $user_answer = $_POST[$answer_field_name];

                $sql_check = "SELECT poprawna_odpowiedz FROM pytania WHERE id = $question_id";
                $result_check = $conn->query($sql_check);

                if ($result_check->num_rows > 0) {
                    $row_check = $result_check->fetch_assoc();
                    $poprawna_odpowiedz = $row_check['poprawna_odpowiedz'];

                    $czy_poprawna = ($user_answer === $poprawna_odpowiedz) ? 1 : 0;

                    $sql_insert = "INSERT INTO odpowiedzi (nazwa_testu, id_osoby, pytanie, odpowiedz, czy_poprawna)
                                  VALUES ('$test_id', '$user_id', '$question_id', '$user_answer', '$czy_poprawna')";

                    if ($conn->query($sql_insert) !== TRUE) {
                        echo "Błąd podczas zapisywania odpowiedzi: " . $conn->error;
                    }
                }
            }

            echo "Odpowiedzi zostały zapisane.";
        }

        $conn->close();
    } else {
        echo "Niepoprawne żądanie.";
    }
} else {
    header("Location: login.php");
}
?>
