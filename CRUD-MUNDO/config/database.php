<?php
    $host = "localhost";
    $user = "root";
    $pass = "";
    $dbname = "bd_mundo";

    $conn = new mysqli($host, $user, $pass, $dbname);
    $conn->set_charset("uft8");

    if ($conn->connect_error) {
        die("Falha na conexão: " $conn->connect_error);
    }