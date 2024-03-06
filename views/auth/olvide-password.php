<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Reestable tu password escribiendo tu e-mail a continuación</p>

<?php include_once __DIR__ . "/../templates/alertas.php" ?>

<form class="formulario" method="POST" action="/olvide">
    <div class="campo">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" placeholder="Tu Email">
    </div>

    <div class="bto">
        <input type="submit" class="boton" value="Enviar Instruciones">
    </div>

</form>

<div class="acciones">
        <a href="/">¿Ya tienes una cuenta? Inicia Sesión!</a>
        <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crea una!</a>
    </div>