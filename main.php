
<?php
session_start();
?>

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

        <div class="bg-gradient-to-r from-blue-700 via-sky-500 to-gray-600 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Página Principal</h1>
            <a href="sair.php" class="text-white px-4 py-2 rounded-lg transition duration-300 ease-in-out">
                Sair
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">

            <div class="col-span-1 bg-gray-200 p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Menu</h2>
                <ul class="space-y-4">
                    <li>
                        <a href="./alt_user.php" class="text-blue-600 hover:text-blue-800 transition duration-200">
                            Alterar Usuários
                        </a>
                    </li>
                    <li>
                        <a href="./CUser.php" class="text-blue-600 hover:text-blue-800 transition duration-200">
                            Cadastrar Novo Usuário
                        </a>
                    </li>
                    <li>
                        <a href="./show_user.php" class="text-blue-600 hover:text-blue-800 transition duration-200">
                            Mostrar Usuário
                        </a>
                    </li>
                    <li>
                        <a href="./show_filme.php" class="text-blue-600 hover:text-blue-800 transition duration-200">
                            Mostrar Filmes
                        </a>
                    </li>
                    <li>
                        <a href="./CD_filme.php" class="text-blue-600 hover:text-blue-800 transition duration-200">
                            Cadastrar Filmes
                        </a>
                    </li>
                </ul>
            </div>

            <div class="col-span-1 lg:col-span-3 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-4">Conteúdo Principal</h2>
                <p class="mb-6">Esse é o conteúdo principal da página.</p>

                <div class="flex flex-col lg:flex-row items-center lg:items-start lg:justify-between">
                    <div class="w-full lg:w-1/2">
                        <img src="https://tse3.mm.bing.net/th/id/OIP.8O_PFSnGa8DFxS3nM6uHkgHaEK?rs=1&pid=ImgDetMain" alt="" class="mb-5 rounded-lg shadow-lg max-w-96">
                        <p class="text-gray-700 italic text-center lg:text-left">"आपकी मेहनत ही आपकी सफलता की कुंजी है।"</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>