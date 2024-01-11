<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'prowadzacy') {
    if ($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['question_id'])) { //gdy ustawione jest ktore pytanie 
        $question_id = $_GET['question_id']; //pobranie id pytania 
        
        $conn = new mysqli("localhost", "root", "", "testy");  //polaczenie z baza

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        $sql = "SELECT * FROM pytania WHERE id = $question_id";
        $result = $conn->query($sql); 

        if ($result->num_rows > 0) {
            $question = $result->fetch_assoc(); //pobranie wyniku
        } else {
            echo "Pytanie o podanym ID nie istnieje.";
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $new_question = $_POST['new_question'];
            $new_correct_answer = $_POST['new_correct_answer']; //pobranie z formularza nowej trsci pytania i nowej poprawnej odpowiedzi
            
            $update_sql = "UPDATE pytania 
                           SET tresc_pytania = '$new_question', 
                               poprawna_odpowiedz = '$new_correct_answer' 
                           WHERE id = $question_id"; //aktualizacja rekordu w bazie danych
            
            if ($conn->query($update_sql) === TRUE) {
                echo "Pytanie zostało zaktualizowane."; //jak dziala to fajrant a jak nie to kaszana 
            } else {
                echo "Błąd podczas aktualizacji pytania: " . $conn->error;
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

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edytuj Pytanie</title>
</head>
<body>
    <div class="main">
        <h1>Edytuj Pytanie</h1>
        <form method="post" action="edit_question.php?question_id=<?php echo $question_id; ?>">
            <label for="new_question">Treść pytania:</label>
            <textarea name="new_question" id="new_question" required><?php echo $question['tresc_pytania']; ?></textarea>
            <br>
            <label for="new_correct_answer">Poprawna odpowiedź:</label>
            <input type="text" name="new_correct_answer" id="new_correct_answer" required value="<?php echo $question['poprawna_odpowiedz']; ?>">
            <br>
            <input type="submit" value="Zaktualizuj pytanie">
        </form>
        <a href="pytanie.php">Powrót do listy pytań</a>
    </div>
</body>
</html>
