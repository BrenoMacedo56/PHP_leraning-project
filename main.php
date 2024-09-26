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
        <div class="bg-green-500 text-white p-6 rounded-lg shadow-lg mb-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold">Cabeçalho</h1>
            <a href="sair.php" class="bg-blue-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                Sair
            </a>
        </div>

        <div class="flex space-x-4">
            <div class="w-1/4 bg-gray-200 p-4 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Menu</h2>
                <ul class="space-y-2">
                    <li><a href="./alt_user.php" class="text-blue-600 hover:underline">Alterar usuários</a></li>
                    <li><a href="./CUser.php" class="text-blue-600 hover:underline">Cadastrar Novo Usuário</a></li>
                    <li><a href="./show_user.php" class="text-blue-600 hover:underline">Mostrar Usuário</a></li>
                </ul>
            </div>

    
            <div class="w-3/4 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-2xl font-bold mb-4">Conteúdo</h2>
                <p>Esse é o conteúdo principal da página.</p>
                
                <div class="p-4 w-96 justify-items-start float-left text-right">
                    <div>
                        <img src="https://tse1.mm.bing.net/th/id/OIP.iynkPRLBGkxF05Lv1k-fawAAAA?rs=1&pid=ImgDetMain" alt="" class="mb-5">
                        <p> "आपकी मेहनत ही आपकी सफलता की कुंजी है।"</p>
                    </div>
                    <img src="https://www.stevenaitchison.co.uk/wp-content/uploads/Abstract_Image_Test-1.jpg" alt="">
                </div>
            </div>
        </div>
    </div>
</body>

</html>
