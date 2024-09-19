<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Principal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10">
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg mb-6">
            <h1 class="text-3xl font-bold">Cabeçalho</h1>
        </div>

        <div class="flex space-x-4">
            <!-- Menu -->
            <div class="w-1/4 bg-gray-200 p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Menu</h2>
                <ul class="space-y-2">
                    <li><a href="#" class="text-blue-600 hover:underline">Item 1</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Item 2</a></li>
                    <li><a href="#" class="text-blue-600 hover:underline">Item 3</a></li>
                </ul>
            </div>

            <!-- Conteúdo -->
            <div class="w-3/4 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-4">Conteúdo</h2>
                <p>Esse é o conteúdo principal da página.</p>
            </div>
        </div>
    </div>
</body>
</html>
