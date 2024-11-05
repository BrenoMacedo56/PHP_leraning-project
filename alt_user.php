<?php
require_once("conection.php");

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

$cpfAnterior = isset($_POST['cpfAnterior']) ? $_POST['cpfAnterior'] : (isset($_GET['cpfAnterior']) ? $_GET['cpfAnterior'] : '');

if (empty($cpfAnterior)) {
    die("CPF anterior não fornecido.");
}

if (isset($_POST['cpf'], $_POST['name'])) {
    $cpf = $_POST['cpf'];
    $nome = $_POST['name'];
    $senha = !empty($_POST['password']) ? $_POST['password'] : null;

    // Verifica se a senha atende aos critérios
    if ($senha && !preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $senha)) {
        die("Erro: A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número, um caractere especial e ter no mínimo 8 caracteres.");
    }

    // Hash da senha se estiver preenchida
    $senha = $senha ? password_hash($senha, PASSWORD_DEFAULT) : null;

    if ($senha) {
        $stmt = $conn->prepare("UPDATE usuarios SET cpf = ?, name = ?, password = ? WHERE cpf = ?");
        $stmt->bind_param("ssss", $cpf, $nome, $senha, $cpfAnterior);
    } else {
        $stmt = $conn->prepare("UPDATE usuarios SET cpf = ?, name = ? WHERE cpf = ?");
        $stmt->bind_param("sss", $cpf, $nome, $cpfAnterior);
    }

    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    if ($stmt->execute()) {
        header("Location: show_user.php?message=Usuário atualizado com sucesso&status=success");
        exit();
    } else {
        $error = "Erro ao atualizar usuário: " . $stmt->error;
    }

    $stmt->close();
} else {
    $stmt = $conn->prepare("SELECT cpf, name FROM usuarios WHERE cpf = ?");
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("s", $cpfAnterior);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if (!$user) {
        die("Usuário não encontrado.");
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        function validarFormulario() {
            const senha = document.getElementById('senha').value;
            const regexSenha = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;

            if (senha && !regexSenha.test(senha)) {
                alert('A senha deve conter pelo menos uma letra maiúscula, uma letra minúscula, um número, um caractere especial e ter no mínimo 8 caracteres.');
                return false;
            }

            const cpf = document.getElementById('cpf').value;
            const cpfAnterior = document.querySelector('input[name="cpfAnterior"]').value;

            // Verifica CPF via AJAX
            return fetch(`check_cpf.php?cpf=${cpf}&cpfAnterior=${cpfAnterior}`)
                .then(response => response.json())
                .then(data => {
                    if (data.exists) {
                        alert('Erro: O CPF informado já existe.');
                        return false;
                    }
                    return true;
                })
                .catch(error => {
                    console.error('Erro na verificação do CPF:', error);
                    return false;
                });
        }
    </script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <div class="bg-gradient-to-r from-blue-700 via-sky-500 to-gray-600 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Alterar Usuário</h1>
            <a href="show_user.php" class="text-white px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-blue-600">
                Voltar
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form action="alt_user.php" method="POST" class="space-y-4" onsubmit="return validarFormulario()">
                <input type="hidden" name="cpfAnterior" value="<?php echo htmlspecialchars($user['cpf']); ?>">

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($user['cpf']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nome:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="senha">Senha:</label>
                    <input type="password" id="senha" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg transition duration-300">
                        Salvar Alterações
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>