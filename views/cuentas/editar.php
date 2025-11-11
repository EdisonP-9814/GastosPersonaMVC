<div class='Contvcc Frm1' style='margin-top: 20px;'>
  
  <form action="<?=base_url?>Cuenta/update&id=<?=$cuenta_data['id_cuenta']?>" class='Contvcc Frm2 Brd15' method="post">
    <h4>Editar Cuenta</h4>

    <label>Nombre de la Cuenta:</label>
    <input type="text" name="nombre" value="<?=htmlspecialchars($cuenta_data['nombre_cuenta'])?>" required>

    <label>Tipo de Cuenta:</label>
    <select name="tipo" required>
        <option value="EFECTIVO" <?= ($cuenta_data['tipo_cuenta'] == 'EFECTIVO') ? 'selected' : '' ?>>EFECTIVO</option>
        <option value="BANCO" <?= ($cuenta_data['tipo_cuenta'] == 'BANCO') ? 'selected' : '' ?>>BANCO</option>
        <option value="TARJETA_CREDITO" <?= ($cuenta_data['tipo_cuenta'] == 'TARJETA_CREDITO') ? 'selected' : '' ?>>TARJETA DE CRÉDITO</option>
        <option value="TARJETA_DEBITO" <?= ($cuenta_data['tipo_cuenta'] == 'TARJETA_DEBITO') ? 'selected' : '' ?>>TARJETA DE DÉBITO</option>
        <option value="DIGITAL" <?= ($cuenta_data['tipo_cuenta'] == 'DIGITAL') ? 'selected' : '' ?>>DIGITAL (Ej: PayPal)</option>
    </select>

    <label>Saldo Inicial (No editable):</label>
    <input type="text" name="saldo" 
           value="$<?=number_format((float)$cuenta_data['saldo_inicial_cuenta'], 2)?>" disabled
           style="background-color: #eee;"> <div class='Conthcj' style='width: 75%; height: 60px;'>
      <input type="submit" value="Actualizar" class="Bt4b">
      <input type="button" value="Cancelar" class="Bt4r"
        onclick="location.href='<?=base_url?>Cuenta/index'">
    </div>
  </form>
</div>