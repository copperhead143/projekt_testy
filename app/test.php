<?php
session_start();

interface AnswerProcessingStrategy{
    public function processAnswer($userAnswer, $correctAnswer, $questionId, $testId, $userId, $conn);
}

class StandardAnswerProcessing implements AnswerProcessingStrategy{
    public function processAnswer($userAnswer, $correctAnswer, $questionId, $testId, $userId, $conn) {
        $czyPoprawna = ($userAnswer === $correctAnswer) ? 1 : 0;

        $sqlInsert = "INSERT INTO odpowiedzi (nazwa_testu, id_osoby, pytanie, odpowiedz, czy_poprawna)
                      VALUES ('$testId', '$userId', '$questionId', '$userAnswer', '$czyPoprawna')";

        if ($conn->query($sqlInsert) !== TRUE) {
            echo "Błąd podczas zapisywania odpowiedzi: " . $conn->error;
        }
    }
}

class CustomAnswerProcessing implements AnswerProcessingStrategy{
    public function processAnswer($userAnswer, $correctAnswer, $questionId, $testId, $userId, $conn){
        $userAnswerShort = substr($userAnswer, 0, 50);

        $czyPoprawna = ($userAnswerShort === $correctAnswer) ? 1 : 0;

        $sqlInsert = "insert into odpowiedzi (nazwa_testu, id_osoby, pytanie, odpowiedz, czy_poprawna)
        VALUES ('$testId', '$userId', '$questionId', '$userAnswerShort', '$czyPoprawna')";

        if($conn->query($sqlInsert)!== TRUE){
            echo "odpowiedz sie nie zapisala D:" . $conn->error;
        }
    }
}


class AnswerProcessor{
    private $strategy;

    public function setStrategy(AnswerProcessingStrategy $strategy){
        $this->strategy = $strategy;
    }

    public function processAnswer($userAnswer, $correctAnswer, $questionId, $testId, $userId, $conn) {
        $this->strategy->processAnswer($userAnswer, $correctAnswer, $questionId, $testId, $userId, $conn);
    }

}

if(isset($_SESSION['role']) && $_SESSION['role'] === 'uczen'){
    if($_SERVER["REQUEST_METHOD"] === "GET" && isset($_GET['test_id'])){
        $testId = $_GET['test_id'];

        $conn = new mysqli("localhost", "root", "", "testy");

        if($conn->connect_error){
            die("przykra sprawa kolezko baza nie dziala". $conn->connect_error);
        }

        $sql = "SELECT * FROM pytania WHERE test_nazwa = '$testId'";
        $result = $conn->query($sql);

        $questions = [];

        if($result->num_rows > 0){
            while($row = $result->fetch_assoc()){
                $questions[] = $row;
            }
        }

        if($_SERVER["REQUEST_METHOD"] === "POST"){
            $userId = $_SESSION['user_id'];

            $answerProcessor = new AnswerProcessor();

            foreach ($questions as $position => $question) {
                $answerFieldName = "answer_" . $position;
                $userAnswer = $_POST[$answerFieldName];

                $sqlCheck = "SELECT poprawna_odpowiedz FROM pytania WHERE id = {$question['id']}";
                $resultCheck = $conn->query($sqlCheck);

                if ($resultCheck->num_rows > 0) {
                    $rowCheck = $resultCheck->fetch_assoc();
                    $correctAnswer = $rowCheck['poprawna_odpowiedz'];

                    //dwie strategie procesowania odpowiedzi
                    //jedna tylko wysyla odpowiedz
                    //druga ja skraca do 50 znakow

                    //$answerProcessor->setStrategy(new StandardAnswerProcessing());

                    $answerProcessor->setStrategy(new CustomAnswerProcessing());

                    $answerProcessor->processAnswer($userAnswer, $correctAnswer, $question['id'], $testId, $userId, $conn);
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