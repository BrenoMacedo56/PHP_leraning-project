<?php
require_once("conection.php");

if (!$conn || $conn->connect_error) {
    die("Erro de conexão: " . ($conn ? $conn->connect_error : "Conexão não estabelecida"));
}

$sql = "SELECT cpf, name FROM usuarios";
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
    <title>Lista de Usuários</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <div class="bg-gradient-to-r from-blue-700 via-sky-500 to-gray-600 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Usuários Cadastrados</h1>
            <a href="sair.php" class=text-withe-700 px-4 py-2 rounded-lg transition duration-300 ease-in-out hover:bg-blue-100">
                Sair
            </a>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-3 px-6 text-left font-semibold text-gray-700">CPF</th>
                        <th class="py-3 px-6 text-left font-semibold text-gray-700">Nome</th>
                        <th class="py-3 px-6 text-left font-semibold text-gray-700">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $cpf = htmlspecialchars($row['cpf']);
                            $name = htmlspecialchars($row['name']);
                            echo <<<HTML
                            <tr class='border-t border-gray-200'>
                                <td class='py-3 px-6'>{$cpf}</td>
                                <td class='py-3 px-6'>{$name}</td>
                                <td class='py-3 px-6'>
                                    <form action='alt_user.php' method='POST'>
                                        <input type='hidden' name='cpfAnterior' value='{$cpf}'>
                                        <button type='submit' class='py-2 px-4 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition duration-300'>Alterar</button>
                                    </form>
                                </td>
                            </tr>
                            HTML;
                        }
                    } else {
                        echo "<tr><td colspan='3' class='py-4 px-6 text-center text-gray-500'>Nenhum usuário cadastrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>