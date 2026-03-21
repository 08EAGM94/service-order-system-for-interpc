<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url; ?>assets/css/reset.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url; ?>assets/fonts/Outfit/outfit.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url; ?>assets/fonts/fontawesome-free-6.7.2-web/css/all.min.css"/>
        <style>
            :root{
                /*Variables*/
                --header-text: #FFFFFF;
                --gradient-white: #ebf8e1;
                --color-primary: #04004D;
                --color-secondary: #7497ED;
                --thead-color: #0915BD;
                --even-trow-color: #B5C7FF;
                --background-color: #F5F5F5;
                --pop-up-window-background: #212121;
                --error-color: #C41414;
                --pdf-button-color: #F5B427;
                --edit-button-color: #008736;
                --background-url: url("<?= base_url;?>assets/img/6075275.jpg");
            }
        </style>
        <link rel="stylesheet" type="text/css" href="<?= base_url; ?>assets/css/generalStyles.css"/>
        <link rel="stylesheet" type="text/css" href="<?= base_url; ?>assets/css/responsive.css"/>
        <?php if(empty($_SESSION["identity"])): ?>
        <script>
            //URL donde se aloja la aplicación web, los archivo JS necesita esta constante para 
            //completar los strings url de las peticiones http ascincronas (fetch) y la creación de 
            //elementos html que necesiten la propiedad "href" (src o action) para funcionar, la variable 
            //de entorno que docker importa desde el comando $env:YOUR_LOCALHOST = (Get-NetRoute -DestinationPrefix 0.0.0.0/0 | Sort-Object RouteMetric | Select-Object -First 1 | Get-NetIPAddress -AddressFamily IPv4).IPAddress
            //docker-compose up -d se define en el param de php base_url y lo que imprime gracias a la etiqueta de php 
            //se contiene en la constante JS de BASE_URL
            const BASE_URL = "<?=base_url;?>";
        </script> 
        <script defer src="<?= base_url; ?>assets/js/particles.min.js"></script>
        <script defer src="<?= base_url; ?>assets/js/app.js"></script>
        <script src="<?= base_url; ?>assets/js/login.js"></script>
        <?php else: ?>
        <link rel="stylesheet" type="text/css" href="<?= base_url; ?>assets/css/select2.min.css"/>
        <script>const BASE_URL = "<?=base_url;?>";</script>
        <script defer src="<?= base_url; ?>assets/js/jquery-3.7.1.min.js"></script>
        <script defer src="<?= base_url; ?>assets/js/select2.min.js"></script>
        <script src="<?= base_url; ?>assets/js/user.home.js"></script>
        <?php endif; ?>
        <title><?=(empty($_SESSION["identity"])) ? "S.O.S Login" : "S.O.S"?></title>
    </head>
    
    <body>