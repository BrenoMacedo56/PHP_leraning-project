<?php
session_start();
include("conection.php");

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $cpf = $_POST['cpf'];
    $password = $_POST['password'];

    $sql = "SELECT name FROM usuarios WHERE cpf = ? AND password = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $cpf, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if ($row['name'] != '') {
                // Configura a sessão
                $_SESSION["cpf"] = $cpf;
                $_SESSION["name"] = $row['name'];
                // Redireciona para main.php
                header("Location: main.php");
                exit();
            } else {
                $error = "Nome de usuário inválido";
            }
        } else {
            $error = "CPF ou senha incorretos";
        }
    } else {
        $error = "Erro na preparação da consulta";
    }

    $stmt->close();
}

// No main.php, você verifica a sessão
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login</h2>
        <?php if (isset($error)): ?>
            <p class="text-red-500 text-center mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <form method="post" action="" class="space-y-4">
            <div>
                <label for="CPF" class="block text-sm font-medium text-gray-700">CPF</label>
                <input type="text" name="cpf" id="CPF" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" required>
            </div>
            <div>
                <label for="PASSWORD" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" name="password" id="PASSWORD" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" required>
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>
