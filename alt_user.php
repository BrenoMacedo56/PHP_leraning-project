<?php
include("conection.php");

$cpfAnterior = $_POST['cpfAnterior'];

$sql = "SELECT cpf, name FROM usuarios WHERE cpf = '$cpfAnterior'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Usuário</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <h1 class="text-3xl font-bold text-center text-gray-800 mb-8">Alterar Usuário</h1>

        <div class="w-full max-w-md mx-auto bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <form action="show_user.php" method="POST">
                <input type="hidden" name="cpfAnterior" value="<?php echo htmlspecialchars($user['cpf']); ?>">

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cpf">CPF:</label>
                    <input type="text" name="cpf" value="<?php echo htmlspecialchars($user['cpf']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nome:</label>
                    <input type="text" name="nome" value="<?php echo htmlspecialchars($user['name']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="senha">Senha:</label>
                    <input type="password" name="senha" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Salvar
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
