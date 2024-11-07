<?php
require_once("conection.php");

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

if (isset($_GET['cpf'], $_GET['cpfAnterior'])) {
    $cpf = $_GET['cpf'];
    $cpfAnterior = $_GET['cpfAnterior'];

    $stmt = $conn->prepare("SELECT cpf FROM usuarios WHERE cpf = ? AND cpf != ?");
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }
    $stmt->bind_param("ss", $cpf, $cpfAnterior);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo json_encode(["exists" => true]);
    } else {
        echo json_encode(["exists" => false]);
    }

    $stmt->close();
}

$conn->close();
?>