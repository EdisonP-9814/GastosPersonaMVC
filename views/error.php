<?php
  $er_reg = $_SESSION['msgerror'] ?? "Error desconocido";
?>
<div class='Contvcc Frm1'>
  <div class='Contvcc FrmEr Smbr2'>
    <div class='Contvtc' style="margin-top: 30px;">
      <img class="ImgLog2" src="<?=base_url?>assets/images/logo.png">
      <h6><?=$er_reg?></h6>
    </div><br>
    <div>
      <img class="ImgMdl" src="<?=base_url?>assets/images/warning.png" alt="">
    </div><br>
    <div>
      <input type="button" class="Btc" value="Regresar"
        onclick="location.href='<?=base_url?>'">
    </div><br>
  </div>
</div>