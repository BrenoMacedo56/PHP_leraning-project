<?php
include("conection.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['nome'], $_POST['cpf'], $_POST['senha']) && !empty($_POST['nome']) && !empty($_POST['cpf']) && !empty($_POST['senha'])) {
        $nome = mysqli_real_escape_string($conn, $_POST['nome']);
        $cpf = mysqli_real_escape_string($conn, $_POST['cpf']);
        $senha = mysqli_real_escape_string($conn, $_POST['senha']);

        $sql = "INSERT INTO usuarios (cpf, name, password) VALUES ('$cpf', '$nome', '$senha')";

        if ($conn->query($sql) === TRUE) {

            header("Location: CUser.php");
            exit();
        } else {
            echo "<p class='text-red-500 text-center'>Erro ao cadastrar: " . $conn->error . "</p>";
        }
    } else {
        echo "<p class='text-red-500 text-center'>Por favor, preencha todos os campos.</p>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Cadastrar Novo Usuário</h1>
        
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-lg mx-auto mb-8">
            <form method="post" action="show_user.php" class="space-y-4">
                <!-- Nome Input -->
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700">Nome</label>
                    <input type="text" name="nome" id="nome" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Digite seu nome" required>
                </div>
                <!-- CPF Input -->
                <div>
                    <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                    <input type="text" name="cpf" id="cpf" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Digite seu CPF" required>
                </div>
                <!-- Senha Input -->
                <div>
                    <label for="senha" class="block text-sm font-medium text-gray-700">Senha</label>
                    <input type="password" name="senha" id="senha" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Digite sua senha" required>
                </div>
                <!-- Botão de Cadastro -->
                <div>
                    <button type="submit" class="w-full py-2 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600">Cadastrar</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

