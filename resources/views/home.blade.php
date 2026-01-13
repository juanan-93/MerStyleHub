<h1>Estoy dentro </h1>

<!-- Authentication -->
<form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit">Cerrar sesión</button>
</form>