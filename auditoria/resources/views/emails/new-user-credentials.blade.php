<h1>¡Hola, {{ $user->name }}!</h1>

<p>
    Te damos la bienvenida a nuestro sistema. Tu cuenta ha sido creada.
</p>

<p>Tus credenciales de acceso son:</p>
<ul>
    <li><strong>Usuario:</strong> {{ $user->email }}</li>
    <li><strong>Contraseña temporal:</strong> {{ $password }}</li>
</ul>

<p>
    Por favor, inicia sesión y cambia tu contraseña.
</p>
