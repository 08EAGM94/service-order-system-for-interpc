<?php

use org\bovigo\vfs\vfsStream;

beforeEach(function(){
    $this->container = testContainerFactory();
    $this->base_url = 'http://localhost:8081/SOSv6/service-order-system/';
    $this->default_homeController = 'UIController';
});

afterEach(function(){
    $_SESSION = [];
    $_POST = [];
    $_GET = [];
    $_SERVER = [];
    $_FILES = [];
    clearstatcache();
});

test('prueba método sessionLifetime', function(){
    
    $_SESSION['LAST_ACTIVITY'] = 1783600000;

    $result = mockSessionLifetime();

    expect(sizeof($_SESSION))->toBeLessThanOrEqual(0);
    expect($result)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método showError', function(){

    $result = mockShowError($this->container);

    expect(get_class($result))->toBe("ErrorController");
    expect(method_exists($result, 'index'))->toBeTrue();
});

test('prueba método defaultHomePage', function(){

    $result = mockDefaultHomePage($this->container, $this->default_homeController);

    expect(get_class($result))->toBe("UIController");
    expect(method_exists($result, 'index'))->toBeTrue();
});

test('prueba método generateWelcomeBanner, caso "user"', function(){

    $_SESSION["identity"] = true;
    $result = mockGenerateWelcomeBanner();

    expect($result)->toBe('../views/userLayouts/menuSides/welcomeBanner.php');
});

test('prueba método generateWelcomeBanner, caso "admin"', function(){

    $_SESSION["identity"] = true;
    $_SESSION["isAdmin"] = true;
    $result = mockGenerateWelcomeBanner();

    expect($result)->toBe('../views/adminLayouts/menuSides/welcomeBanner.php');
});

test('prueba método setAsideWithVerify', function(){

    $_SESSION["isAdmin"] = true;
    $result = mockSetAsideWithVerify();

    expect($result)->toBe('../views/adminLayouts/menuSides/aside.php');
});

test('prueba método unsetFlagsSessions', function(){

    $_SESSION["success"] = true;
    $_SESSION["errors"] = true;
    $_SESSION["exceptions"] = true;

    Utils::unsetFlagsSessions();

    expect(sizeof($_SESSION))->toBeLessThanOrEqual(0);
});

test('prueba método unsetJsonDecodedSession', function(){

    $_SESSION["jsondecoded"] = true;
    
    Utils::unsetJsonDecodedSession();

    expect(sizeof($_SESSION))->toBeLessThanOrEqual(0);
});

test('prueba método unsetBinnFilterSession', function(){

    $_SESSION["binnFilterSession"] = true;
    
    Utils::unsetBinnFilterSession();

    expect(sizeof($_SESSION))->toBeLessThanOrEqual(0);
});

test('prueba método unsetSearchFormsIdSession', function(){

    $_SESSION["idSession"] = true;
    
    Utils::unsetSearchFormsIdSession();

    expect(sizeof($_SESSION))->toBeLessThanOrEqual(0);
});

test('prueba método unsetFormSessions', function(){

    $_SESSION["formSession"] = true;
    
    Utils::unsetFormSessions();

    expect(sizeof($_SESSION))->toBeLessThanOrEqual(0);
});

test('prueba método setDataSelectionForSigns, caso seguimiento de bitácora', function(){

    $_SESSION["identity"]["Id"] = "1";
    $_POST = [
        "binnId" => "15",
        "userId" => "1",
        "clientName" => "María Anaí",
        "clientEntName" => "Papelería Ruíz García",
        "userName" => "Héctor Raúl",
        "userSurname" => "De León Martínez",
    ];
    
    mockSetDataSelectionForSigns();

    expect($_SESSION["formSession"]["dataSelectionForSigns"])->toBe([
        "binnId"        => "15",
        "clientName"    => "Mar0a Ana0",
        "altClientName" => "Papeler0a Ru0z Garc0a",
        "userId"        => "1",
        "userName"      => "H0ctor Ra0l",
        "userSurname"   => "De Le0n Mart0nez"
    ]);
});

test('prueba método setDataSelectionForSigns, caso edición de firma', function(){

    $_POST = [
        "userId" => "1",
        "userName" => "Héctor Raúl",
        "userSurname" => "De León Martínez",
        "oldTechSign" => "old_tech_sign.png",
    ];
    
    mockSetDataSelectionForSigns();

    expect($_SESSION["formSession"]["dataSelectionForSigns"])->toBe([
        "userId"        => "1",
        "userName"      => "H0ctor Ra0l",
        "userSurname"   => "De Le0n Mart0nez",
        "oldTechSign"   => "old_tech_sign.png"
    ]);
});

