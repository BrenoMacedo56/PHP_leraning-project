<?php
require_once("conection.php");

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

// Consulta os gêneros
$sql = "SELECT id_genero, descricao FROM generos";
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("Erro na preparação da consulta: " . $conn->error);
}

$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Gêneros</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <?php
        if (isset($_GET['message']) && isset($_GET['status'])) {
            $message = htmlspecialchars($_GET['message']);
            $status = htmlspecialchars($_GET['status']);
            $alertClass = $status === 'success' ? 'bg-green-100 border-green-500 text-green-700' : 
                          ($status === 'error' ? 'bg-red-100 border-red-500 text-red-700' : 
                          'bg-yellow-100 border-yellow-500 text-yellow-700');
            
            echo "<div class='mb-4 border-l-4 p-4 {$alertClass}' role='alert'>";
            echo "<p>{$message}</p>";
            echo "</div>";
        }
        ?>

        <div class="bg-gradient-to-r from-blue-700 via-sky-500 to-gray-600 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Gêneros Cadastrados</h1>
            <div class="space-x-4">
                <a href="main.php" class="text-white px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-green-600">
                    Página Inicial
                </a>
                <a href="sair.php" class="text-white px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-red-600">
                    Sair
                </a>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <div class="mb-4 flex justify-end">
                <a href="CD_genero.php" class="bg-blue-500 text-white px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-blue-600">
                    Novo Gênero
                </a>
            </div>
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-6 text-left font-semibold text-gray-700">ID</th>
                        <th class="py-3 px-6 text-left font-semibold text-gray-700">Nome</th>
                        <th class="py-3 px-6 text-center font-semibold text-gray-700">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $id_genero = htmlspecialchars($row['id_genero']);
                            $nome = htmlspecialchars($row['descricao']);
                            echo <<<HTML
                            <tr class='border-t border-gray-200 hover:bg-gray-50'>
                                <td class='py-3 px-6'>{$id_genero}</td>
                                <td class='py-3 px-6'>{$nome}</td>
                                <td class='py-3 px-6 text-center'>
                                    <a href='alt_genero.php?idGeneroAnterior={$id_genero}' class='py-1 px-3 bg-yellow-500 hover:bg-yellow-600 text-white rounded transition duration-300'>
                                        Alterar
                                    </a>
                                    <a href='del_genero.php?id_genero={$id_genero}' class='bg-red-500 py-1 px-3 text-white rounded transition duration-300 ml-2' onclick="return confirm('Tem certeza que deseja excluir este gênero?')">
                                        Excluir
                                    </a>
                                </td>
                            </tr>
                            HTML;
                        }
                    } else {
                        echo "<tr><td colspan='3' class='py-4 px-6 text-center text-gray-500'>Nenhum gênero cadastrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>