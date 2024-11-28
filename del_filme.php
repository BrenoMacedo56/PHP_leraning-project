<?php
require_once("conection.php");

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

// Verifica se o CPF foi enviado via GET
if (isset($_GET['id_filme'])) {
    $cpf = $_GET['id_filme'];

    // Prepara a instrução SQL para deletar o usuário
    $stmt = $conn->prepare("DELETE FROM filmes WHERE id_filme = ?");
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $cpf);

    // Executa a consulta e verifica se a exclusão foi bem-sucedida
    if ($stmt->execute()) {
        $stmt->close();
        $conn->close();
        header("Location: show_filme.php?message=Filme excluído com sucesso&status=success");
        exit();
    } else {
        $error = "Erro ao excluir usuário: " . $stmt->error;
        $stmt->close();
        $conn->close();
        header("Location: show_user.php?message=" . urlencode($error) . "&status=error");
        exit();
    }
} else {
    // Caso o CPF não tenha sido enviado, redireciona com uma mensagem de erro
    $conn->close();
    header("Location: show_filme.php?message=Filme não fornecido para exclusão&status=error");
    exit();
}