test('prueba método setDataSelectionForSigns, caso seguimiento de bitácora (id bitácora invalida)', function(){

    $_SESSION["identity"]["Id"] = "1";
    $_POST = [
        "binnId" => "qwerty",
        "userId" => "1",
        "clientName" => "María Anaí",
        "clientEntName" => "Papelería Ruíz García",
        "userName" => "Héctor Raúl",
        "userSurname" => "De León Martínez",
    ];
    
    $value = mockSetDataSelectionForSigns();

    expect($value)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método setDataSelectionForSigns, caso edición de firma (id usuario invalido)', function(){

    $_POST = [
        "userId" => "qwerty",
        "userName" => "Héctor Raúl",
        "userSurname" => "De León Martínez",
        "oldTechSign" => "old_tech_sign.png",
    ];
    
    $value = mockSetDataSelectionForSigns();

    expect($value)->toBe("Location: ".$this->base_url."home/");
});

test('prueba método saveSignaturesFiles, caso "newTechSign"', function () {
    
    $root = vfsStream::setup("uploads/firmas");
    $virtual_png = $root->url() .'/'.'test_sign.png';
    $some_sign = file_get_contents('/var/www/html/SOSv6/service-order-system/finishing/uploads/firmas/userid_2_Edgar_Allan_Gutierrez_Morales_Sign.png');
    file_put_contents($virtual_png, $some_sign);

    $tmp_file = tempnam(sys_get_temp_dir(), 'test_');
    file_put_contents($tmp_file, $some_sign);

    $_FILES["newTechSign"] = [
        "tmp_name" => $tmp_file,
        "name" => "new_tech_sign.png"
    ];

    $_SESSION["formSession"]["dataSelectionForSigns"]["oldTechSign"] = 'test_sign.png';

    $value = mockSaveSignaturesFiles($root->url());

    expect($_SESSION["formSession"]["techSignature"])->toBe('new_tech_sign.png');
    expect($root->hasChild('new_tech_sign.png'))->toBeTrue();
    expect($value)->toBe("Location: ".$this->base_url."finishing/?controller=followupform&action=techsign");
});

test('prueba método saveSignaturesFiles, caso "techSign"', function () {
    
    $root = vfsStream::setup("uploads/firmas");
    $some_sign = file_get_contents('/var/www/html/SOSv6/service-order-system/finishing/uploads/firmas/userid_2_Edgar_Allan_Gutierrez_Morales_Sign.png');

    $tmp_file = tempnam(sys_get_temp_dir(), 'test_');
    file_put_contents($tmp_file, $some_sign);

    $_FILES["techSign"] = [
        "tmp_name" => $tmp_file,
        "name" => "tech_sign.png"
    ];

    $value = mockSaveSignaturesFiles($root->url());

    expect($_SESSION["formSession"]["techSignature"])->toBe('tech_sign.png');
    expect($root->hasChild('tech_sign.png'))->toBeTrue();
    expect($value)->toBe("Location: ".$this->base_url."finishing/?controller=followupform&action=techsign");
});

test('prueba método saveSignaturesFiles, caso "cliSign"', function () {
    
    $root = vfsStream::setup("uploads/firmas");
    $some_sign = file_get_contents('/var/www/html/SOSv6/service-order-system/finishing/uploads/firmas/userid_2_Edgar_Allan_Gutierrez_Morales_Sign.png');

    $tmp_file = tempnam(sys_get_temp_dir(), 'test_');
    file_put_contents($tmp_file, $some_sign);

    $_FILES["cliSign"] = [
        "tmp_name" => $tmp_file,
        "name" => "cli_sign.png"
    ];

    $_SERVER['HTTP_X_REQUESTED_WITH'] = 'xmlhttprequest';

    $value = mockSaveSignaturesFiles($root->url());

    expect($_SESSION["formSession"]["clientSignature"])->toBe('cli_sign.png');
    expect($root->hasChild('cli_sign.png'))->toBeTrue();
    expect($value)->toBe("Firma del cliente guardado con éxito");
});

test('prueba método setAdminWithVerify', function(){
    
    $_SESSION["identity"]["Privilegio"] = "Admin";

    Utils::setAdminWithVerify();

    expect(isset($_SESSION["isAdmin"]))->toBeTrue();
});

test('prueba método setIdSession, caso editSign', function(){
    
    $_SESSION["isAdmin"] = true;
    $_GET["homeAction"] = "editSign";
    $_POST["usuarios"] = "1";

    mockSetIdSession();
    expect($_SESSION["idSession"]["userSign_userId"] === $_POST["usuarios"])->toBeTrue();
    expect($_SESSION["header"])->toBe("../home/?homeController=user&homeAction=editSign&usuarios=1");
});

