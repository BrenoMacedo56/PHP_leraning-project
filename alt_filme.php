<?php
require_once("conection.php");

$message = '';

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}


function validarAno($ano) {
    $anoAtual = date("Y");
    return is_numeric($ano) && $ano >= 1900 && $ano <= $anoAtual;
}

$idFilmeAnterior = isset($_POST['idFilmeAnterior']) ? $_POST['idFilmeAnterior'] : (isset($_GET['idFilmeAnterior']) ? $_GET['idFilmeAnterior'] : '');

if (empty($idFilmeAnterior)) {
    die("ID do filme anterior não fornecido.");
}

if (isset($_POST['nome'], $_POST['ano'], $_POST['id_genero'])) {
    $nome = trim($_POST['nome']);
    $ano = $_POST['ano'];
    $idGenero = $_POST['id_genero'];

   
    if (!validarAno($ano)) {
        $error = "Ano inválido. Por favor, insira um ano entre 1900 e " . date("Y") . ".";
    } else {
      
        $stmt = $conn->prepare("SELECT id_filme FROM filmes WHERE nome = ? AND id_filme != ?");
        $stmt->bind_param("si", $nome, $idFilmeAnterior);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = "O filme informado já existe no sistema.";
        } else {
         
            $stmt = $conn->prepare("UPDATE filmes SET nome = ?, ano = ?, id_genero = ? WHERE id_filme = ?");
            $stmt->bind_param("siii", $nome, $ano, $idGenero, $idFilmeAnterior);

            if (!$stmt) {
                $error = "Erro na preparação da consulta: " . $conn->error;
            } else {
                if ($stmt->execute()) {
                    header("Location: show_filmes.php?message=Filme atualizado com sucesso&status=success");
                    exit();
                } else {
                    $error = "Erro ao atualizar filme: " . $stmt->error;
                }
            }
            $stmt->close();
        }
    }
} else {
    $stmt = $conn->prepare("SELECT nome, ano, id_genero FROM filmes WHERE id_filme = ?");
    if (!$stmt) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    $stmt->bind_param("i", $idFilmeAnterior);
    $stmt->execute();
    $result = $stmt->get_result();
    $filme = $result->fetch_assoc();

    if (!$filme) {
        die("Filme não encontrado.");
    }

    $stmt->close();
}

// Obter lista de gêneros para o dropdown
$stmtGeneros = $conn->prepare("SELECT id_genero, descricao FROM generos ORDER BY descricao");
$stmtGeneros->execute();
$generos = $stmtGeneros->get_result();
$stmtGeneros->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Filme</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <div class="bg-gradient-to-r from-blue-700 via-sky-500 to-gray-600 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Alterar Filme</h1>
            <a href="show_filme.php" class="text-white px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-blue-600">
                Voltar
            </a>
        </div>

        <?php if (isset($error)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?php echo $error; ?></p>
            </div>
        <?php endif; ?>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form action="alt_filme.php" method="POST" class="space-y-4">
                    <input type="hidden" name="idFilmeAnterior" value="<?php echo htmlspecialchars($filme['id_filme']); ?>"

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nome">Nome do Filme:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($filme['nome']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="ano">Ano:</label>
                    <input type="number" id="ano" name="ano" value="<?php echo htmlspecialchars($filme['ano']); ?>" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="id_genero">Gênero:</label>
                    <select id="id_genero" name="id_genero" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        <?php while ($genero = $generos->fetch_assoc()): ?>
                            <option value="<?php echo $genero['id_genero']; ?>" <?php echo $genero['id_genero'] == $filme['id_genero'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($genero['descricao']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
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