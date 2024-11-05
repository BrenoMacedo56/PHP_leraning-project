<?php
session_start();
require_once("conection.php");

$message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['cpf'], $_POST['password']) && !empty($_POST['cpf']) && !empty($_POST['password'])) {
        $cpf = trim($_POST['cpf']);
        $password = $_POST['password'];

        // Verifica se o CPF existe no banco
        $stmt = $conn->prepare("SELECT cpf, password FROM usuarios WHERE cpf = ?");
        if (!$stmt) {
            die("Erro na preparação da consulta: " . $conn->error);
        }
        
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            // Verifica a senha
            if (password_verify($password, $user['password'])) {
                $_SESSION['cpf'] = $cpf;
                header("Location: show_user.php");
                exit();
            } else {
                $message = "Senha incorreta!";
            }
        } else {
            // CPF não encontrado
            header("Location: CUser1.php");
            exit();
        }
        
        $stmt->close();
    } else {
        $message = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Função para mostrar mensagem de erro
        function showError(message) {
            alert(message);
        }
    </script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login</h2>
        
        <?php if (!empty($message)): ?>
            <div class="mb-4 p-4 bg-red-100 text-red-700 border-l-4 border-red-500">
                <?php echo htmlspecialchars($message); ?>
                <script>
                    showError("<?php echo addslashes($message); ?>");
                </script>
            </div>
        <?php endif; ?>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="space-y-4">
            <div>
                <label for="CPF" class="block text-sm font-medium text-gray-700">CPF</label>
                <input type="text" name="cpf" id="CPF" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Digite seu CPF" required>
            </div>
            <div>
                <label for="PASSWORD" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" name="password" id="PASSWORD" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Digite sua senha" required>
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>