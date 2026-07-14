<!-- Este html es la vista de mensaje de error del proyecto-->
<?php if(empty($_SESSION["identity"])): ?>
<div class="error-box"><h1>La página que buscas no existe</h1></div>
<div class="particles" id="particles-js"></div>
<?php else: ?>
<div class="error-box error-color"><h1>La página que buscas no existe</h1></div>
<?php endif;