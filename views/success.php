<?php
  $er_reg = $_SESSION['msgsuccess'] ?? "Procedimiento Exitoso!!";
?>
<div class='Contvcc Frm1'>
  <div class='Contvcc FrmEr Smbr2'>
    <div class='Contvtc' style="margin-top: 30px;">
      <img class="ImgLog2" src="<?=base_url?>assets/images/logo.png" alt="Logo">
      <h6><?=$er_reg?></h6>
    </div><br>
    <div>
      <img class="ImgMdl" src="<?=base_url?>assets/images/success.png" alt="Éxito">
    </div><br>
    <div>
      <input type="button" class="Btc" value="Regresar"
        onclick="location.href='<?=base_url?>'">
    </div><br>
  </div>
</div>
