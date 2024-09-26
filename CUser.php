<?php
require_once("conection.php");

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nome'], $_POST['cpf'], $_POST['senha']) && !empty($_POST['nome']) && !empty($_POST['cpf']) && !empty($_POST['senha'])) {
        $nome = $_POST['nome'];
        $cpf = $_POST['cpf'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO usuarios (cpf, name, password) VALUES (?, ?, ?)");
        if (!$stmt) {
            die("Erro na preparação da consulta: " . $conn->error);
        }

        $stmt->bind_param("sss", $cpf, $nome, $senha);

        if ($stmt->execute()) {
            header("Location: show_user.php");
            exit();
        } else {
            $message = "<p class='text-red-500 text-center'>Erro ao cadastrar: " . $stmt->error . "</p>";
        }
        $stmt->close();
    } else {
        $message = "<p class='text-red-500 text-center'>Por favor, preencha todos os campos.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <!-- Cabeçalho -->
        <div class="bg-gradient-to-r from-blue-700 via-sky-500 to-gray-600 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Cadastrar Novo Usuário</h1>
            <a href="show_user.php" class="text-white-700 px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-blue-100">
                Voltar
            </a>
        </div>

        <?php
        if ($message) {
            echo "<div class='mb-4'>$message</div>";
        }
        ?>

        <!-- Formulário -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="post" action="" class="space-y-4">
                <div>
                    <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome</label>
                    <input type="text" name="nome" id="nome" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite seu nome" required>
                </div>
                <div>
                    <label for="cpf" class="block text-gray-700 text-sm font-bold mb-2">CPF</label>
                    <input type="text" name="cpf" id="cpf" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite seu CPF" required>
                </div>
                <div>
                    <label for="senha" class="block text-gray-700 text-sm font-bold mb-2">Senha</label>
                    <input type="password" name="senha" id="senha" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite sua senha" required>
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                        Cadastrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

