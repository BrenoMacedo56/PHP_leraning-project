<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex justify-center items-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold text-gray-800 text-center mb-6">Login</h2>
        <form method="post" action="login.php" class="space-y-4">
            <!-- CPF Input -->
            <div>
                <label for="CPF" class="block text-sm font-medium text-gray-700">CPF</label>
                <input type="text" name="cpf" id="CPF" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Digite seu CPF">
            </div>
            <!-- Senha Input -->
            <div>
                <label for="PASSWORD" class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" name="password" id="PASSWORD" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-200" placeholder="Digite sua senha">
            </div>
            <!-- Botão de Enviar -->
            <div>
                <button type="submit" class="w-full py-2 px-4 bg-blue-500 text-white rounded-lg hover:bg-blue-600">Entrar</button>
            </div>
        </form>
    </div>
</body>
</html>

