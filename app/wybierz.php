<?php
session_start();
if (isset($_SESSION['role']) && $_SESSION['role'] === 'uczen') {
    $conn = new mysqli("localhost", "root", "", "testy");

    if ($conn->connect_error) {
        die("Błąd połączenia z bazą danych: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM test";
    $result = $conn->query($sql);  //pobranie testow z bazy danych zeby umozliwic ich wyswietlenie i wybor

    $tests = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tests[] = $row; //zapisanie testow w zmiennej
        }
    }

    $conn->close();
} else {
    header("Location: login.php");
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wybierz Test</title>
</head>
<body>
    <div class="main">
        <h1>Wybierz Test</h1>
        <ul>
            <?php foreach ($tests as $test): ?>
                <li>
                    <a href="test.php?test_id=<?php echo $test['nazwa']; ?>"><?php echo $test['nazwa']; ?></a>
                </li>
            <?php endforeach; ?>
        </ul>
        <a href="wyloguj.php">Wyloguj się</a>
    </div>
</body>
</html>
