<?php
session_start();

?>
<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php include("cabecera.php"); ?>

    <style>
        @keyframes welcomeAnimation {
            0% {
                opacity: 0;
                transform: translate(-50%, -100%);
            }

            20% {
                opacity: 1;
                transform: translate(-50%, 0);
            }

            80% {
                opacity: 1;
                transform: translate(-50%, 0);
            }

            100% {
                opacity: 0;
                transform: translate(-50%, -100%);
            }
        }

        .animate-welcome {
            animation: welcomeAnimation 3s ease-in-out forwards;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4">
        <?php
        require_once(!isset($_SESSION["user_id"]) ? "templates/navbar.php" : "templates/navbarLoggedIn.php");

        if (!isset($_SESSION["user_id"])) {
            echo "<div class='min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 py-20 px-4'>";
            echo "<div class='max-w-3xl mx-auto text-center'>";

            echo "<div class='mb-8'>";
            echo "<svg class='w-16 h-16 mx-auto text-blue-500' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width ='2' d='M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z'></path>";
            echo "</svg>";
            echo "</div>";

            echo "<h1 class='text-4xl font-light mb-6 text-gray-800 tracking-tight'>Bienvenido a <span class='font-medium text-blue-600'>Central</span></h1>";

            echo "<p class='text-lg text-gray-600 mb-12 leading-relaxed'>Descubre una nueva forma de comprar. Simple, rápida y segura.</p>";

            echo "<div class='space-y-4 sm:space-y-0 sm:space-x-4 flex flex-col sm:flex-row justify-center'>";

            echo "<button onclick='openLoginModal()' 
                     class='inline-flex items-center justify-center px-8 py-3 border border-transparent 
                            text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 
                            transition-all duration-200 ease-in-out transform hover:-translate-y-0.5'>";
            echo "<svg class='w-5 h-5 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1'></path>";
            echo "</svg>Iniciar Sesión</button>";

            echo "<button onclick='openRegisterModal()' 
                     class='inline-flex items-center justify-center px-8 py-3 border border-gray-300 
                            text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 
                            transition-all duration-200 ease-in-out transform hover:-translate-y-0.5'>";
            echo "<svg class='w-5 h-5 mr-2' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z'></path>";
            echo "</svg>Crear Cuenta</a>";
            echo "</div>";

            echo "<div class='mt-12 grid grid-cols-1 md:grid-cols-3 gap-4 max-w-4xl mx-auto'>";

            echo "<div class='p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow'>";
            echo "<div class='text-blue-500 mb-3'>";
            echo "<svg class='w-6 h-6 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z'></path>";
            echo "</svg></div>";
            echo "<h3 class='text-lg font-medium text-gray-800 mb-2'>Cartera Virtual</h3>";
            echo "</div>";

            echo "<div class='p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow text-center'>";
            echo "<div class='text-blue-500 mb-3'>";
            echo "<svg class='w-6 h-6 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'></path>";
            echo "</svg></div>";
            echo "<h3 class='text-lg font-medium text-gray-800 mb-2'>Pedidos del Día</h3>";
            echo "</div>";

            echo "<div class='p-4 bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow'>";
            echo "<div class='text-blue-500 mb-3'>";
            echo "<svg class='w-6 h-6 mx-auto' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'></path>";
            echo "</svg></div>";
            echo "<h3 class='text-lg font-medium text-gray-800 mb-2'>Personaliza tu Perfil</h3>";
            echo "</div>";

            echo "</div>";
            echo "</div>";
            echo "</div>";
        } else {
            require_once("conexion.php");
            require_once("RepositorioPedidos.php");

            $repo = new RepositorioPedidos($conexion);
            $ultimosPedidos = $repo->getUltimosPedidos();
            $productoFavorito = $repo->getProductoFavorito();

            echo "<div id='welcomeCard' class='fixed top-0 left-1/2 transform -translate-x-1/2 bg-white rounded-lg shadow-lg p-6 mt-4 border-l-4 border-green-500 animate-welcome z-50'>";
            echo "<div class='flex items-center'>";
            echo "<div class='p-3 rounded-full bg-green-100'>";
            echo "<svg class='h-6 w-6 text-green-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M5 13l4 4L19 7'></path></svg>";
            echo "</div>";
            echo "<div class='ml-4'>";
            echo "<p class='text-lg font-semibold text-gray-800'>¡Bienvenido, {$_SESSION["username"]}!</p>";
            echo "<p class='text-sm text-gray-600'>Nos alegra verte de nuevo</p>";
            echo "</div>";
            echo "</div>";
            echo "</div>";

            echo "<div class='container mx-auto px-4 py-8 mt-20'>";


            echo "<div class='grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8'>";

            echo "<div class='bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500'>";
            echo "<div class='flex items-center'>";
            echo "<div class='p-3 rounded-full bg-blue-100 bg-opacity-75'>";
            echo "<svg class='h-8 w-8 text-blue-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'></path></svg></div>";
            echo "<div class='ml-4'><h2 class='text-gray-600 text-sm'>Últimos Pedidos</h2>";
            echo "<p class='text-2xl font-semibold text-gray-800'>" . count($ultimosPedidos) . "</p></div></div></div>";

            if ($productoFavorito) {
                echo "<div class='bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500'>";
                echo "<div class='flex items-center'>";
                echo "<div class='p-3 rounded-full bg-green-100 bg-opacity-75'>";
                echo "<svg class='h-8 w-8 text-green-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
                echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'></path></svg></div>";
                echo "<div class='ml-4'><h2 class='text-gray-600 text-sm'>Producto Favorito</h2>";
                echo "<p class='text-2xl font-semibold text-gray-800'>{$productoFavorito->nombre}</p>";
                echo "<p class='text-sm text-gray-500'>{$productoFavorito->cantidad_total} unidades</p></div></div></div>";
            }

            $total = array_reduce($ultimosPedidos, function ($carry, $pedido) {
                return $carry + ($pedido->precio * $pedido->cantidad);
            }, 0);

            echo "<div class='bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500'>";
            echo "<div class='flex items-center'>";
            echo "<div class='p-3 rounded-full bg-purple-100 bg-opacity-75'>";
            echo "<svg class='h-8 w-8 text-purple-600' fill='none' stroke='currentColor' viewBox='0 0 24 24'>";
            echo "<path stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'></path></svg></div>";
            echo "<div class='ml-4'><h2 class='text-gray-600 text-sm'>Total Gastado</h2>";
            echo "<p class='text-2xl font-semibold text-gray-800'>" . number_format($total, 2) . "€</p></div></div></div></div>";

            echo "<div class='bg-white rounded-lg shadow-md overflow-hidden'>";
            echo "<div class='px-6 py-4 border-b border-gray-200 bg-gray-50'>";
            echo "<h3 class='text-xl font-semibold text-gray-800'>Últimos Pedidos</h3></div>";
            echo "<div class='overflow-x-auto'><table class='min-w-full divide-y divide-gray-200'>";
            echo "<thead class='bg-gray-50'><tr>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Producto</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Fecha</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Cantidad</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Total</th>";
            echo "<th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Estado</th>";
            echo "</tr></thead><tbody class='bg-white divide-y divide-gray-200'>";

            foreach ($ultimosPedidos as $pedido) {
                $total = $pedido->precio * $pedido->cantidad;
                $fecha = new DateTime($pedido->fecha);
                echo "<tr class='hover:bg-gray-50'>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>{$pedido->nombre_producto}</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{$fecha->format('d/m/Y')}</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>{$pedido->cantidad}</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap text-sm text-gray-900'>" . number_format($total, 2) . "€</td>";
                echo "<td class='px-6 py-4 whitespace-nowrap'><span class='px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800'>Completado</span></td>";
                echo "</tr>";
            }

            echo "</tbody></table></div></div></div>";
        }
        ?>
    </div>

    <!-- Modal de Login -->
    <div id="loginModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 <?php echo (isset($_SESSION['login_error'])) ? '' : 'hidden'; ?> overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 w-full max-w-sm">
            <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-lg p-6 border border-white/20">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Iniciar Sesión</h2>
                    <button onclick="document.getElementById('loginModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <?php if (isset($_SESSION['login_error'])): ?>
                    <div class="bg-red-100/80 backdrop-blur-sm border border-red-400 text-red-700 px-3 py-2 rounded mb-4 text-sm">
                        <?php
                        echo $_SESSION['login_error'];
                        unset($_SESSION['login_error']);
                        ?>
                    </div>
                <?php endif; ?>

                <form action="login.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Usuario</label>
                        <input type="text" id="username" name="username"
                            class="w-full px-3 py-2 bg-white/50 backdrop-blur-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Contraseña</label>
                        <input type="password" id="password" name="password"
                            class="w-full px-3 py-2 bg-white/50 backdrop-blur-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600/80 backdrop-blur-sm hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 hover:shadow-lg">
                        Iniciar Sesión
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-800">
                        ¿No tienes cuenta?
                        <button onclick="openRegisterModal()" class="text-blue-700 hover:text-blue-900 font-medium">
                            Regístrate aquí
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!--Modal Registro-->
    <div id="registerModal" class="fixed inset-0 backdrop-blur-sm bg-black/30 <?php echo (isset($_SESSION['register_error'])) ? '' : 'hidden'; ?> overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 w-full max-w-sm">
            <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-lg p-6 border border-white/20">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800">Registro</h2>
                    <button onclick="document.getElementById('registerModal').classList.add('hidden')" class="text-gray-600 hover:text-gray-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <?php if (isset($_SESSION['register_error'])): ?>
                    <div class="bg-red-100/80 backdrop-blur-sm border border-red-400 text-red-700 px-3 py-2 rounded mb-4 text-sm">
                        <?php
                        echo $_SESSION['register_error'];
                        unset($_SESSION['register_error']);
                        ?>
                    </div>
                <?php endif; ?>

                <form action="registro.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Usuario</label>
                        <input type="text" id="username" name="username" class="w-full px-3 py-2 bg-white/50 backdrop-blur-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Email</label>
                        <input type="email" id="email" name="email" class="w-full px-3 py-2 bg-white/50 backdrop-blur-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Contraseña</label>
                        <input type="password" id="password" name="password" class="w-full px-3 py-2 bg-white/50 backdrop-blur-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-800 mb-1">Confirmar Contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="w-full px-3 py-2 bg-white/50 backdrop-blur-sm border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors">
                    </div>

                    <button type="submit" class="w-full bg-blue-600/80 backdrop-blur-sm hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 hover:shadow-lg">
                        Registrarse
                    </button>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-sm text-gray-800">
                        ¿Ya tienes cuenta?
                        <button onclick="closeRegisterModal(), openLoginModal()" class="text-blue-700 hover:text-blue-900 font-medium">
                            Inicia sesión aquí
                        </button>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openLoginModal() {
            document.getElementById('loginModal').classList.remove('hidden');
        }

        function closeLoginModal() {
            document.getElementById('loginModal').classList.add('hidden');
        }

        function openRegisterModal() {
            document.getElementById('registerModal').classList.remove('hidden');
        }

        function closeRegisterModal() {
            document.getElementById('registerModal').classList.add('hidden');
        }
    </script>
</body>

</html>