<!-- Esta vista es el banner de bienvenida del administrador, tambien aqui se abren las etiquetas donde contendrán los archivos html con la etiqueta <main> -->
<header class="adminMenu-header">
    <a class="adminMenu-header__link" href="<?= base_url;?>home/"><img class="menu-header__img" src="<?= base_url;?>assets/img/logo_interpc_letras_blancas-1.png"/></a>
    <h2 class="adminMenu-header__admin-welcome">Bienvenid@, <?=$_SESSION["identity"]["Alias"]?></h2>
</header>


<button class="navbar_mobile-btn" type="button" id="mobileBtn">
    <span class="mobile-btn__line"></span>
    <span class="mobile-btn__line"></span>
    <span class="mobile-btn__line"></span>
</button>

<div class="adminViews-wrapper">
<div class="mains-wrapper" id="mainsWrapper">