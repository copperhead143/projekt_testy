<?php
session_start();
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] === 'prowadzacy') {  //jezeli juz jest zalogowany jako prowadzacy to idz do panelu prowadzacego 
        header("Location: panel_prowadzacego.php");
    } elseif ($_SESSION['role'] === 'uczen') { //analogicznie dla ucznia
        header("Location: uczen.php");
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $login = $_POST["login"];
    $password = $_POST["password"];  //pobranie loginu i hasla z formularza

    $conn = new mysqli("localhost", "root", "", "testy");  //polaczenie z baza danych 

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }


    $sql = "SELECT id, password, role FROM osoby WHERE login = '$login' LIMIT 1"; //sprawdzenie hasla w bazie 
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        $stored_password = $row["password"];
        $role = $row["role"]; //pobranie hasla i roli uzytkownika do zmiennych

        if ($password == $stored_password) {
            $_SESSION['login'] = $login;
            $_SESSION['role'] = $role; //jezeli haslo sie zgadza to ustaw w sesji login i role

            $sql1 = "SELECT ID FROM osoby where login = '$login' LIMIT 1";
            $wynik = $conn->query($sql);

            if ($wynik->num_rows > 0) {
                $row = $wynik->fetch_assoc();
                $user_id = $row["id"];
                $_SESSION["user_id"] = $user_id; //pobranie i ustawienie id uzytkownika do sesji
            }

            if ($role === 'prowadzacy') {
                header("Location: panel_prowadzacego.php");
            } elseif ($role === 'uczen') {
                header("Location: uczen.php");
            }
        } else {
            $error_message = "Błędne hasło.";
        }
    } else {
        $error_message = "Użytkownik o podanym loginie nie istnieje.";
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
</head>
<body>
    <div class="main">
        <h1>Logowanie</h1>
        <?php if (isset($error_message)) : ?>
            <p style="color: red;"><?php echo $error_message; ?></p>
        <?php endif; ?>
        <form method="post" action="index.php">
            <label for="login">Login:</label>
            <input type="text" name="login" id="login" required>
            <br>
            <label for="password">Hasło:</label>
            <input type="password" name="password" id="password" required>
            <br>
            <input type="submit" value="Zaloguj">
        </form>
    </div>
</body>
</html>
