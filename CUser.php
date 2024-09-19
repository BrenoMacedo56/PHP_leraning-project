<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Cadastrar Usuário</h2>
        <form method="post" action="cadastro.php" class="space-y-4">
            <div>
                <label for="cpf" class="block text-sm font-medium text-gray-700">CPF</label>
                <input type="text" name="cpf" id="cpf" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">Nome</label>
                <input type="text" name="name" id="name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200">
            </div>
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-green-500 text-white rounded-lg hover:bg-green-600">Cadastrar</button>
            </div>
        </form>
    </div>

</body>
</html>
