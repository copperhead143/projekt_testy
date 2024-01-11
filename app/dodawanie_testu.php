<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'prowadzacy') {  //jezeli zalogowano jako prowadzacy
    if ($_SERVER["REQUEST_METHOD"] === "POST") { //metoda post jak cos to mowicie ze ten rudy zjeb tak kazal zrobicc
        $conn = new mysqli("localhost", "root", "", "testy");  //polaczenie z baza na serwerze localhost, uzytkownik root, bez hasla bvaza testy

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }
        $user_id = $_SESSION['user_id']; //pobranie z sesji id uzytkownika
        $nazwa_testu = $_POST["nazwa_testu"]; //pobranie z sesji nazwy testu

        $sql = "INSERT INTO test (nazwa, id_osoby) VALUES ('$nazwa_testu', '$user_id')"; //kwerenda do wstawienia testu

        if ($conn->query($sql) === TRUE) {
            echo "Nowy test został dodany.";  //jak dziala to fajnie jak nie to wywala blad
        } else {
            echo "Błąd podczas dodawania testu: " . $conn->error;
        }

        $conn->close();
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
        <a href="pytanie.php">Dodaj pytania do testow</a>
    </div>
</body>
</html>
