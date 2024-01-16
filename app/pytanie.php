<?php
class DatabaseConnection{
    private static $instance;
    private $conn;

    private function __construct(){
        $this->conn = new mysqli("localhost", "root", "","testy");

        if($this->conn->connect_error){
            die("nie udalo sie polaczyc z baza danych")
        }
    }

    public static function getInstance(){
        if(self::$instance == null){
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(){
        return $this->conn;
    }
}


class TestQuestion{
    public function addQuestion($test_nazwa, $tresc_pytania, $poprawna_odpowiedz){
        $conn = DatabaseConnection::getInstance()->getConnection();
    }

    $sql = "INSERT INTO pytania (test_nazwa, pytanie, popr_odp)
     VALUES ('$test_nazwa', '$tresc_pytania', '$poprawna_odpowiedz')";

    if($conn->query($sql) === TRUE){
        echo "pytanie dodane humor gituwa";
    }else{
        echo "no cusz przykra sprawa"
    }

}

session_start();


if(isset($_SESSION['role']) && $_SESSION['role'] === 'prowadzacy'){
    $user_id = $_SESSION['user_id'];

    if($_SERVER["REQUEST_METHOD"] === "POST"){
        
    }
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
