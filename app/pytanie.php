<?php
session_start();

if (isset($_SESSION['role']) && $_SESSION['role'] === 'prowadzacy') {
    $user_id = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $conn = new mysqli("localhost", "root", "", "testy");

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        $test_nazwa = $_POST["id_test"];
        $tresc_pytania = $_POST["pytanie"];
        $poprawna_odpowiedz = $_POST["popr_odp"];

        $sql = "INSERT INTO pytania (test_nazwa, pytanie, popr_odp)
                VALUES ('$test_nazwa', '$tresc_pytania', '$poprawna_odpowiedz')";

        if ($conn->query($sql) === TRUE) {
            echo "Nowe pytanie zostało dodane do testu.";
        } else {
            echo "Błąd podczas dodawania pytania: " . $conn->error;
        }

        $conn->close();
    }

    $conn = new mysqli("localhost", "root", "", "testy");

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $sql_tests = "SELECT nazwa FROM test WHERE id_osoby = $user_id";
    $result_tests = $conn->query($sql_tests);
}
?>


<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Pytanie</title>
</head>
<body>
    <div class="main">
    <div class="main">
    <h1>Dodaj nowe pytanie do testu</h1>
    <form method="post" action="pytanie.php">
        <label for="id_test">Nazwa testu:</label>
        <select name="id_test" id="id_test" required>
            <?php
            while ($row_tests = $result_tests->fetch_assoc()) {
                echo '<option value="' . $row_tests['nazwa'] . '">' . $row_tests['nazwa'] . '</option>';
            }
            ?>
        </select>
        <br>
        <label for="pytanie">Treść pytania:</label>
        <textarea name="pytanie" id="pytanie" required></textarea>
        <br>
        <label for="popr_odp">Poprawna odpowiedź:</label>
        <input type="text" name="popr_odp" id="popr_odp" required>
        <br>
        <input type="submit" value="Dodaj pytanie">
    </form>
    <a href="panel_prowadzacego.php">Powrót do panelu prowadzącego</a>
</div>

    </div>
</body>
</html>
