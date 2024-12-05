<?php
require_once("conection.php");

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

// Verifica se o ID do gênero foi enviado via GET
if (isset($_GET['id_genero'])) {
    $id_genero = $_GET['id_genero'];

    // Prepara a instrução SQL para deletar o gênero
    $stmt = $conn->prepare("DELETE FROM generos WHERE id_genero = ?");
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $id_genero);

    // Executa a consulta e verifica se a exclusão foi bem-sucedida
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: show_genero.php?message=Gênero excluído com sucesso&status=success");
        exit();
    } else {
        $error = "Erro ao excluir gênero: " . $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: show_genero.php?message=" . urlencode($error) . "&status=error");
        exit();
    }
} else {
    // Caso o ID do gênero não tenha sido enviado, redireciona com uma mensagem de erro
    $conn->close();
    header("Location: show_genero.php?message=Gênero não fornecido para exclusão&status=error");
    exit();
}