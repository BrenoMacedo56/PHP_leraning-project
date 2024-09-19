<?php
include("conection.php");

if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT cpf, name FROM usuarios";
$result = $conn->query($sql);

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
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Usuários Cadastrados</h1>

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="py-2 px-4 text-left font-semibold text-gray-700">CPF</th>
                        <th class="py-2 px-4 text-left font-semibold text-gray-700">Nome</th>
                        <th class="py-2 px-4 text-left font-semibold text-gray-700">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr class='border-t border-gray-200'>";
                            echo "<td class='py-2 px-4'>" . htmlspecialchars($row['cpf']) . "</td>";
                            echo "<td class='py-2 px-4'>" . htmlspecialchars($row['name']) . "</td>";
                            echo "<td class='py-2 px-4'>";
                            echo "<form action='alt_user.php' method='POST'>";
                            echo "<input type='hidden' name='cpfAnterior' value='" . htmlspecialchars($row['cpf']) . "'>";
                            echo "<button type='submit' class='py-2 px-4 bg-blue-500 text-white rounded'>Alterar</button>";
                            echo "</form>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3' class='py-4 px-4 text-center text-gray-500'>Nenhum usuário cadastrado</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
