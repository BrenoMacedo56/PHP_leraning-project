<?php
require_once("conection.php");

$message = '';

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

// Obter id_genero anterior
$idGeneroAnterior = isset($_POST['idGeneroAnterior']) ? $_POST['idGeneroAnterior'] : (isset($_GET['idGeneroAnterior']) ? $_GET['idGeneroAnterior'] : '');

if (empty($idGeneroAnterior)) {
    die("ID do gênero anterior não fornecido.");
}

if (isset($_POST['descricao'])) {
    $nome = trim($_POST['descricao']);

    // Validar nome do gênero
    if (empty($nome)) {
        $error = "O nome do gênero não pode estar vazio.";
    } else {
        // Verificar se o nome do gênero já existe
        $stmt = $conn->prepare("SELECT id_genero FROM generos WHERE descricao= ? AND id_genero != ?");
        $stmt->bind_param("si", $nome, $idGeneroAnterior);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "O gênero informado já existe no sistema.";
        } else {
            // Atualizar o gênero
            $stmt = $conn->prepare("UPDATE generos SET descricao = ? WHERE id_genero = ?");
            $stmt->bind_param("si", $nome, $idGeneroAnterior);

            if (!$stmt) {
                $error = "Erro na preparação da consulta: " . $conn->error;
            } else {
                if ($stmt->execute()) {
                    header("Location: show_generos.php?message=Gênero atualizado com sucesso&status=success");
                    header("Location: show_genero.php");
                    exit();
                } else {
                    $error = "Erro ao atualizar gênero: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
} else {
    // Obter os dados do gênero para edição
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
            <a href="show_generos.php" class="text-white px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-blue-600">
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
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">Nome do Gênero:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($genero['descricao']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
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