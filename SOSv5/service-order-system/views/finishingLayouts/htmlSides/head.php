<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" type="text/css" href="<?=base_url;?>assets/css/reset.css"/>
        <link rel="stylesheet" type="text/css" href="<?=base_url;?>assets/fonts/Outfit/outfit.css"/>
        <link rel="stylesheet" type="text/css" href="<?=base_url;?>assets/css/finishingStyles.css"/>
        <link rel="stylesheet" type="text/css" href="<?=base_url;?>assets/css/finishingResponsive.css"/>
        <?php if(!empty($_SESSION["dataSelectionForSigns"])): ?>
            <!-- Este script inicializa un objeto solo si la sesión  "binnacleSelection" no está vacía, este objeto 
            se usa en el archivo finishing.js para dar forma al nombre del archivo de las imagenes de las firmas (tanto 
            de clientes como de técnicos) -->
            <script>window.serverData = <?=json_encode($_SESSION["dataSelectionForSigns"]);?></script>
        <?php endif; ?>
        <script>
            //URL donde se aloja la aplicación web, los archivo JS necesita esta constante para 
            //completar los strings url de las peticiones http ascincronas (fetch) y la creación de 
            //elementos html que necesiten la propiedad "href" (src o action) para funcionar, la variable 
            //de entorno que docker importa desde el comando $env:YOUR_LOCALHOST = (Get-NetRoute -DestinationPrefix 0.0.0.0/0 | Sort-Object RouteMetric | Select-Object -First 1 | Get-NetIPAddress -AddressFamily IPv4).IPAddress
            //docker-compose up -d se define en el param de php base_url y lo que imprime gracias a la etiqueta de php 
            //se contiene en la constante JS de BASE_URL
            const BASE_URL = "<?=base_url;?>";
        </script> 
        <script src="<?=base_url;?>assets/js/finishing.js"></script>
        <title>Seguimiento de bitacora</title>
    </head>
    
    <body>