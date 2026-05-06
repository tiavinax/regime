<?php
echo "Test MySQL :<br>";

// Test 1 : avec mysqli
if (function_exists('mysqli_connect')) {
    $conn = @mysqli_connect('127.0.0.1', 'root', '', 'bibliotheque', 3306);
    if ($conn) {
        echo "✅ Connexion avec 127.0.0.1 réussie<br>";
        mysqli_close($conn);
    } else {
        echo "❌ Échec 127.0.0.1 : " . mysqli_connect_error() . "<br>";
    }
    
    $conn2 = @mysqli_connect('localhost', 'root', '', 'bibliotheque', 3306);
    if ($conn2) {
        echo "✅ Connexion avec localhost réussie<br>";
        mysqli_close($conn2);
    } else {
        echo "❌ Échec localhost : " . mysqli_connect_error() . "<br>";
    }
} else {
    echo "❌ mysqli n'est pas installé<br>";
}

// Test 2 : avec PDO
if (class_exists('PDO')) {
    try {
        $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=bibliotheque', 'root', '');
        echo "✅ PDO avec 127.0.0.1 réussie<br>";
    } catch (Exception $e) {
        echo "❌ PDO échec : " . $e->getMessage() . "<br>";
    }
}