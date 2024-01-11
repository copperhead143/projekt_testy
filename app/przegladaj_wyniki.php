<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'prowadzacy') {

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['test_id'])) {
        $test_id = $_POST['test_id'];

        $conn = new mysqli("localhost", "root", "", "testy");

        if ($conn->connect_error) {
            die("Błąd połączenia z bazą danych: " . $conn->connect_error);
        }

        $sql = "SELECT o.nazwa_testu, o.id_osoby, o.pytanie, o.odpowiedz, o.czy_poprawna, u.login
                FROM odpowiedzi o
                JOIN uczniowie u ON o.id_osoby = u.id
                WHERE o.nazwa_testu = '$test_id'";

        $result = $conn->query($sql);

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
    <title>Przeglądaj Wyniki</title>
</head>
<body>
    <div class="main">
        <h1>Przeglądaj Wyniki Testów</h1>
        <form method="post" action="przegladaj_wyniki.php">
            <label for="test_id">Wybierz test:</label>
            <select name="test_id" id="test_id">
                <?php
                    //tutaj sie maja wyswietlac testy ale nie mam psychy juz tego robic
                   /* while ($row = $result->fetch_assoc()) {
                        echo '<option value="' . $row['id_osoby'] . '">' . $row['nazwa_test'] . '</option>';
                    }*/ 
                    //no sory probowalem
                ?>
            </select>
            <input type="submit" value="Wyświetl Wyniki">
        </form>

        <?php if (isset($result)): ?>
            <table>
                <tr>
                    <th>Nazwa Testu</th>
                    <th>Uczeń</th>
                    <th>Pytanie</th>
                    <th>Odpowiedź</th>
                    <th>Czy Poprawna</th>
                </tr>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['nazwa_testu']; ?></td>
                        <td><?php echo $row['login']; ?></td>
                        <td><?php echo $row['pytanie']; ?></td>
                        <td><?php echo $row['odpowiedz']; ?></td>
                        <td><?php echo $row['czy_poprawna']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php endif; ?>
        <a href="panel_prowadzacego.php">Powrót do panelu prowadzącego</a>
    </div>
</body>
</html>
