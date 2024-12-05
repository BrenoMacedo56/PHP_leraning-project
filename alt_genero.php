<?php
require_once("conection.php");

$message = '';

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

// Função para validar o nome do gênero
function validarNomeGenero($nomeGenero) {
    return !empty($nomeGenero) && strlen($nomeGenero) <= 255;
}

$idGeneroAnterior = isset($_POST['idGeneroAnterior']) ? $_POST['idGeneroAnterior'] : (isset($_GET['idGeneroAnterior']) ? $_GET['idGeneroAnterior'] : '');

if (empty($idGeneroAnterior)) {
    die("ID do gênero anterior não fornecido.");
}

if (isset($_POST['nome_genero'])) {
    $nomeGenero = trim($_POST['nome_genero']);

    if (!validarNomeGenero($nomeGenero)) {
        $error = "Nome do gênero inválido. Certifique-se de que não esteja vazio e tenha no máximo 255 caracteres.";
    } else {
        // Verificar se já existe um gênero com o mesmo nome (diferente do atual)
        $stmt = $conn->prepare("SELECT id_genero FROM generos WHERE descricao = ? AND id_genero != ?");
        $stmt->bind_param("si", $nomeGenero, $idGeneroAnterior);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "O gênero informado já existe no sistema.";
        } else {
            // Atualizar os dados do gênero
            $stmt = $conn->prepare("UPDATE generos SET descricao = ? WHERE id_genero = ?");
            $stmt->bind_param("si", $nomeGenero, $idGeneroAnterior);

            if (!$stmt) {
                $error = "Erro na preparação da consulta: " . $conn->error;
            } else {
                if ($stmt->execute()) {
                    header("Location: show_genero.php?message=Gênero atualizado com sucesso&status=success");
                    exit();
                } else {
                    $error = "Erro ao atualizar gênero: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
} else {
    // Carregar os dados do gênero existente
    $stmt = $conn->prepare("SELECT descricao FROM generos WHERE id_genero = ?");
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $idGeneroAnterior);
    $stmt->execute();
    $result = $stmt->get_result();
    $genero = $result->fetch_assoc();

    if (!$genero) {
        die("Gênero não encontrado.");
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
    <title>Alterar Gênero</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <div class="bg-gradient-to-r from-blue-700 via-sky-500 to-gray-600 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Alterar Gênero</h1>
            <a href="show_genero.php" class="text-white px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-green-600">
                Voltar
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form action="alt_genero.php" method="POST" class="space-y-4">
                <input type="hidden" name="idGeneroAnterior" value="<?php echo htmlspecialchars($idGeneroAnterior); ?>">

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nome_genero">Nome do Gênero:</label>
                    <input type="text" id="nome_genero" name="nome_genero" value="<?php echo htmlspecialchars($genero['descricao']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500" required>
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