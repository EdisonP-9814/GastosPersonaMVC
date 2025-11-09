<!-- Sidebar -->
<aside class='Contvtc Block_aside'>
  <div class='Contvtc' style='width: 260px;'>
    <form class='Contvtc Frm260' action="<?=base_url?>usuario/login" method="POST">
      <div class='Conthcl Frm260'>
        <label for="cedula">Cédula</label>
        <input type="text" name='cedula' placeholder='Ingrese su cédula'>
      </div>
      <div class='Conthcl Frm260'>
        <label for="clave">Contraseña</label>
        <input type="password" name='clave' placeholder='Ingrese su contraseña'>
      </div>

      <input class="Bt4b" type="submit" value="Ingresar">
      <br><h7>¿No tienes cuenta?</h7>
      <a class="Hovsbr" href="<?=base_url?>usuario/registro">
        <h5>Regístrate</h5>
      </a>
    </form>
  </div>
</aside>
<!-- End Sidebar -->
