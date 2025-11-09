<div class='Contvcc Frm1' style='margin-top: 20px;'>
  
  <form action="<?=base_url?>Cuenta/save" class='Contvcc Frm2 Brd15' method="post">
    <h4>Crear Nueva Cuenta</h4>

    <input type="text" name="nombre" placeholder="Nombre (Ej: Ahorros Banco)" required>

    <select name="tipo" required style="width: 250px; height: 35px; margin-bottom: 7px;">
        <option value="" disabled selected>Selecciona el Tipo de Cuenta</option>
        <option value="EFECTIVO">EFECTIVO</option>
        <option value="BANCO">BANCO</option>
        <option value="TARJETA_CREDITO">TARJETA DE CRÉDITO</option>
        <option value="TARJETA_DEBITO">TARJETA DE DÉBITO</option>
        <option value="DIGITAL">DIGITAL (Ej: PayPal)</option>
    </select>

    <input type="number" name="saldo" placeholder="Saldo Inicial" step="0.01" value="0.00" required>

    <div class='Conthcj' style='width: 75%; height: 60px;'>
      <input type="submit" value="Guardar Cuenta" class="Bt4b">
      <input type="button" value="Cancelar" class="Bt4r"
        onclick="location.href='<?=base_url?>Cuenta/index'">
    </div>
  </form>
</div>
</body>