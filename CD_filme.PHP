<?php
require_once("conection.php");

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nome'], $_POST['ano'], $_POST['id_genero']) && 
        !empty($_POST['nome']) && 
        !empty($_POST['ano']) && 
        !empty($_POST['id_genero'])) {
        
        $nome = trim($_POST['nome']);
        $ano = intval($_POST['ano']);
        $id_genero = intval($_POST['id_genero']);
        

        // Validação adicional para ano (opcional)
        if ($ano < 1888 || $ano > intval(date("Y"))) { // O cinema começou em 1888
            $message = "<p class='text-red-500 text-center'>Ano inválido. Insira um ano entre 1888 e o ano atual.</p>";
        } else {
            // Inserção no banco de dados
            $stmt = $conn->prepare("INSERT INTO filmes (nome, ano, id_genero) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Erro na preparação da consulta: " . $conn->error);
            }
            
            $stmt->bind_param("sii", $nome, $ano, $id_genero);
            
            if ($stmt->execute()) {
                $message = "<p class='text-green-500 text-center'>Filme cadastrado com sucesso!</p>";
                header("Location: main.php");
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
    <title>Cadastro de Filme</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <div class="bg-gradient-to-r from-blue-700 via-sky-500 to-gray-600 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Cadastrar Novo Filme</h1>
            <a href="show_filmes.php" class="text-white-700 px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-blue-100">
                Ver Filmes
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
                    <label for="nome" class="block text-gray-700 text-sm font-bold mb-2">Nome do Filme</label>
                    <input type="text" name="nome" id="nome" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o nome do filme" required>
                </div>
                <div>
                    <label for="ano" class="block text-gray-700 text-sm font-bold mb-2">Ano de Lançamento</label>
                    <input type="number" name="ano" id="ano" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o ano de lançamento" required>
                </div>
                <div>
                    <label for="id_genero" class="block text-gray-700 text-sm font-bold mb-2">ID do Gênero</label>
                    <input type="number" name="id_genero" id="id_genero" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Digite o ID do gênero" required>
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
