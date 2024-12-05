<?php
require_once("conection.php");

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['descricao'], $_POST['status_genero']) && 
        !empty($_POST['descricao']) && 
        !empty($_POST['status_genero'])) {
        
        $descricao = trim($_POST['descricao']);
        $status_genero = trim($_POST['status_genero']);

        // Validação para status (opcional)
        if (!in_array($status_genero, ['ativo', 'inativo'])) {
            $message = "<p class='text-red-500 text-center'>Status inválido. Use 'ativo' ou 'inativo'.</p>";
        } else {
            $stmt = $conn->prepare("INSERT INTO generos (descricao, status_genero) VALUES (?, ?)");
            if (!$stmt) {
                die("Erro na preparação da consulta: " . $conn->error);
            }

            $stmt->bind_param("ss", $descricao, $status_genero);

            if ($stmt->execute()) {
                $message = "<p class='text-green-500 text-center'>Gênero cadastrado com sucesso!</p>";
            } else {
                $message = "<p class='text-red-500 text-center'>Erro ao cadastrar: " . htmlspecialchars($stmt->error) . "</p>";
            }
            $stmt->close();
        }
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
    <title>Cadastro de Gênero de Filme</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <div class="bg-gradient-to-r from-blue-700 via-sky-500 to-gray-600 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Cadastrar Novo Gênero</h1>
            <a href="show_genero.php" class="text-white-700 px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-blue-100">
                Voltar
            </a>
        </div>

        <?php
        if ($message) {
            echo "<div class='mb-4'>$message</div>";
        }
        ?>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="post" action="" class="space-y-4">
                <div>
                    <label for="descricao" class="block text-gray-700 text-sm font-bold mb-2">Descrição</label>
                    <input type="text" name="descricao" id="descricao" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite a descrição do gênero" required>
                </div>
                <div>
                    <label for="status_genero" class="block text-gray-700 text-sm font-bold mb-2">Status</label>
                    <select name="status_genero" id="status_genero" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <option value="ativo">Ativo</option>
                        <option value="inativo">Inativo</option>
                    </select>
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