<?php
require_once './fileHandler.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['Naam'];
    $location = $_POST['Locatie'];
    $messages = $_POST['Gegevens'];

    $fileHandler = new File();

    $success = $fileHandler->create($name, $location, $messages);

    if ($success) {
        header("Location: ../view/Toegevoegd.html");
        exit;
    } else {
        echo "Geen moskee kunnen toevoegen.";
    }
}
?>