test('prueba método setIdSession, caso userNewPassword', function(){
    
    $_SESSION["isAdmin"] = true;
    $_GET["homeAction"] = "userNewPassword";
    $_POST["usuarios"] = "1";

    mockSetIdSession();

    expect($_SESSION["idSession"]["userNewPwd_userId"] === $_POST["usuarios"])->toBeTrue();
    expect($_SESSION["header"])->toBe("../home/?homeController=user&homeAction=userNewPassword&usuarios=1");
});

test('prueba método setIdSession, caso index de EnterpriseController', function(){
    
    $_SESSION["isAdmin"] = true;
    $_GET = [
        "homeAction" => "index",
        "homeController" => "enterprise"
    ];
    $_POST["empresas"] = "1";

    mockSetIdSession();

    expect($_SESSION["idSession"]["enterpriseEdit_enterId"] === $_POST["empresas"])->toBeTrue();
    expect($_SESSION["header"])->toBe("../home/?homeController=enterprise&homeAction=index&empresas=1");
});

test('prueba método setIdSession, caso editDevice', function(){
    
    $_SESSION["isAdmin"] = true;
    $_GET["homeAction"] = "editDevice";
    $_POST["empresas"] = "1";

    mockSetIdSession();

    expect($_SESSION["idSession"]["devicesEdit_enterId"] === $_POST["empresas"])->toBeTrue();
    expect($_SESSION["header"])->toBe("../home/?homeController=device&homeAction=editDevice&empresas=1");
});

test('prueba método setIdSession, caso devicesReport', function(){
    
    $_SESSION["isAdmin"] = true;
    $_GET["homeAction"] = "devicesReport";
    $_POST["empresas"] = "1";

    mockSetIdSession();

    expect($_SESSION["idSession"]["devicesReport_enterId"] === $_POST["empresas"])->toBeTrue();
    expect($_SESSION["header"])->toBe("../home/?homeController=device&homeAction=devicesReport&empresas=1");
});

test('prueba método setBinnFilterSessions, caso satisfactorio', function(){
    
    $_POST = [
        "empresaId" => "12",
        "contactoId" => "23",
        "servicioOEquipo" => "Equipo_id",
        "equipoId" => "30",
        "estatus" => "falta confirmar",
        "startedOrEnded" => "Inicio",
        "leftDay" => "2026-07-08",
        "rightDay" => "2026-07-20",
        "visible" => "ENABLED",
    ];

    mockSetBinnFilterSessions($this->container->make('binnDTO'));

    expect($_SESSION["binnFilterSession"])->toBe([
        "Empresa_id" => "12",
        "Contacto_id" => "23",
        "IsServiceOrDevice" => "Equipo_id",
        "Equipo_id" => "30",
        "Estatus" => "falta confirmar",
        "StartedOrEnded" => "Inicio",
        "LeftDay" => "2026-07-08",
        "RightDay" => "2026-07-20",
        "Visible" => "ENABLED",
    ]);
    expect(isset($_SESSION["header"]))->toBeTrue();
});

test('prueba método setBinnFilterSessions, caso campos invalidos', function(){
    
    $_POST = [
        "empresaId" => "qwert",
        "contactoId" => "qwerty",
        "servicioOEquipo" => "<script></script>",
        "equipoId" => "qwerty",
        "estatus" => "<script></script>",
        "startedOrEnded" => "<script></script>",
        "leftDay" => "2026/07/08",
        "rightDay" => "2026/07/20",
        "visible" => "<script></script>",
    ];

    try{
        mockSetBinnFilterSessions($this->container->make('binnDTO'));
    }catch(UnauthorizedDataException $ex){
        $_SESSION["exceptions"]["unauthEx"] = $ex->getMessage();
    }
    
    expect(isset($_SESSION["exceptions"]["unauthEx"]))->toBeTrue();
});

test('prueba método setIVAIfAmountIsNotNull', function(){
    
    $binn_arr = [
        "Monto" => "2568.50"
    ];

    $value = Utils::setIVAIfAmountIsNotNull($binn_arr);
    
    expect($value)->toBe('2979.46');
});

test('prueba método isAuthorizedBinnacle, caso estatus finalizado', function(){
    
    $binn_arr = [
        "Estatus" => "finalizado"
    ];

    try{
        Utils::isAuthorizedBinnacle($binn_arr);
    }catch(UnauthorizedDataException $ex){
        $_SESSION["exceptions"]["unauthEx"] = $ex->getMessage();
    }
    
    expect(isset($_SESSION["exceptions"]["unauthEx"]))->toBeTrue();
});

test('prueba método isAuthorizedBinnacle, caso estatus cancelado', function(){
    
    $binn_arr = [
        "Estatus" => "cancelado"
    ];

    try{
        Utils::isAuthorizedBinnacle($binn_arr);
    }catch(UnauthorizedDataException $ex){
        $_SESSION["exceptions"]["unauthEx"] = $ex->getMessage();
    }
    
    expect(isset($_SESSION["exceptions"]["unauthEx"]))->toBeTrue();
});