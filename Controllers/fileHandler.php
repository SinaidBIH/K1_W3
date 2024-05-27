<?php
require_once 'dbHandler.php';

class File
{
    private $mysqli;

    public function __construct()
    {
        $this->mysqli = connectDB();
        if ($this->mysqli->connect_error) {
            die("Verbinding mislukt: " . $this->mysqli->connect_error);
        }
    }

    public function create($name, $location, $messages)
    {
        $stmt = $this->mysqli->prepare("INSERT INTO moskeen (Naam, Locatie, Gegevens) VALUES (?, ?, ?)");
        if ($stmt === false) {
            die('Voorbereiding mislukt: ' . htmlspecialchars($this->mysqli->error));
        }

        $stmt->bind_param('sss', $name, $location, $messages);

        if (!$stmt->execute()) {
            die('Uitvoering mislukt: ' . htmlspecialchars($stmt->error));
        }

        $uploadDirectory = dirname(dirname(__FILE__)) . "/uploads/";
        if (!file_exists($uploadDirectory)) {
            if (!mkdir($uploadDirectory, 0777, true)) {
                die("Maken van de uploads-map mislukt");
            }
        }

        echo '<pre>';
        print_r($_FILES);
        echo '</pre>';

        if (isset($_FILES['pictures'])) {
            $targetDir = $uploadDirectory . $name . '/';
            if (!file_exists($targetDir)) {
                if (!mkdir($targetDir, 0777, true)) {
                    die("Maken van map voor $name mislukt");
                }
            }

            foreach ($_FILES['pictures']['name'] as $key => $fileName) {
                if ($_FILES['pictures']['error'][$key] == UPLOAD_ERR_OK) {
                    $targetPath = $targetDir . basename($fileName);

                    if (move_uploaded_file($_FILES['pictures']['tmp_name'][$key], $targetPath)) {
                        echo "Bestand $fileName is geldig en is succesvol geüpload.\n";
                    } else {
                        die("Verplaatsen van geüpload bestand $fileName mislukt. Controleer rechten en bestandslocaties.");
                    }
                } else {
                    echo "Fout bij uploaden van bestand $fileName. Foutcode: " . $_FILES['pictures']['error'][$key] . "\n";
                }
            }
        } else {
            die("Geen bestanden geüpload of er was een uploadfout.");
        }

        $stmt->close();
        return true;
    }

    public function __destruct()
    {
        if ($this->mysqli) {
            closeConn($this->mysqli);
        }
    }
}
?>