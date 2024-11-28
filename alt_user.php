<?php
require_once("conection.php");

$message = '';

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

function validarCPF($cpf) {
    $cpf = preg_replace('/[^0-9]/i', '', $cpf);
    
    if (strlen($cpf) != 11) {
        return false;
    }

    if (preg_match('/(\d)\1{10}/', $cpf)) {
        return false;
    }

    for ($t = 9; $t < 11; $t++) {
        for ($d = 0, $c = 0; $c < $t; $c++) {
            $d += $cpf[$c] * (($t + 1) - $c);
        }
        $d = ((10 * $d) % 11) % 10;
        if ($cpf[$c] != $d) {
            return false;
        }
    }
    return true;
}

function validarSenha($senha) {
    // Verifica o comprimento mínimo
    if (strlen($senha) < 8) {
        return "A senha deve ter pelo menos 8 caracteres.";
    }

    // Verifica letra maiúscula
    if (!preg_match('/[A-Z]/', $senha)) {
        return "A senha deve conter pelo menos uma letra maiúscula.";
    }

    // Verifica letra minúscula
    if (!preg_match('/[a-z]/', $senha)) {
        return "A senha deve conter pelo menos uma letra minúscula.";
    }

    // Verifica número
    if (!preg_match('/[0-9]/', $senha)) {
        return "A senha deve conter pelo menos um número.";
    }

    // Verifica caractere especial
    if (!preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/', $senha)) {
        return "A senha deve conter pelo menos um caractere especial (!@#$%^&*()-_=+{};:,<.>).";
    }

    return true;
}

$cpfAnterior = isset($_POST['cpfAnterior']) ? $_POST['cpfAnterior'] : (isset($_GET['cpfAnterior']) ? $_GET['cpfAnterior'] : '');

if (empty($cpfAnterior)) {
    die("CPF anterior não fornecido.");
}

if (isset($_POST['cpf'], $_POST['name'])) {
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf']);
    $nome = trim($_POST['name']);
    $senha = $_POST['password'];

    // Validação da senha
    if (empty($senha)) {
        $error = "A senha é obrigatória.";
    } else {
        $validacaoSenha = validarSenha($senha);
        if ($validacaoSenha !== true) {
            $error = $validacaoSenha;
        } else if (!validarCPF($cpf)) {
            $error = "CPF inválido. Por favor, insira um CPF válido.";
        } else {
            $stmt = $conn->prepare("SELECT cpf FROM usuarios WHERE cpf = ? AND cpf != ?");
            $stmt->bind_param("ss", $cpf, $cpfAnterior);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                $error = "O CPF informado já existe no sistema.";
            } else {
                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);
                
                $stmt = $conn->prepare("UPDATE usuarios SET cpf = ?, name = ?, password = ? WHERE cpf = ?");
                $stmt->bind_param("ssss", $cpf, $nome, $senhaHash, $cpfAnterior);

                if (!$stmt) {
                    $error = "Erro na preparação da consulta: " . $conn->error;
                } else {
                    if ($stmt->execute()) {
                        header("Location: show_user.php?message=Usuário atualizado com sucesso&status=success");
                        exit();
                    } else {
                        $error = "Erro ao atualizar usuário: " . $stmt->error;
                    }
                }
            }
            $stmt->close();
        }
    }
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
        function formatarCPF(campo) {
            let cpf = campo.value.replace(/\D/g, '');
            if (cpf.length <= 11) {
                cpf = cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
                campo.value = cpf;
            }
        }

        function validarFormulario() {
            const senha = document.getElementById('senha').value;
            const cpf = document.getElementById('cpf').value.replace(/\D/g, '');
            
            if (!senha) {
                alert('A senha é obrigatória.');
                return false;
            }

            if (senha.length < 8) {
                alert('A senha deve ter pelo menos 8 caracteres.');
                return false;
            }

            if (!/[A-Z]/.test(senha)) {
                alert('A senha deve conter pelo menos uma letra maiúscula.');
                return false;
            }

            if (!/[a-z]/.test(senha)) {
                alert('A senha deve conter pelo menos uma letra minúscula.');
                return false;
            }

            if (!/[0-9]/.test(senha)) {
                alert('A senha deve conter pelo menos um número.');
                return false;
            }

            if (!/[!@#$%^&*()\-_=+{};:,<.>]/.test(senha)) {
                alert('A senha deve conter pelo menos um caractere especial (!@#$%^&*()-_=+{};:,<.>).');
                return false;
            }

            if (cpf.length !== 11) {
                alert('CPF inválido. Por favor, insira um CPF válido.');
                return false;
            }

            return true;
        }

        window.onload = function() {
            const cpfInput = document.getElementById('cpf');
            cpfInput.addEventListener('input', function() {
                formatarCPF(this);
            });
        };
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
                    <input type="text" id="cpf" name="cpf" value="<?php echo htmlspecialchars($user['cpf']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required maxlength="14">
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nome:</label>
                    <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="senha">Senha:</label>
                    <input type="password" id="senha" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                    <ul class="text-sm text-gray-600 mt-1 list-disc list-inside">
                        <li>Mínimo de 8 caracteres</li>
                        <li>Pelo menos uma letra maiúscula</li>
                        <li>Pelo menos uma letra minúscula</li>
                        <li>Pelo menos um número</li>
                        <li>Pelo menos um caractere especial (!@#$%^&*()-_=+{};:,<.>)</li>
                    </ul>
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