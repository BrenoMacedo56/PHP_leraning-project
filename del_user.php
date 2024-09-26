<?php
require_once("conection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cpf']) && !empty($_POST['cpf'])) {
        $cpf = $_POST['cpf'];
        
    
        if (!preg_match("/^\d{3}\.\d{3}\.\d{3}-\d{2}$/", $cpf)) {
            die("CPF inválido");
        }

        if (!$conn || $conn->connect_error) {
            die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
        }

        n
        $sql = "DELETE FROM usuarios WHERE cpf = ?";
        $stmt = $conn->prepare($sql);

        if ($stmt) {
            $stmt->bind_param("s", $cpf);
            
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                  
                    $message = "Usuário excluído com sucesso.";
                    $status = "success";
                } else {
                    
                    $message = "Nenhum usuário encontrado com este CPF.";
                    $status = "warning";
                }
            } else {
               
                $message = "Erro ao excluir o usuário: " . $stmt->error;
                $status = "error";
            }

            $stmt->close();
        } else {
       
            $message = "Erro na preparação da consulta: " . $conn->error;
            $status = "error";
        }

        $conn->close();
    } else {
        $message = "CPF não fornecido.";
        $status = "error";
    }
} else {
    $message = "Método de requisição inválido.";
    $status = "error";
}

header("Location: list_users.php?message=" . urlencode($message) . "&status=" . $status);
exit();
?>