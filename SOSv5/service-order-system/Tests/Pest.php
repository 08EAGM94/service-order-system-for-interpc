<?php

use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind different classes or traits.
|
*/

pest()->extend(TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

expect()->extend('toBeInDatabase', function($pdo, $table){
    
    $db = $pdo->getConnection();
    $data = $this->value;
    $where = implode(' AND ', array_map(function($key){return "$key = :$key";}, array_keys($data)));
    $stmt = $db->prepare('SELECT COUNT(*) FROM '.$table.' WHERE '.$where);
    $stmt->execute($data);
    $count = $stmt->fetchColumn();
    $db = null;

    return expect($count)->toBeGreaterThan(0, "No se encontró el registro en la tabla '$table'.");
});

expect()->extend('toBeUnknownInDatabase', function($pdo, $table){
    
    $db = $pdo->getConnection();
    $data = $this->value;
    $where = implode(' AND ', array_map(function($key){return "$key = :$key";}, array_keys($data)));
    $stmt = $db->prepare('SELECT COUNT(*) FROM '.$table.' WHERE '.$where);
    $stmt->execute($data);
    $count = $stmt->fetchColumn();
    $db = null;

    return expect($count)->toBeLessThanOrEqual(0, "se encontró el registro en la tabla '$table'.");
});
/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

//---------------------------TEST UTILITIES---------------------------------
function testContainerFactory()
{

    $builder = new DI\ContainerBuilder();

    $builder->addDefinitions([
        'ErrorController' => DI\factory(function(){return new ErrorController();}),
        'UIController' => DI\factory(function(){return new UIController();}),

        'binnDTO' => DI\factory(function(){return new BitacorasDTO();}),
        'contDTO' => DI\factory(function(){return new ContactosDTO();}),
        'enterDTO' => DI\factory(function(){return new EmpresasDTO();}),
        'dceDTO' => DI\factory(function(){return new EquiposDTO();}),
        'typDTO' => DI\factory(function(){return new TiposDTO();}),
        'usrDTO' => DI\factory(function(){return new UsuariosDTO();}),

        'SOSTestDatabase' => DI\factory(function(){return new DatabaseMssqlTest();}),


        'binnService' => DI\factory(function(){
            return new CommonService(new BinnacleRepository(new BinnacleQueries(new DatabaseMssqlTest(), new Zebra_Pagination()), 
                new BinnacleToModelMapper()), new BinnacleDTOToEntityMapper(), new BinnacleToModelMapper());
        }),
        'enterService' => DI\factory(function(){
            return new CommonService(new EnterpriseRepository(new EnterpriseQueries(new DatabaseMssqlTest()), 
                new EnterpriseToModelMapper(), new ContactToModelMapper()), new EnterpriseDTOToEntityMapper(), 
                new EnterpriseToModelMapper(), new ContactDTOToEntityMapper());
        }),
        'typService' => DI\factory(function(){
            return new CommonService(new TypeRepository(new TypeQueries(new DatabaseMssqlTest()), new TypeToModelMapper()), 
                new TypeDTOToEntityMapper(), new TypeToModelMapper());
        }),
        'usrService' => DI\factory(function(){
            return new CommonService(new UserRepository(new UserQueries(new DatabaseMssqlTest()), new UserToModelMapper()), 
                new UserDTOToEntityMapper(), new UserToModelMapper());
        }),
        'contService' => DI\factory(function(){
            return new EnterpriseChildrenService(new ContactRepository(new ContactQueries(new DatabaseMssqlTest()), 
            new ContactToModelMapper()), new ContactDTOToEntityMapper(), new ContactToModelMapper());
        }),
        'dceService' => DI\factory(function(){
            return new EnterpriseChildrenService(new DeviceRepository(new DeviceQueries(new DatabaseMssqlTest()), 
            new DeviceToModelMapper()), new DeviceDTOToEntityMapper(), new DeviceToModelMapper());
        }),


        'contSelectSrv' => DI\factory(function(){
            return new SelectService(new ContactRepository(new ContactQueries(new DatabaseMssqlTest()), 
            new ContactToModelMapper()));
        }),
        'enterSelectSrv' => DI\factory(function(){
            return new SelectService(new EnterpriseRepository(new EnterpriseQueries(new DatabaseMssqlTest()), 
                new EnterpriseToModelMapper(), new ContactToModelMapper()));
        }),
        'typSelectSrv' => DI\factory(function(){
            return new SelectService(new TypeRepository(new TypeQueries(new DatabaseMssqlTest()), new TypeToModelMapper()));
        }),


        'usrSignService' => DI\factory(function(){
            return new SignatureService(new UserRepository(new UserQueries(new DatabaseMssqlTest()), new UserToModelMapper()), 
                new UserDTOToEntityMapper());
        }),
        'usrParticularSrv' => DI\factory(function(){
            return new UserService(new UserRepository(new UserQueries(new DatabaseMssqlTest()), new UserToModelMapper()), 
                new UserDTOToEntityMapper());
        }),
        'binnParticularSrv' => DI\factory(function(){
            return new BinnacleService(new BinnacleRepository(new BinnacleQueries(new DatabaseMssqlTest(), new Zebra_Pagination()), 
                new BinnacleToModelMapper()), new BinnacleDTOToEntityMapper());
        })
    ]);

    return $builder->build();
}

function mockUserDTO($dto, $usrType){

    $dto->nombre = "Edgar Allan";
    $dto->apellidos = "Gutierrez Morales";
    $dto->alias = "theGEAr94";
    $dto->contrasena = "elRojoQueNoEsRojo";
    $dto->privilegio = ($usrType === "admin") ? "admin" : "user";
    $dto->firma = "test.png";

    return $dto;
}

function setIdentitySession($dto, $usrParticularSrv){
    
    $dto->alias = "theGEAr94";
    $dto->contrasena = "elRojoQueNoEsRojo";
    $user = $usrParticularSrv->login($dto);

    if($user["Privilegio"] === "admin")
        $_SESSION["isAdmin"] = true;
    
    return $user;
}

function mockUsersDTO($dto, $dto2, $dto3){
    
    $dto->nombre = "Edgar Allan";
    $dto->apellidos = "Gutierrez Morales";
    $dto->alias = "theGEAr94";
    $dto->contrasena = "elRojoQueNoEsRojo";
    $dto->privilegio = "admin";
    $dto->firma = "test.png";

    $dto2->nombre = "Elena Aurora";
    $dto2->apellidos = "Rodriguez";
    $dto2->alias = "nena86";
    $dto2->contrasena = "mi_nena";
    $dto2->privilegio = "user";
    $dto2->firma = "test.png";

    $dto3->nombre = "Héctor Ignacio";
    $dto3->apellidos = "Lopéz Rentería";
    $dto3->alias = "hector96";
    $dto3->contrasena = "123456789";
    $dto3->privilegio = "user";
    $dto3->firma = "test.png";

    return [
        $dto,
        $dto2,
        $dto3
    ];
}

function mockTypesDTO($dto, $dto2, $dto3){

    $dto->tipo = "impresora";
    $dto2->tipo = "laptop";
    $dto3->tipo = "móvil";

    return [
        $dto,
        $dto2,
        $dto3
    ];
}

function mockEntersDTO($container){

    $dto = $container->make('enterDTO');
    $dto2 = $container->make('enterDTO');
    $dto3 = $container->make('enterDTO');

    $contDTO = $container->make('contDTO');
    $contDTO2 = $container->make('contDTO');
    $contDTO3 = $container->make('contDTO');
    
    $contDTO->nombre_completo = "Mariana Torres López";
    $dto->nombre_comercial = "Tecnologías Avanzadas del Bajío";
    $dto->razon_social = "Tecnologías Avanzadas del Bajío S.A. de C.V.";
    $dto->calle_numero = "Av. Insurgentes 1450";
    $dto->entre_calles = "Entre Reforma y Hidalgo";
    $dto->dirigirse_con = "Ing. Laura Martínez";
    $dto->telefonos = "462-123-4567, 462-987-6543";
    $dto->horario = "Lunes a Viernes de 9:00 a 18:00";
    $dto->atencion = "Atención a empresas de TI";
    $dto->colonia = "Centro";
    $dto->localidad = "Irapuato";
    $dto->email = "contacto@tabajio.com.mx";

    $contDTO2->nombre_completo = "Roberto Gómez Hernández";
    $dto2->nombre_comercial = "Farmacia La Salud";
    $dto2->razon_social = "Farmacia La Salud de Guanajuato S.A.";
    $dto2->calle_numero = "Calle Morelos 220";
    $dto2->entre_calles = "Entre Juárez y 5 de Mayo";
    $dto2->dirigirse_con = "Lic. Roberto Gómez";
    $dto2->telefonos = "462-555-1122";
    $dto2->horario = "Todos los días de 8:00 a 22:00";
    $dto2->atencion = "Venta de medicamentos y consultas rápidas";
    $dto2->colonia = "San Juan";
    $dto2->localidad = "León";
    $dto2->email = "ventas@farmaciasalud.com";

    $contDTO3->nombre_completo = "Laura Martínez Sánchez";
    $dto3->nombre_comercial = "Restaurante El Sabor Mexicano";
    $dto3->razon_social = "El Sabor Mexicano S. de R.L.";
    $dto3->calle_numero = "Blvd. Díaz Ordaz 780";
    $dto3->entre_calles = "Entre Las Flores y Los Pinos";
    $dto3->dirigirse_con = "Chef Mariana Torres";
    $dto3->telefonos = "462-321-7788";
    $dto3->horario = "Martes a Domingo de 13:00 a 23:00";
    $dto3->atencion = "Comida típica mexicana y banquetes";
    $dto3->colonia = "Las Águilas";
    $dto3->localidad = "Celaya";
    $dto3->email = "reservaciones@sabormexicano.mx";

    return [
        [$dto, $contDTO],
        [$dto2, $contDTO2],
        [$dto3, $contDTO3]
    ];
}

function mockDevicesDTO($container, $enterIds, $typIds){

    $dto = $container->make('dceDTO');
    $dto2 = $container->make('dceDTO');
    $dto3 = $container->make('dceDTO');
    $dto4 = $container->make('dceDTO');
    $dto5 = $container->make('dceDTO');
    $dto6 = $container->make('dceDTO');

    $dto->empresa_id = $enterIds[0];
    $dto->tipo_id = $typIds[0];
    $dto->marca = "HP";
    $dto->modelo = "LaserJet Pro M404dn";
    $dto->numero_serie = "HP-PRN-2026-001";

    $dto2->empresa_id = $enterIds[0];
    $dto2->tipo_id = $typIds[0];
    $dto2->marca = "Canon";
    $dto2->modelo = "PIXMA G6020";
    $dto2->numero_serie = "CN-PRN-2026-002";

    $dto3->empresa_id = $enterIds[1];
    $dto3->tipo_id = $typIds[1];
    $dto3->marca = "Dell";
    $dto3->modelo = "Latitude 5420";
    $dto3->numero_serie = "DL-LAP-2026-003";

    $dto4->empresa_id = $enterIds[1];
    $dto4->tipo_id = $typIds[1];
    $dto4->marca = "Lenovo";
    $dto4->modelo = "ThinkPad X1 Carbon Gen 9";
    $dto4->numero_serie = "LN-LAP-2026-004";

    $dto5->empresa_id = $enterIds[2];
    $dto5->tipo_id = $typIds[2];
    $dto5->marca = "Samsung";
    $dto5->modelo = "Galaxy S23 Ultra";
    $dto5->numero_serie = "SM-MOB-2026-005";

    $dto6->empresa_id = $enterIds[2];
    $dto6->tipo_id = $typIds[2];
    $dto6->marca = "Apple";
    $dto6->modelo = "iPhone 15 Pro Max";
    $dto6->numero_serie = "AP-MOB-2026-006";

    return [
        $dto,
        $dto2,
        $dto3,
        $dto4,
        $dto5,
        $dto6
    ];
}

function mockContactsDTO($container, $enterIdsArr){

    $dto = $container->make('contDTO');
    $dto2 = $container->make('contDTO');
    $dto3 = $container->make('contDTO');
    $dto4 = $container->make('contDTO');
    $dto5 = $container->make('contDTO');
    $dto6 = $container->make('contDTO');
    
    $dto->empresa_id = $enterIdsArr[0];
    $dto->nombre_completo = "Javier Ramírez Ortega";

    $dto2->empresa_id = $enterIdsArr[0];
    $dto2->nombre_completo = "Claudia Fernández Ruiz";

    $dto3->empresa_id = $enterIdsArr[1];
    $dto3->nombre_completo = "Andrés Castillo Morales";

    $dto4->empresa_id = $enterIdsArr[1];
    $dto4->nombre_completo = "Olivia Ruiz Martinez";

    $dto5->empresa_id = $enterIdsArr[2];
    $dto5->nombre_completo = "Elena Carolina Lopez";
    
    $dto6->empresa_id = $enterIdsArr[2];
    $dto6->nombre_completo = "Oswaldo Rentería Morales";

    return [
        $dto,
        $dto2,
        $dto3,
        $dto4,
        $dto5,
        $dto6
    ];
}

function mockBinnDTOs($container, $userId, $contIdsArr, $dceIdsArr){

    $dto = $container->make('binnDTO');
    $dto2 = $container->make('binnDTO');
    $dto3 = $container->make('binnDTO');
    $dto4 = $container->make('binnDTO');
    $dto5 = $container->make('binnDTO');
    $dto6 = $container->make('binnDTO');

    $dto->usuario_id = $userId;
    $dto->contacto_id = $contIdsArr[0];
    $dto->servicio = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Ab consequuntur sunt dolores corporis, nemo in totam nihil iure velit quas, rem blanditiis dignissimos rerum nesciunt illum temporibus cum reprehenderit? Laborum?";

    $dto2->usuario_id = $userId;
    $dto2->contacto_id = $contIdsArr[1];
    $dto2->servicio = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Fugiat in distinctio ea odit incidunt minus, voluptate eos quod a quisquam laudantium quis reiciendis atque impedit? Voluptates eligendi quibusdam itaque ipsa?";

    $dto3->usuario_id = $userId;
    $dto3->contacto_id = $contIdsArr[0];
    $dto3->equipo_id = $dceIdsArr[0];
    $dto3->Actividades_realizadas = "Lorem ipsum dolor sit amet consectetur, adipisicing elit. Impedit eius nostrum, laboriosam excepturi iusto odit accusamus dolorem, fugiat voluptatum cupiditate tenetur distinctio ipsum minima fuga sit eveniet eum unde omnis?";
    $dto3->observaciones = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Ducimus beatae aspernatur vel quia corporis! Aut commodi voluptatem voluptatum reprehenderit nesciunt iste ratione corporis, aliquam minus maiores labore explicabo sapiente aspernatur.";
    $dto3->estatus = "falta confirmar";

    $dto4->usuario_id = $userId;
    $dto4->contacto_id = $contIdsArr[1];
    $dto4->equipo_id = $dceIdsArr[1];
    $dto4->Actividades_realizadas = "Lorem ipsum, dolor sit amet consectetur adipisicing elit. Enim dolores temporibus voluptatem fugit quisquam voluptatum eum, corrupti consequatur beatae quas. Quam iste, eveniet ipsam similique ea adipisci totam officia reiciendis.";
    $dto4->observaciones = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Blanditiis animi fuga esse expedita atque recusandae, veritatis, eveniet nemo ad deserunt dolore voluptatibus error totam dicta minima molestiae praesentium neque excepturi?";
    $dto4->estatus = "falta confirmar";

    $dto5->usuario_id = $userId;
    $dto5->contacto_id = $contIdsArr[1];
    $dto5->servicio = "Lorem, ipsum dolor sit amet consectetur adipisicing elit. Nesciunt, earum! Error facilis cum sed hic excepturi nemo culpa molestias debitis, explicabo omnis laudantium dolores soluta rerum blanditiis ipsam eos. Itaque.";
    $dto5->observaciones = "Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum sequi voluptate omnis, ea nesciunt, dicta saepe pariatur dolorum facilis tempora et? A nihil nisi velit, facilis atque nemo cum eos!";
    $dto5->estatus = "cancelado";

    $dto6->usuario_id = $userId;
    $dto6->contacto_id = $contIdsArr[2];
    $dto6->equipo_id = $dceIdsArr[0];
    $dto6->Actividades_realizadas = "Lorem ipsum dolor, sit amet consectetur adipisicing elit. Iste modi asperiores consectetur. Reprehenderit dolorem modi illo. Doloribus neque tempore, doloremque consectetur quis aut dicta ab veniam, fugit sequi debitis velit!";
    $dto6->observaciones = "Lorem ipsum dolor sit amet consectetur adipisicing elit. Praesentium distinctio voluptas hic autem, natus animi ipsam, tempore minus aliquid dicta, dolorem deleniti recusandae? Quae error quam magni odit provident tempora!";
    $dto6->monto = "2564.50";
    $dto6->firma_cliente = "test.png";
    $dto6->fin = "2026-07-10";
    $dto6->estatus = "finalizado";
    
    return [
        $dto,
        $dto2,
        $dto3,
        $dto4,
        $dto5,
        $dto6
    ];
}

function cleanTable($pdo, $table){
    $database = $pdo->getConnection();
    $stmt = $database->prepare('DELETE FROM '.$table);
    $stmt->execute();
}

function getBinnIds($pdo){
    $database = $pdo->getConnection();
    $stmt = $database->prepare('SELECT Id FROM Bitacoras');
    $stmt->execute();
    return $stmt->fetchAll();
}

//------------------------UTILS METHODS MOCKUPS-----------------------------
function mockSetIdSession(){

    if($_GET["homeAction"] === "editSign" && !empty($_SESSION["isAdmin"]) && 
        sizeof($_POST) > 0){ 
        
        $_SESSION["idSession"]["userSign_userId"] = (!empty($_POST["usuarios"]) && (!preg_match('/[A-Za-z]+/', $_POST["usuarios"]) ||
            !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["usuarios"]))) ? $_POST["usuarios"] : false;

        $get_params = http_build_query($_POST);
        $_SESSION["header"] = "../home/?homeController=user&homeAction=editSign&".$get_params;
        //exit;    
    }

    if($_GET["homeAction"] === "userNewPassword" && !empty($_SESSION["isAdmin"]) && 
        sizeof($_POST) > 0){
        $_SESSION["idSession"]["userNewPwd_userId"] = (!empty($_POST["usuarios"]) && (!preg_match('/[A-Za-z]+/', $_POST["usuarios"]) ||
            !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["usuarios"]))) ? $_POST["usuarios"] : false;

        $get_params = http_build_query($_POST);
        $_SESSION["header"] = "../home/?homeController=user&homeAction=userNewPassword&".$get_params;
        //exit;
    }

    if($_GET["homeAction"] === "index" && !empty($_SESSION["isAdmin"]) && 
        sizeof($_POST) > 0){

        if($_GET["homeController"] === "enterprise"){
            $_SESSION["idSession"]["enterpriseEdit_enterId"] = (!empty($_POST["empresas"]) && (!preg_match('/[A-Za-z]+/', $_POST["empresas"]) ||
                !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["empresas"]))) ? $_POST["empresas"] : false;
            
            $get_params = http_build_query($_POST);
            $_SESSION["header"] = "../home/?homeController=enterprise&homeAction=index&".$get_params;
            //exit;    
        }
    }

    if($_GET["homeAction"] === "editDevice" && !empty($_SESSION["isAdmin"]) && 
        sizeof($_POST) > 0){
        $_SESSION["idSession"]["devicesEdit_enterId"] = (!empty($_POST["empresas"]) && (!preg_match('/[A-Za-z]+/', $_POST["empresas"]) ||
            !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["empresas"]))) ? $_POST["empresas"] : false;
        
        $get_params = http_build_query($_POST);
        $_SESSION["header"] = "../home/?homeController=device&homeAction=editDevice&".$get_params;
        //exit;    
    }

    if($_GET["homeAction"] === "devicesReport" && !empty($_SESSION["isAdmin"]) && 
        sizeof($_POST) > 0){
        $_SESSION["idSession"]["devicesReport_enterId"] = (!empty($_POST["empresas"]) && (!preg_match('/[A-Za-z]+/', $_POST["empresas"]) ||
            !preg_match('/[!@#$%^&*(),.?":{}|<>]+/', $_POST["empresas"]))) ? $_POST["empresas"] : false;
        
        $get_params = http_build_query($_POST);
        $_SESSION["header"] = "../home/?homeController=device&homeAction=devicesReport&".$get_params;
        //exit;    
    }
}

function mockSetBinnFilterSessions($dto){

    if(sizeof($_POST) > 0){
        $dto->empresa_id = $_POST["empresaId"];
        $dto->contacto_id = $_POST["contactoId"];
        $dto->actividad = $_POST["servicioOEquipo"];
        $dto->equipo_id = (isset($_POST["equipoId"])) ? $_POST["equipoId"] : '';
        $dto->estatus = $_POST["estatus"];
        $dto->dates_type = $_POST["startedOrEnded"];
        $dto->left_day = $_POST["leftDay"];
        $dto->right_day = $_POST["rightDay"];
        $dto->visibilidad = $_POST["visible"]; 
        $errorsArr = BinnacleVerifications::verifyingFilterOptions($dto);

        if(sizeof($errorsArr) > 0)
            throw new UnauthorizedDataException("filtrado no valido, si elegiste solo una fecha, hay que indicar la otra fecha para calcular el rango de tiempo".json_encode($errorsArr));
        
        $queryString = http_build_query($_POST);    
        
        $_SESSION["binnFilterSession"]["Empresa_id"] = (!empty($dto->empresa_id)) ? $dto->empresa_id : null;
        $_SESSION["binnFilterSession"]["Contacto_id"] = (!empty($dto->contacto_id)) ? $dto->contacto_id : null;
        $_SESSION["binnFilterSession"]["IsServiceOrDevice"] = $dto->actividad;
        $_SESSION["binnFilterSession"]["Equipo_id"] = (!empty($dto->equipo_id)) ? $dto->equipo_id : null;
        $_SESSION["binnFilterSession"]["Estatus"] = $dto->estatus;
        $_SESSION["binnFilterSession"]["StartedOrEnded"] = $dto->dates_type;
        $_SESSION["binnFilterSession"]["LeftDay"] = (!empty($dto->left_day)) ? $dto->left_day : null;
        $_SESSION["binnFilterSession"]["RightDay"] = (!empty($dto->right_day)) ? $dto->right_day : null;
        $_SESSION["binnFilterSession"]["Visible"] = $dto->visibilidad;

        $_SESSION["header"] = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binnaclesReport&".$queryString;
        //exit;
    }
}

function mockSaveSignaturesFiles($root){
    
    if(isset($_FILES["newTechSign"])){
        
        if(!unlink($root.'/'.$_SESSION["formSession"]["dataSelectionForSigns"]["oldTechSign"])){
            $_SESSION["exceptions"]["unlinkTechSignEx"] = "La supuesta firma del técnico no se encontró en la aplicación web";
            Utils::unsetFormSessions();
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
            //exit;
        }
        
        $tech_sign_file = $_FILES["newTechSign"];
        $technician_name = $tech_sign_file["name"];
        
        if(!is_dir($root)){
            mkdir($root, 0777, true);
        }
        copy($tech_sign_file["tmp_name"], 
                    $root.'/'.$technician_name);
        $_SESSION["formSession"]["techSignature"] = $technician_name;
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=techsign";
    }
    
    if(isset($_FILES["techSign"])){
        $tech_sign_file = $_FILES["techSign"];
        $technician_name = $tech_sign_file["name"];
        if(!is_dir($root)){
            mkdir($root, 0777, true);
        }
        copy($tech_sign_file["tmp_name"], 
                    $root.'/'.$technician_name);
        $_SESSION["formSession"]["techSignature"] = $technician_name;
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=techsign";
    }
    
    if(isset($_FILES["cliSign"])){
        $cli_sign_file = $_FILES["cliSign"];
        $client_name = $cli_sign_file["name"];
        if(!is_dir($root)){
            mkdir($root, 0777, true);
        }
        copy($cli_sign_file["tmp_name"], 
                    $root.'/'.$client_name);
        $_SESSION["formSession"]["clientSignature"] = $client_name;
        
        if((!empty($_SERVER['HTTP_X_REQUESTED_WITH'])                           && 
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest')){                
            $result = "Firma del cliente guardado con éxito";
            //exit;
        }
    }

    return $result;
}

function mockSessionLifetime(){
    if (!empty($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 1800) {
        // Caducar sesión
        $_SESSION = [];
        //session_unset();
        //session_destroy();
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }
    return $result;
}

function mockShowError($container){
    $error = $container->make('ErrorController');
    //$error->index();
    return $error;
}

function mockDefaultHomePage($container, $default){
    $controllerName = $default;
    //$defaultAction = default_action;
    $controlador = $container->make($controllerName);
    //$controlador->$defaultAction();
    return $controlador;
}

function mockGenerateWelcomeBanner(){
    if (!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])) {
        $result = '../views/adminLayouts/menuSides/welcomeBanner.php';
    } else if (!empty($_SESSION["identity"])) {
        $result = '../views/userLayouts/menuSides/welcomeBanner.php';
    }
    return $result;
}

function mockSetAsideWithVerify(){
    if(!empty($_SESSION["isAdmin"])){
        $result = '../views/adminLayouts/menuSides/aside.php';
    }
    return $result;
}

function mockSetDataSelectionForSigns(){

    $result = null;
    
    if (sizeof($_POST) > 0 && !empty($_POST["binnId"])) {
        
        if(preg_match('/[0-9]+/', $_POST["binnId"]) &&
            $_POST["userId"] === $_SESSION["identity"]["Id"]){
            
            $cliente_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["clientName"]);
            $commercial_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["clientEntName"]);
            $usuario_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userName"]);
            $usuario_ape = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userSurname"]);
            
            $_SESSION["formSession"]["dataSelectionForSigns"] = array(
                "binnId"        => $_POST["binnId"],
                "clientName"    => $cliente_nombre,
                "altClientName" => $commercial_nombre,
                "userId"        => $_POST["userId"],
                "userName"      => $usuario_nombre,
                "userSurname"   => $usuario_ape
            );

            
        }else{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
            //exit;
        }
    }
    
    if(sizeof($_POST) > 0 && !empty($_POST["oldTechSign"])){
        
        if(preg_match('/[0-9]+/', $_POST["userId"])){
            
                $usuario_nombre = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userName"]);
                $usuario_ape = preg_replace('/[\x{00C0}-\x{00FF}]/u', '0', $_POST["userSurname"]);

                $_SESSION["formSession"]["dataSelectionForSigns"] = array(
                    "userId"        => $_POST["userId"],
                    "userName"      => $usuario_nombre,
                    "userSurname"   => $usuario_ape,
                    "oldTechSign"   => $_POST["oldTechSign"]
                );
        }else{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
            //exit;
        }
    }

    return $result;
}
//------------------------UTILS METHODS MOCKUPS-----------------------------

//--------------------------------------------------------------------------

//--------------------CONTROLLERS METHODS MOCKUPS-------------------------

//----------------------------UI CONTROLLER---------------------------------

function mockUIindex(){
    if(!empty($_SESSION["identity"])){
        if($_SESSION["identity"]["Privilegio"] === "user"){
            $_SESSION['LAST_ACTIVITY'] = time();
            $result = '../views/userLayouts/menu.php';
        }else if(!empty($_SESSION["isAdmin"])){
            $_SESSION['LAST_ACTIVITY'] = time();
            $result = '../views/adminLayouts/welcomeMessage.php';
        }
    }else if(empty($_SESSION["identity"])){
        $result = '../views/login.php';
    }

    return $result;
}

//--------------------------------------------------------------------------

//---------------------------USERS CONTROLLER-------------------------------

function mockUserIndex(){
    if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        $result = '../views/adminLayouts/userInsertForm.php';
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }
    return $result;
}

function mockEditSign($container = null, $usrDTO = null, $usrSignService = null){
    if(!empty($_SESSION["identity"])){
            $_SESSION['LAST_ACTIVITY'] = time();
        //Utils::setIdSession();
        try{
            
            if(!empty($_SESSION["isAdmin"]))
                $users = $container->make('usrService')->getAllInfo();
            

            if(!empty($_SESSION["idSession"]["userSign_userId"])){

                $usrDTO->user_id = $_SESSION["idSession"]["userSign_userId"];
                $user_info = $container->make('usrService')->getInfo($usrDTO);
                
                if(!empty($user_info["Firma"])){
                    if(!file_exists("../../finishing/uploads/firmas/".$user_info["Firma"])){
                        $usrSignService->insertSignature($usrDTO);
                        $user_info = $container->make('usrService')->getInfo($usrDTO);
                    }
                }
            }
            
            
            if($_SESSION["identity"]["Privilegio"] === "user"){

                
                $usrDTO->user_id = $_SESSION["identity"]["Id"];

                if(!empty($_SESSION["identity"]["Firma"])){
                    if(!file_exists("../../finishing/uploads/firmas/".$_SESSION["identity"]["Firma"])){
                        $usrSignService->insertSignature($usrDTO);
                        $_SESSION["identity"]["Firma"] = null;
                    }
                }
            }
            
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["gettingUsersException"] = "No se pudo hacer comprobaciones necesarias ".
            "para la edición de firma, posible corte de conexión a la base de datos";
            if(!empty($_SESSION["isAdmin"])){
                $users = [];
                $user_info = [];
            }                
        }finally{
            $result = (isset($_SESSION["header"])) ? $_SESSION["header"] : '../views/userLayouts/editSign.php';
        }
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return [
        "result"    => $result,
        "users"     => (!empty($users)) ? $users : null,
        "user_info" => (!empty($user_info)) ? $user_info : null
    ];
}

function mockUserNewPassword($container = null){
    if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        //Utils::setIdSession();

        try{

            $users = $container->make("usrService")->getAllInfo();

            if(!empty($_SESSION["idSession"]["userNewPwd_userId"])){
                $dto = $container->make("usrDTO");
                $dto->user_id = $_SESSION["idSession"]["userNewPwd_userId"];
                $user_info = $container->make("usrService")->getInfo($dto);
            }
            
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage(); 
        }catch(Exception $ex){
            $_SESSION["exception"]["userInfoException"] = "No se logró conseguir "
                        ."la información del usuario, posible corte "
                        ."de conexión a la base de datos";
            $users = [];
            $user_info = [];             
        }finally{
            $result = (isset($_SESSION["header"])) ? $_SESSION["header"] : '../views/adminLayouts/userNewPwd.php';
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit; 
    }

    return [
        "result" => $result,
        "users" => (isset($users)) ? $users : null,
        "user_info" => (isset($user_info)) ? $user_info : null
    ];
}

function mockLogin($container = null){

    $dto = $container->make('usrDTO');    

    if(sizeof($_POST) > 0){

        $dto->alias = $_POST["user"];
        $dto->contrasena = $_POST["pwd"];
        $errorArr = UserVerifications::verifyingLogin($dto);

        try{
            
            if(sizeof($errorArr) === 0){
                
                $possible_user = $container->make("usrParticularSrv")->login($dto);
                (empty($possible_user["loginFailed"])) ?
                    $_SESSION["identity"] = $possible_user :
                    $_SESSION["errors"] = $possible_user;
                
                if(!empty($_SESSION["identity"]))
                    Utils::setAdminWithVerify();
            }else{
                $_SESSION["errors"] = $errorArr;
            }

        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["withoutConnextion"] = "Se ha cortado la conexión a la base de datos". $ex;
        }
    }
}

function mockLogout(){
    
    if(!empty($_SESSION["identity"])){
        
        unset($_SESSION["identity"]);
        //session_destroy();
        //header("Location: ". base_url."home/");
        //exit;
    }
    //header("Location: ". base_url."home/");
    //exit;
}

function mockInsertDBUser($container = null){
    if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

        $dto = $container->make('usrDTO');
        
        $dto->nombre = $_POST["nombre"];
        $dto->apellidos = $_POST["apellidos"];
        $dto->alias = $_POST["alias"];
        $dto->contrasena = $_POST["contrasena"];
        $dto->conf_pwd = $_POST["confContrasena"];
        $dto->privilegio = $_POST["privilegio"];
        $dto->admin_pwd = $_POST["adminContrasena"];
        $errorArr = UserVerifications::verifyingInsertion($dto);

        try{
            
            if(empty($errorArr["adminContrasena"]))
                $isRejection = Utils::setAdminVerification($dto, $container->make('usrParticularSrv'));
            if(sizeof($isRejection) > 0)
                $errorArr = $isRejection;

            (sizeof($errorArr) === 0) ?
                $container->make('usrService')->insertInfo($dto) :
                $_SESSION["errors"] = $errorArr;

            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "El usuario ha sido creado con éxito";
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["userDataException"] = "Acción fallida, probable nombre de usuario existente en la base de datos o falta de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=user&homeAction=index";
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return $result;
}

function mockUpdateUserPassword($container = null){
    if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

        $dto = $container->make('usrDTO');

        $dto->user_id = $_POST["usuarioId"];
        $dto->contrasena = $_POST["contrasena"];
        $dto->conf_pwd = $_POST["confContrasena"];
        $dto->admin_pwd = $_POST["adminContrasena"];
        $errorArr = UserVerifications::verifyingUpdate($dto);

        try{
            if(empty($errorArr["adminContrasena"]))
                $isRejection = Utils::setAdminVerification($dto, $container->make('usrParticularSrv'));
            if(sizeof($isRejection) > 0)
                $errorArr = $isRejection;

            (sizeof($errorArr) === 0) ?
                $container->make('usrService')->updateInfo($dto) :
                $_SESSION["errors"] = $errorArr;

            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "La contraseña se reestableció "
                        . "con éxito";    
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["userPWDException"] = "No se pudo reestablecer "
                        ."la contraseña, posible corte de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=user&homeAction=userNewPassword";
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit; 
    }

    return ['result' => $result, 'errorArr' => (isset($errorArr)) ? $errorArr : null];
}

function mockDisableUser($container = null){
    if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

        $dto = $container->make('usrDTO');

        $dto->user_id = $_POST["usuarioId"];
        $dto->visibilidad = $_POST["visibilidad"];
        $dto->admin_pwd = $_POST["adminContrasena"];
        $errorArr = SwitchVerification::verifyingSwitch($dto);

        try{
            if(empty($errorArr["adminContrasena"]))
                $isRejection = Utils::setAdminVerification($dto, $container->make('usrParticularSrv'));
            if(sizeof($isRejection) > 0)
                $errorArr = $isRejection;

            (sizeof($errorArr) === 0) ?
                $container->make('usrService')->updateVisibility($dto) :
                $_SESSION["errors"] = $errorArr;
            
            if(empty($_SESSION["errors"])){
                $_SESSION["success"] = "Se desactivó al usuario con éxito";
                //$_SESSION["idSession"]["userNewPwd_userId"] = false;
            }    
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
        }catch(AutomaticValueException $ex){
            $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["disableUserEx"] = "No se logró desactivar al usuario, posible corte "
                        . "de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=user&homeAction=userNewPassword";
            //exit;
        }
            
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit; 
    }

    return ['result' => $result, 'errorArr' => (isset($errorArr)) ? $errorArr : null];
}

//--------------------------------------------------------------------------

//-------------------------TYPES CONTROLLER---------------------------------

function mockTypeIndex(){
    if(!empty($_SESSION["identity"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        $result = '../views/userLayouts/newTypeForm.php';
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return $result;
}

function mockEditTypes($container = null){
    if(!empty($_SESSION["isAdmin"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        try {
            $types_arr = $container->make('typService')->getAllInfo();
        } catch (Exception $ex) {
            $_SESSION["exceptions"]["typesDataForEditionEx"] = "No se logró conseguir los datos de los registros de tipos, lo más probable es que se haya "
                    . "cortado la conexión a la base de datos";
            $types_arr = [];
        }finally{
            $result = '../views/adminLayouts/typesEditForms.php';
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, 'types_arr' => (isset($types_arr)) ? $types_arr : null];
}

function mockInsertType($container = null){
    if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){
        $dto = $container->make('typDTO');
        $dto->tipo = $_POST["tipo"];
        $errorArr = TypeVerifications::verifyingInsertion($dto);
        try{
            (sizeof($errorArr) === 0) ? $container->make('typService')->insertInfo($dto) :
                $_SESSION["errors"] = $errorArr;

            if(empty($_SESSION["errors"]))    
                $_SESSION["success"] = "El tipo de equipo ha sido creado con éxito";
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["typeInsertionException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la inserción de un tipo de equipo, "
                        ."lo más probable es que se haya ingresado un tipo "
                        ."existente en la base de datos o se haya cortado la conexión a "
                        . "la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=type&homeAction=index";
            //exit;
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}

function mockUpdateTypeInfo($container = null){
    if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){
        
        $dto1 = $container->make('typDTO');
        $dto2 = $container->make('usrDTO');

        $dto1->type_id = $_POST["tipoId"];
        $dto1->tipo = $_POST["tipo"];
        $dto2->admin_pwd = $_POST["adminContrasena"];
        $errorArr = TypeVerifications::verifyingUpdate($dto1, $dto2);
        
        try{
            if(empty($errorArr["adminContrasena"]))
                $isRejection = Utils::setAdminVerification($dto2, $container->make('usrParticularSrv'));
            if(sizeof($isRejection) > 0)
                $errorArr = $isRejection;
            
            (sizeof($errorArr) === 0) ?
                $container->make('typService')->updateInfo($dto1) :
                $_SESSION["errors"] = $errorArr;
            
            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se modificó el tipo con ID ".$dto1->type_id." con éxito";    
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["updateTypeException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la actualización de un tipo de equipo, "
                        ."lo más probable es que se haya ingresado un tipo "
                        ."existente en la base de datos o se haya cortado la conexión a "
                        . "la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=type&homeAction=editTypes";
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, 'errorArr' => (isset($errorArr)) ? $errorArr : null];
}

function mockEnableOrDisableType($container = null){
    if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

        $dto = $container->make('typDTO');

        $dto->type_id = $_POST["tipoId"];
        $dto->visibilidad = $_POST["visibilidad"];
        $errorArr = SwitchVerification::verifyingSwitch($dto);
        $str_portion_one = ($dto->visibilidad === "DISABLED") ? 
        "desactivó":"activó";
        $str_portion_two = ($dto->visibilidad === "DISABLED") ? 
        "desactivar":"activar";
        
        try{
            (sizeof($errorArr) === 0) ?
                $container->make('typService')->updateVisibility($dto) :
                $_SESSION["errors"] = $errorArr;

            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se "
                    .$str_portion_one." el tipo con ID ".$dto->type_id." con éxito";
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(AutomaticValueException $ex){
            $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." el tipo con ID ".$dto->type_id.", posible corte de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=type&homeAction=editTypes";
            //exit;
        }
            
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit; 
    }

    return ['result' => $result, 'errorArr' => (isset($errorArr)) ? $errorArr : null];
}
//--------------------------------------------------------------------------

//-----------------------ENTERPRISES CONTROLLER-----------------------------

function mockEnterpriseIndex($container = null){
    if(!empty($_SESSION["isAdmin"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        //Utils::setIdSession();

        try {
            $enterprises = $container->make('enterService')->getAllInfo();

            if(!empty($_SESSION["idSession"]["enterpriseEdit_enterId"])){
                $dto1 = $container->make('enterDTO');
                $dto2 = $container->make('contDTO');
                $dto1->enterprise_id = $_SESSION["idSession"]["enterpriseEdit_enterId"];
                $dto2->empresa_id = $_SESSION["idSession"]["enterpriseEdit_enterId"];
                $ent_arr = $container->make('enterService')->getInfo($dto1);
                $contacts_arr = $container->make('contService')->getChildrenByEnterprise($dto2);
            }
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch (Exception $ex) {
            $_SESSION["exceptions"]["selectDataEnterEditEx"] = "No se logró obtener la "
                            ."información de la empresa y sus contactos, posible corte "
                            ."de conexión a la base de datos";
            $enterprises = [];
            $ent_arr = [];
            $contacts_arr = [];
        }finally{
            $result = (isset($_SESSION["header"])) ? $_SESSION["header"] : '../views/adminLayouts/enterAndContactsEditForms.php';
        }
            
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return [
        "result" => $result,
        "enterprises" => (isset($enterprises)) ? $enterprises : null,
        "ent_arr" => (isset($ent_arr)) ? $ent_arr : null,
        "contacts_arr" => (isset($contacts_arr)) ? $contacts_arr : null
    ];
}

function mockUpdateEnterInfo($container = null){
    if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){
        
        $dto = $container->make('enterDTO');
        $dto2 = $container->make('usrDTO');

        $dto2->admin_pwd = $_POST["adminContrasena"];
        $dto->enterprise_id = $_POST["empresaId"];
        $dto->nombre_comercial = $_POST["nombreComercial"];
        $dto->razon_social = $_POST["razonSocial"];
        $dto->calle_numero = $_POST["calleYNumero"];
        $dto->entre_calles = $_POST["entreCalles"];
        $dto->dirigirse_con = $_POST["dirigirseCon"];
        $dto->telefonos = $_POST["telefonos"];
        $dto->horario = $_POST["horario"];
        $dto->atencion = $_POST["atencion"];
        $dto->colonia = $_POST["colonia"];
        $dto->localidad = $_POST["localidad"];
        $dto->email = $_POST["email"];
        $errorArr = EnterpriseVerifications::verifyingUpdate($dto, $dto2);
        
        try{
            if(empty($errorArr["adminContrasena"]))
                $isRejection = Utils::setAdminVerification($dto2, $container->make('usrParticularSrv'));
            if(sizeof($isRejection) > 0)
                $errorArr = $isRejection;

            (sizeof($errorArr) === 0) ?
                $container->make('enterService')->updateInfo($dto) :
                $_SESSION["errors"] = $errorArr;
            
            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se actualizaron los datos de la empresa con éxito";    
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["updateEnterInfoEx"] = "Hubo una excepción en "
                        ."el proceso de actualización de datos de la "
                        ."empresa, posible corte de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=enterprise&homeAction=index";
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, 'errorArr' => (isset($errorArr)) ? $errorArr : null];
}

function mockEnableOrDisableEnterprise($container = null){
    if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

        $dto = $container->make('enterDTO');
        
        $dto->enterprise_id = $_POST["empresaId"];
        $dto->visibilidad = $_POST["visibilidad"];
        $errorArr = SwitchVerification::verifyingSwitch($dto);
        $str_portion_one = ($dto->visibilidad === "DISABLED") ? 
        "desactivó":"activó";
        $str_portion_two = ($dto->visibilidad === "DISABLED") ? 
        "desactivar":"activar";
        
        try{
            (sizeof($errorArr) === 0) ?
                $container->make('enterService')->updateVisibility($dto) :
                $_SESSION["errors"] = $errorArr;

            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se "
                    .$str_portion_one." la empresa con ID ".$dto->enterprise_id." con éxito";
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(AutomaticValueException $ex){
            $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." la empresa con ID ".$dto->enterprise_id.", posible corte de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=enterprise&homeAction=index";
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit; 
    }

    return ['result' => $result, 'errorArr' => (isset($errorArr)) ? $errorArr : null];
}

//--------------------------------------------------------------------------

//------------------------DEVICES CONTROLLER--------------------------------

function mockDeviceIndex($container = null){
    if(!empty($_SESSION["identity"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        try{
            $enters = $container->make('enterSelectSrv')->getInfoForSelects();
            $types = $container->make('typSelectSrv')->getInfoForSelects();
        } catch (Exception $ex) {
            $_SESSION["exceptions"]["dataForSelectDceFormEx"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la obtención de datos para la"
                        ." caja de selección de tipos y empresas del formulario, lo más "
                        ."probable es que se haya cortado la conexión "
                        ."a la base de datos";
            $enters = [];            
            $types = [];
        }finally{
            $result = '../views/userLayouts/newDeviceForm.php';
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return [
        "result" => $result,
        "enters" => (isset($enters)) ? $enters : null,
        "types" => (isset($types)) ? $types : null
    ];
}

function mockEditDevice($container = null){
    if(!empty($_SESSION["isAdmin"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        //Utils::setIdSession();

        try {
            $enterprises = $container->make('enterSelectSrv')->getInfoForSelects();

            if(!empty($_SESSION["idSession"]["devicesEdit_enterId"])){
                $dto = $container->make('dceDTO');
                $dto->empresa_id = $_SESSION["idSession"]["devicesEdit_enterId"];
                $devices_arr = $container->make('dceService')->getChildrenByEnterprise($dto);
                $types_arr = $container->make('typSelectSrv')->getInfoForSelects();
            }
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch (Exception $ex) {
            $_SESSION["exceptions"]["deviceInformationEx"] = "No se logró obtener la "
                            ."información de los equipos, posible corte "
                            ."de conexión a la base de datos";
            $enterprises = [];
            $devices_arr = [];
            $types_arr = [];
        }finally{
            $result = (isset($_SESSION["header"])) ? $_SESSION["header"] : '../views/adminLayouts/devicesEditForms.php';
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return [
        "result" => $result,
        "enterprises" => (isset($enterprises)) ? $enterprises : null,
        "devices_arr" => (isset($devices_arr)) ? $devices_arr : null,
        "types_arr" => (isset($types_arr)) ? $types_arr : null,
    ];
}

function mockDevicesReport($container = null){
    if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        //Utils::setIdSession();
        
        try{
            $enters = $container->make('enterService')->getAllInfo();

            if(!empty($_SESSION["idSession"]["devicesReport_enterId"])){
                $dto = $container->make('enterDTO');
                $dto2 = $container->make("dceDTO");
                $dto->enterprise_id = $_SESSION["idSession"]["devicesReport_enterId"];
                $dto2->empresa_id = $_SESSION["idSession"]["devicesReport_enterId"];
                $enter_info = $container->make('enterService')->getInfo($dto);
                $enter_devices = $container->make('dceService')->getChildrenByEnterprise($dto2);
            }
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch (Exception $ex) {
            $_SESSION["exceptions"]["gettingEntersException"] = "No se pudo conseguir "
                    ."el listado de empresas para la busqueda, posible "
                    ."corte de conexión a la base de datos";
            $enters = [];
            $enter_info = [];
            $enter_devices = [];
        }finally{
            $result = (isset($_SESSION["header"])) ? $_SESSION["header"] : '../views/adminLayouts/devicesReport.php';
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit; 
    }

    return [
        "result" => $result,
        "enters" => (isset($enters)) ? $enters : null,
        "enter_info" => (isset($enter_info)) ? $enter_info : null,
        "enter_devices" => (isset($enter_devices)) ? $enter_devices : null,
    ];
}

function mockInsertDevice($container = null){
    if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){

        $dto = $container->make('dceDTO');

        $dto->empresa_id = $_POST["empresas"];
        $dto->tipo_id = $_POST["tipos"];
        $dto->marca = $_POST["marca"];
        $dto->modelo = $_POST["modelo"];
        $dto->numero_serie = $_POST["ns"];
        $dto->numero_inventario = $_POST["numeroInventario"];
        $errorArr = DeviceVerifications::verifyingInsertion($dto);

        try{
            (sizeof($errorArr) === 0) ? $container->make('dceService')->insertChild($dto) :
                $_SESSION["errors"] = $errorArr;
            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se realizó el registro del equipo con éxito";

        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["deviceInsertionException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la inserción de un equipo, "
                        ."lo más probable es que se haya ingresado un número de serie "
                        ."existente en la base de datos o se haya cortado la conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=device&homeAction=index";
            //exit;
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}

function mockUpdateDeviceInfo($container = null){
    if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){

        $dto = $container->make('dceDTO');
        $dto2 = $container->make('usrDTO');

        $dto2->admin_pwd = $_POST["adminContrasena"];
        $dto->device_id = $_POST["dispositivoId"];
        $dto->tipo_id = $_POST["tipos"];
        $dto->marca = $_POST["marca"];
        $dto->modelo = $_POST["modelo"];
        $dto->numero_serie = $_POST["ns"];
        $dto->numero_inventario = $_POST["numeroInventario"];
        $errorArr = DeviceVerifications::verifyingUpdate($dto, $dto2);

        try{
            if(empty($errorArr["adminContrasena"]))
                $isRejection = Utils::setAdminVerification($dto2, $container->make('usrParticularSrv'));
            if(sizeof($isRejection) > 0)
                $errorArr = $isRejection;

            (sizeof($errorArr) === 0) ? $container->make('dceService')->updateChild($dto) :
                $_SESSION["errors"] = $errorArr;
            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se logró editar la información del dispositivo con ID ".$dto->device_id." con éxito";

        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["updateDeviceInfoEx"] = "No se logró editar la información del dispositivo, "
                        ."lo más probable es que se haya registrado un número "
                        ."de serie que ya se encuentra en la base de datos. Otro problema posible "
                        ."es que se haya cortado la conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=device&homeAction=editDevice";
            //exit;
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}

function mockEnableOrDisableDevice($container = null){
    if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

        $dto = $container->make('dceDTO');

        $dto->device_id = $_POST["equipoId"];
        $dto->visibilidad = $_POST["visibilidad"];
        $errorArr = SwitchVerification::verifyingSwitch($dto);
        $str_portion_one = ($dto->visibilidad === "DISABLED") ? 
        "desactivó":"activó";
        $str_portion_two = ($dto->visibilidad === "DISABLED") ? 
        "desactivar":"activar";
        try{
            (sizeof($errorArr) === 0) ?
                $container->make('dceService')->updateVisibility($dto) :
                $_SESSION["errors"] = $errorArr;

            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se "
                    .$str_portion_one." el equipo con ID ".$dto->device_id." con éxito";
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(AutomaticValueException $ex){
            $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." el equipo con ID ".$dto->device_id.", posible corte de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=device&homeAction=editDevice";
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit; 
    }

    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}
//--------------------------------------------------------------------------

//------------------------CONTACTS CONTROLLER-------------------------------

function mockContactIndex($container = null){
    if(!empty($_SESSION["identity"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        try{
            $enterprises = $container->make('enterSelectSrv')->getInfoForSelects();
        } catch (Exception $ex) {
            $_SESSION["exceptions"]["getInfoForSelectException"] = "Se generó un "
                    ."error interactuando con la base de datos "
                    ."en cuanto a la obtención de datos para la"
                    ." caja de selección de empresas del formulario, lo más "
                    ."probable es que se haya cortado la conexión "
                    ."a la base de datos";
            $enterprises = [];
        }finally{
            $result = '../views/userLayouts/newContactForm.php';
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return [
        "result" => $result,
        "enterprises" => (isset($enterprises)) ? $enterprises : null
    ];
}

function mockInsertContact($container = null){
    
    if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){

        $dto = $container->make('contDTO');
            
        $hidden_ent_id = (!empty($_POST["hiddenEntId"])) ? $_POST["hiddenEntId"] : null;
        
        try{
            if(isset($hidden_ent_id)){
                $dto->empresa_id = $hidden_ent_id;
                $dto->nombre_completo = $_POST["contacto"];
                $errorsArr = ContactVerifications::verifyingInsertion($dto);

                (sizeof($errorsArr) === 0) ? $container->make('contService')->insertChild($dto) :
                    $_SESSION["errors"] = $errorsArr;
                
                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se realizó el registro del contacto con éxito";    
            }else{

                $dto2 = $container->make('enterDTO');

                $dto->nombre_completo = $_POST["contacto"];
                $dto2->nombre_comercial = $_POST["nombreComercial"];
                $dto2->razon_social = $_POST["razonSocial"];
                $dto2->calle_numero = $_POST["calleYNumero"];
                $dto2->entre_calles = $_POST["entreCalles"];
                $dto2->dirigirse_con = $_POST["dirigirseCon"];
                $dto2->telefonos = $_POST["telefonos"];
                $dto2->horario = $_POST["horario"];
                $dto2->atencion = $_POST["atencion"];
                $dto2->colonia = $_POST["colonia"];
                $dto2->localidad = $_POST["localidad"];
                $dto2->email = $_POST["email"];
                $errorsArr = EnterpriseVerifications::verifyingInsertion($dto2, $dto);

                (sizeof($errorsArr) === 0) ? $container->make('enterService')->insertInfo($dto2, $dto) :
                    $_SESSION["errors"] = $errorsArr;
                
                if(empty($_SESSION["errors"]))
                    $_SESSION["success"] = "Se realizó el registro del contacto con éxito";

            }
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            if(isset($hidden_ent_id)){
                $_SESSION["exceptions"]["contactWithEnterIdInsertionException"] = "Se generó un "
                    ."error interactuando con la base de datos "
                    ."en cuanto a la inserción de un contacto vinculado a una empresa "
                    . "existente, posible falta de conexión";
            }else{
                $_SESSION["exceptions"]["contactTotalInsertionException"] = "Se generó un "
                    ."error interactuando con la base de datos "
                    ."en cuanto a la inserción total de un contacto, "
                    . "lo más probable es que se haya ingresado un nombre de empresa ya existente "
                    . "en la base de datos, o se haya cortado la conexión a la base de datos";
            }
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=contact&homeAction=index";
            //exit;
        }
            
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, "errorsArr" => (isset($errorsArr)) ? $errorsArr : null];
}

function mockUpdateContactInfo($container = null){
    if(!empty($_SESSION["isAdmin"])  && sizeof($_POST) > 0){

        $dto = $container->make('contDTO');
        $dto2 = $container->make('usrDTO');

        $dto->contact_id = $_POST["contactoId"];
        $dto->nombre_completo = $_POST["nombre"];
        $dto2->admin_pwd = $_POST["adminContrasena"];
        $errorsArr = ContactVerifications::verifyingUpdate($dto, $dto2);
        
        try{
            if(empty($errorArr["adminContrasena"]))
                $isRejection = Utils::setAdminVerification($dto2, $container->make('usrParticularSrv'));
            if(sizeof($isRejection) > 0)
                $errorsArr = $isRejection;

            (sizeof($errorsArr) === 0) ? $container->make('contService')->updateChild($dto) :
                $_SESSION["errors"] = $errorsArr;

            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se modificó al contacto con ID ".$dto->contact_id." con éxito";

        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unknownInDB"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["updateClientException"] = "Hubo un problema dentro del proceso de modificación del contacto, posible corte de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=enterprise&homeAction=index";
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, "errorsArr" => (isset($errorsArr)) ? $errorsArr : null];
}

function mockEnableOrDisableContact($container = null){
    if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

        $dto = $container->make('contDTO');

        $dto->contact_id = $_POST["contactoId"];
        $dto->visibilidad = $_POST["visibilidad"];
        $errorArr = SwitchVerification::verifyingSwitch($dto);
        $str_portion_one = ($dto->visibilidad === "DISABLED") ? 
        "desactivó":"activó";
        $str_portion_two = ($dto->visibilidad === "DISABLED") ? 
        "desactivar":"activar";
        
        try{
            (sizeof($errorArr) === 0) ?
                $container->make('contService')->updateVisibility($dto) :
                $_SESSION["errors"] = $errorArr;

            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se "
                    .$str_portion_one." el contacto con ID ".$dto->contact_id." con éxito";
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(AutomaticValueException $ex){
            $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." el contacto con ID ".$dto->contact_id.", posible corte de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=enterprise&homeAction=index";
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit; 
    }

    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}
//--------------------------------------------------------------------------

//-----------------------BINNACLES CONTROLLER-------------------------------

function mockBinnIndex($container = null){
    if(!empty($_SESSION["identity"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        try{
            $enterprises = $container->make('enterSelectSrv')->getInfoForSelects();
        } catch (Exception $ex) {
            $_SESSION["exceptions"]["getInfoForSelectsException"] = "Se generó un "
                    ."error interactuando con la base de datos "
                    ."en cuanto a la obtención de datos para la"
                    ." caja de selección de empresas de la bitácora, lo más "
                    ."probable es que se haya cortado la conexión "
                    ."a la base de datos";
            $enterprises = [];
        }finally{
            $result = '../views/userLayouts/firstForm.php';
        }    
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ["result" => $result, "enterprises" => (isset($enterprises)) ? $enterprises : null];
}

function mockFollowuplist($container = null){
    if(!empty($_SESSION["identity"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        $dto = $container->make('binnDTO');
        $dto->usuario_id = $_SESSION["identity"]["Id"];
        try {
            (!empty($_SESSION["jsondecoded"]["followUpNumKey"])) ?
                $page_elem = $_SESSION["jsondecoded"]["followUpNumKey"] :
                $page_elem = 1;
            
            $binn_pagination = $container->make('binnService')->getAllInfo(
                $dto,
                $page_elem,
                null,
                $_GET["homeAction"]
            );    
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        } catch (Exception $ex) {
            $_SESSION["exceptions"]["paginationArrException"] = "Se generó un "
                    ."error interactuando con la base de datos "
                    ."en cuanto a la generación de paginación".$ex;
        }finally{
            /*
            if(!empty($_SESSION["exceptions"])){
                header("Location: ".base_url."home/");
                exit;
            }
            */
            $result = '../views/userLayouts/followup.php';    
        }
            
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }
    
    return ["result" => $result, "binn_pagination" => (isset($binn_pagination)) ? $binn_pagination : null];
}

function mockBinnaclesReport($container = null){
    if(!empty($_SESSION["identity"]) && !empty($_SESSION["isAdmin"])){
        $_SESSION['LAST_ACTIVITY'] = time();
        
        $binn_pagination = [];
        try{
            $empresas = $container->make('enterSelectSrv')->getInfoForSelects();
            mockSetBinnFilterSessions($container->make('binnDTO'));

            if(!empty($_SESSION["binnFilterSession"])){
                (!empty($_SESSION["jsondecoded"]["binnsReportNumKey"])) ?
                $page_elem = $_SESSION["jsondecoded"]["binnsReportNumKey"] :
                $page_elem = 5;
            
                $binn_pagination = $container->make('binnService')->getAllInfo(
                    null,
                    $page_elem,
                    $_SESSION["binnFilterSession"],
                    $_GET["homeAction"]
                );
            }   

        }catch(UnauthorizedDataException $ex){
            $_SESSION["exceptions"]["unauthEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["binnsRowsPaginationEx"] = "Se generó un "
                    . "error interactuando con la base de datos "
                    . "en cuanto a la generación de paginación, posible falta de conexión a la base de datos";
            Utils::unsetBinnFilterSession();
        }finally{
            $result = (isset($_SESSION["header"])) ? $_SESSION["header"] : '../views/adminLayouts/binnaclesFilter.php';
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return [
        "result" => $result, 
        "binn_pagination" => (isset($binn_pagination)) ? $binn_pagination : null,
        "empresas" => (isset($empresas)) ? $empresas : null
    ];
}

function mockShowBinnacle($container = null){
    if(!empty($_SESSION["identity"]) && 
        !empty($_SESSION["isAdmin"])  && 
        !empty($_GET["homeId"])){
        $_SESSION['LAST_ACTIVITY'] = time();

        try{
            $dto = $container->make('binnDTO');
            $dto->binnacle_id = $_GET["homeId"];
            $binn_info = $container->make('binnService')->getInfo($dto);
            $with_iva = Utils::setIVAIfAmountIsNotNull($binn_info);
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unKnownEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["getBinnInfoEx"] = "No se logró obtener los "
                        ."datos de la bitácora seleccionada, posible "
                        ."corte de conexión a la base de datos";
        }finally{
            /*
            if(!empty($_SESSION["exceptions"]["unKnownEx"])){
                header("Location: ".base_url."home/?homeController=error&homeAction=index");
                exit;
            }

            if(!empty($_SESSION["exceptions"]["getBinnInfoEx"]) ||
                !empty($_SESSION["exceptions"]["entitiesEx"])){
                header("Location: ".base_url."home/?homeController=user&homeAction=binnaclesReport");
                exit;
            }
            */
            $result = '../views/adminLayouts/binnacleInfoCanvas.php';
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ["result" => $result, "binn_info" => (isset($binn_info)) ? $binn_info : null];
}

function mockEditBinnacle($container = null){
    if(!empty($_SESSION["identity"]) && 
        !empty($_SESSION["isAdmin"])  && 
        !empty($_GET["homeId"])){
        $_SESSION['LAST_ACTIVITY'] = time();    
            
        try{
            $dto = $container->make('binnDTO');
            $dto->binnacle_id = $_GET["homeId"];
            $binn_info = $container->make('binnService')->getInfo($dto);

            if($binn_info["Estatus"] === "en proceso" || $binn_info["Estatus"] === "falta confirmar")
                $usuarios = $container->make('usrService')->getAllInfo();
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unKnownEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["getBinnInfoEx"] = "No se logró obtener los "
                        ."datos de la bitácora seleccionada, posible "
                        ."corte de conexión a la base de datos";
        }finally{
            /*
            if(!empty($_SESSION["exceptions"]["unKnownEx"])){
                header("Location: ".base_url."home/?homeController=error&homeAction=index");
                exit;
            }

            if(!empty($_SESSION["exceptions"]["getBinnInfoEx"]) ||
                !empty($_SESSION["exceptions"]["entitiesEx"])){
                header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                exit;
            }
            */
            $result = '../views/adminLayouts/binnacleInfoCanvas.php';
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return [
        "result" => $result, 
        "binn_info" => (isset($binn_info)) ? $binn_info : null,
        "usuarios" => (isset($usuarios)) ? $usuarios : null
    ];
}

function mockBinninsertion($container = null){
    if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){

        $dto = $container->make('binnDTO');

        $dto->usuario_id = $_POST["userId"];
        $dto->contacto_id = $_POST["contactos"];
        $dto->actividad = (isset($_POST["tipoActividades"])) ? $_POST["tipoActividades"] : false;
        $dto->servicio = (!empty($_POST["servicio"])) ? $_POST["servicio"] : null;
        $dto->equipo_id = (!empty($_POST["equipos"])) ? $_POST["equipos"] : null;
        $errorArr = BinnacleVerifications::verifyingInsertion($dto);
        
        try{
            (sizeof($errorArr) === 0) ? $container->make('binnService')->insertInfo($dto) :
                $_SESSION["errors"] = $errorArr;
            
            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "La bitácora se a creado con éxito";
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["binnDataException"] = "Acción fallida, probable falta de conexión a la base de datos".$ex;
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=index";
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}

function mockUpdateBinnacleInfo($container = null){
    if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

        $dto = $container->make('binnDTO');
        $dto2 = $container->make('usrDTO'); 
        
        $dto->binnacle_id = $_POST["bitacoraId"];
        $dto->estatus = $_POST["estatus"];
        $dto2->admin_pwd = $_POST["adminContrasena"];
        $dto->inicio = $_POST["fechaInicio"];
        if(isset($_POST["usuario"]))
            $dto->usuario_id = $_POST["usuario"];
        if(isset($_POST["servicio"]))
            $dto->servicio = $_POST["servicio"];
        if(isset($_POST["precio"]))
            $dto->monto = $_POST["precio"];
        if(isset($_POST["seHizo"]))
            $dto->Actividades_realizadas = $_POST["seHizo"];
        if(isset($_POST["motivoCancelacion"]))
            $dto->cancel_desc = $_POST["motivoCancelacion"];
        if(isset($_POST["observaciones"]))
            $dto->observaciones = $_POST["observaciones"];
        if(isset($_POST["fechaFin"]))
            $dto->fin = $_POST["fechaFin"];
        $errorArr = BinnacleVerifications::verifyingUpdate($dto, $dto2);

        try{
            if(empty($errorArr["adminContrasena"]))
                $isRejection = Utils::setAdminVerification($dto2, $container->make('usrParticularSrv'));
            if(sizeof($isRejection) > 0)
                $errorArr = $isRejection;

            (sizeof($errorArr) === 0) ? $container->make('binnService')->updateInfo($dto) :
                $_SESSION["errors"] = $errorArr;
                
            if(empty($_SESSION["errors"])) 
                    $_SESSION["success"] = "Se logró editar la bitácora con éxito";
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["binnacleUpdateEx"] = "No se logró editar la bitácora, posible corte de conexión a la base de datos";
        }finally{
            /*
            if(!empty($_SESSION["exceptions"]) ||
                !empty($_SESSION["errors"])){
                header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                exit;
            }
            */

            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=editBinnacle&homeId=".$dto->binnacle_id;
            //exit;
        }
        
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}

function mockEnableOrDisableBinn($container = null){
    if(!empty($_SESSION["isAdmin"]) && sizeof($_POST) > 0){

        $dto = $container->make('binnDTO');

        $dto->binnacle_id = $_POST["bitacoraId"];
        $dto->visibilidad = $_POST["visibilidad"];
        $errorArr = SwitchVerification::verifyingSwitch($dto);
        $str_portion_one = ($dto->visibilidad === "DISABLED") ? 
        "desactivó":"activó";
        $str_portion_two = ($dto->visibilidad === "DISABLED") ? 
        "desactivar":"activar";
        
        try{
            (sizeof($errorArr) === 0) ?
                $container->make('binnService')->updateVisibility($dto) :
                $_SESSION["errors"] = $errorArr;

            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "Se "
                    .$str_portion_one." la bitácora con ID ".$dto->binnacle_id." con éxito";
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(AutomaticValueException $ex){
            $_SESSION["exceptions"]["visibilityEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["disableTypeEx"] = "No se logró ".$str_portion_two." la bitácora con ID ".$dto->binnacle_id.", posible corte de conexión a la base de datos";
        }finally{
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binnaclesReport";
            //exit;
        }
            
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit; 
    }

    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}
//--------------------------------------------------------------------------

//----------------------FOLLOWUPFORM CONTROLLER-----------------------------

function mockFollowupformIndex($container = null){
    if(!empty($_SESSION["identity"]) && !empty($_GET["id"])){
        $_SESSION['LAST_ACTIVITY'] = time();

        $dto = $container->make('binnDTO');

        try{
            $dto->binnacle_id = $_GET["id"];
            $dto->usuario_id = $_SESSION["identity"]["Id"];
            $info = $container->make('binnService')->getInfo($dto);
            Utils::isAuthorizedBinnacle($info);
            (!empty($info["Actividades_realizadas"])) ?
                    $info_verified = '../views/finishingLayouts/consentInfo.php' :
                    $info_verified = '../views/finishingLayouts/remindedfields.php';
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnknownInDataBaseException $ex){
            $_SESSION["exceptions"]["unKnownEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(UnauthorizedDataException $ex){
            $_SESSION["exceptions"]["unauthorizedEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["remindedOrConsentReportEx"] = "No se logró obtener "
                            ."la información necesaria para el seguimiento de "
                            ."bitácoras, se cortó la conexión a la base de datos.";                    
        }finally{
            /*            
            if(!empty($_SESSION["exceptions"])){
                header("Location: ".base_url."home/");
                exit;        
            }
            */            
            $result = (isset($info_verified)) ? $info_verified : "Location: http://localhost:8081/SOSv5/service-order-system/home/";                
        }
            
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return $result;
}

function mockTechsign(){
    
    if(!empty($_SESSION["identity"]) && !empty($_SESSION["formSession"]["dataSelectionForSigns"])){ 
        $_SESSION['LAST_ACTIVITY'] = time();

        $result[] = '../views/finishingLayouts/technicianCanvas.php';
        $result[] = '../views/finishingLayouts/absoluteElems.php';            
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }
    return $result;
}
function mockClientsign(){
    if(!empty($_SESSION["identity"]) && 
            !empty($_SESSION["formSession"]["dataSelectionForSigns"]["binnId"])){
        $_SESSION['LAST_ACTIVITY'] = time();

        $result[] = '../views/finishingLayouts/clientCanvas.php';
        $result[] = '../views/finishingLayouts/absoluteElems.php';
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }
    return $result;
}

function mockFollowupPartial($container = null){
    if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){

        $dto = $container->make('binnDTO');       
        
        $dto->binnacle_id = $_POST["id"];
        $dto->usuario_id = $_SESSION["identity"]["Id"];
        $dto->Actividades_realizadas = $_POST["seHizo"];
        $dto->observaciones = $_POST["observaciones"];
        $dto->inicio = $_POST["binnFecha"];
        $dto->estatus = $_POST["estatus"];                
        $errorArr = BinnacleVerifications::verifyingFollowUpPartial($dto);

        try{
            (sizeof($errorArr) === 0) ? $container->make('binnParticularSrv')->followUpPartial($dto) :
                $_SESSION["errors"] = $errorArr;                           
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["followupExeption"] = "No se actualizó la "
                            ."bitacora con Id: ".$dto->binnacle_id.
                            ", probable corte de conexión a la base de datos";                    
        }finally{
            /*
            if(!empty($_SESSION["exceptions"])){
                header("Location: ".base_url."home/?homeController=binnacle&homeAction=followuplist");
                exit;            
            }*/
            
            $result = "Location: http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=index&id=".
                $dto->binnacle_id;
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }
    
    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}

function mockResetActivitiesDescriptions($container = null){
    if(!empty($_SESSION["identity"]) && !empty($_GET["id"])){
        
        $dto = $container->make('binnDTO');        

        try{
            $dto->binnacle_id = $_GET["id"];
            $dto->usuario_id = $_SESSION["identity"]["Id"];
            $container->make('binnParticularSrv')->resetActivities($dto);
            $_SESSION["success"] = "Puedes actualizar las actividades después";                    
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(UnauthorizedDataException $ex){
            $_SESSION["exceptions"]["unauthorizedEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["resetActivitiesException"] = "No se pudo "
                            . "reiniciar las actividades en la bitacora con Id: "
                            .$dto->binnacle_id.", probable corte de conexión a la base de datos";                
        }finally{
            /*
            if(!empty($_SESSION["exceptions"])){
                header("Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=followuplist");
                exit;
            }*/
            
            $result = (!empty($_SESSION["exceptions"])) ? "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=followuplist" : 
                "Location: http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=index&id=".$dto->binnacle_id;
            //exit;
        }

    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }
    
    return $result;
}

function mockCancellingBinn($container = null){
    if(!empty($_SESSION["identity"]) && sizeof($_POST) > 0){

        $dto = $container->make('binnDTO');        
        
        $dto->binnacle_id = $_POST["cancelwithid"];
        $dto->usuario_id = $_SESSION["identity"]["Id"];
        $dto->estatus = $_POST["cancelestatus"];
        $dto->cancel_desc = $_POST["cancelacion"];    
        $errorArr = BinnacleVerifications::verifyingCancelDescription($dto);

        try{
            (sizeof($errorArr) === 0) ? $container->make('binnParticularSrv')->cancelBinnacle($dto) :
                $_SESSION["errors"] = $errorArr;
            
            if(empty($_SESSION["errors"]))
                $_SESSION["success"] = "La bitacora con "
                            ."Id: ".$dto->binnacle_id." Se canceló con éxito.";        
        }catch(WrongObjectException $ex){
            $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch(Exception $ex){
            $_SESSION["exceptions"]["followupCancelExeption"] = "No se pudo cancelar"
                            . " la bitacora con Id: ".$dto->binnacle_id." probable"
                            . " corte de conexión a la base de datos";
        }finally{
            /*
            if(!empty($_SESSION["errors"])){
                header("Location: ".base_url."finishing/?controller=followupform&action=index&id=".$dto->binnacle_id);
                exit;
            }*/

            $result = (sizeof($errorArr) > 0) ? "Location: http://localhost:8081/SOSv5/service-order-system/finishing/?controller=followupform&action=index&id=".$dto->binnacle_id : 
                "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=followuplist";
            //exit;   
        }
            
    }else{
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return ['result' => $result, "errorArr" => (isset($errorArr)) ? $errorArr : null];
}

function mockFinishbinnacle($container = null){
    if(!empty($_SESSION["identity"])                &&
        !empty($_SESSION["formSession"]["dataSelectionForSigns"])   &&     
        !empty($_SESSION["formSession"]["clientSignature"])         && 
        !empty($_SESSION["identity"]["Firma"])){

            $dto = $container->make('binnDTO');    
            
            $dto->binnacle_id = $_SESSION["formSession"]["dataSelectionForSigns"]["binnId"];
            $dto->usuario_id = $_SESSION["identity"]["Id"];
            $dto->firma_cliente = $_SESSION["formSession"]["clientSignature"];

            try {
                $container->make('binnParticularSrv')->finishBinnacle($dto);
                $_SESSION["success"] = "La bitacora con Id: "
                        .$dto->binnacle_id. " ha sido finalizada correctamente";
            }catch(WrongObjectException $ex){
                $_SESSION["exceptions"]["wrongObjectEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex) {
                $_SESSION["exceptions"]["binnFinishingException"] = "no se pudo finalizar la bitacora"
                        . " con Id: " .$dto->binnacle_id. " probable falta de conexión";
            }finally{
                if(!empty($_SESSION["exceptions"]))
                    unlink("uploads/firmas/".$_SESSION["formSession"]["clientSignature"]);

                //Utils::unsetFormSessions();

                $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=followuplist";
                //exit;
            }

    } else {
        $result = "Location: http://localhost:8081/SOSv5/service-order-system/home/";
        //exit;
    }

    return $result;
}
//--------------------------------------------------------------------------

//--------------------CONTROLLERS METHODS MOCKUPS---------------------------

//-------------------UTILS FEATURE METHODS MOCKUPS--------------------------

function mockReportPdfGenerator($container){
    
    if(!empty($_GET["homeAction"]) && $_GET["homeAction"] === "generateDevicesReport"){

        if(!empty($_SESSION["idSession"]["devicesReport_enterId"])){

            try{
                $dto = $container->make('dceDTO');
                $dto->empresa_id = $_SESSION["idSession"]["devicesReport_enterId"];
                $enter_devices = $container->make('dceService')->getChildrenByEnterprise($dto);

                if(sizeof($enter_devices) === 0)
                        throw new UnknownInDataBaseException("La empresa seleccionada no tiene dispositivos");

                $dto2 = $container->make('enterDTO');
                $dto2->enterprise_id = $_SESSION["idSession"]["devicesReport_enterId"];
                $enter_info = $container->make('enterService')->getInfo($dto2);
                $path = "/var/www/html/SOSv5/service-order-system/assets/img/logo.png";
                $data = file_get_contents($path);
                $logo_base64 = "data:image/png;base64,".base64_encode($data);

            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDataBaseEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["deviceReportException"] = "No se logró conseguir "
                                    ."la información para el reporte, posible corte "
                                    ."de conexión a la base de datos";
            }finally{
                /*
                if(!empty($_SESSION["exceptions"])){
                    header("Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=device&homeAction=devicesReport");
                    exit;
                }*/

                $result = (isset($_SESSION["exceptions"])) ? "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=device&homeAction=devicesReport" : 
                    '../views/adminLayouts/devicesPDF.php';
                //exit;
            }
        }
    }
    
    if(!empty($_SESSION["binnFilterSession"]) && !empty($_GET["homeAction"]) && !empty($_GET["homeId"])){
        
        if($_GET["homeAction"] === "generateBinnacleReport"){

            try{
                $dto = $container->make('binnDTO');
                $dto->binnacle_id = $_GET["homeId"];
                $binn_info = $container->make('binnService')->getInfo($dto);

                if(empty($binn_info))
                    throw new UnknownInDataBaseException("El id de la bitácora no existe en la base de datos");

                $logo_path = "/var/www/html/SOSv5/service-order-system/assets/img/logo.png";
                $logo_file = file_get_contents($logo_path);
                $logo_base64 = "data:image/png;base64,".base64_encode($logo_file);

                $without_img_path = "/var/www/html/SOSv5/service-order-system/assets/img/no-image-icon-23494.png";
                $without_img_file = file_get_contents($without_img_path);
                $no_img_base64 = "data:image/png;base64,".base64_encode($without_img_file);

                ($binn_info["Estatus"] !== 'en proceso') ?
                    $tech_sign_path = "/var/www/html/SOSv5/service-order-system/finishing/uploads/firmas/".$binn_info["Tecnico_firma"] : $tech_sign_path = null;
                ($binn_info["Estatus"] === 'finalizado') ?
                    $cli_sign_path = "/var/www/html/SOSv5/service-order-system/finishing/uploads/firmas/".$binn_info["Firma_cliente"] : $cli_sign_path = null;    
                
                if(!empty($tech_sign_path)){
                    (!is_file($tech_sign_path)) ?
                    $tech_base64 = $tech_base64 = null : "data:image/png;base64,".base64_encode(file_get_contents($tech_sign_path));
                }
                
                if(!empty($cli_sign_path)){
                    (!is_file($cli_sign_path)) ?
                    $cli_base64 = $cli_base64 = null : "data:image/png;base64,".base64_encode(file_get_contents($cli_sign_path));
                }
                $with_iva = Utils::setIVAIfAmountIsNotNull($binn_info);
                
            }catch(UnknownInDataBaseException $ex){
                $_SESSION["exceptions"]["unknownInDataBaseEx"] = $ex->getMessage();
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch(Exception $ex){
                $_SESSION["exceptions"]["getBinnInfoEx"] = "No se logró obtener los "
                                ."datos de la bitácora seleccionada, posible "
                                ."corte de conexión a la base de datos";
            }finally{
                /*
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                    exit;
                }*/

                $result = (isset($_SESSION["exceptions"])) ? "Location: http://localhost:8081/SOSv5/service-order-system/home/?homeController=binnacle&homeAction=binnaclesReport" :
                     '../views/adminLayouts/binnacleInfoCanvas.php';
                //exit;
            }
        }
    }

    return [
        "result" => $result,
        "logo_base64" => (isset($logo_base64)) ? $logo_base64 : null,
        "enter_devices" => (isset($enter_devices)) ? $enter_devices : null,
        "enter_info" => (isset($enter_info)) ? $enter_info : null,
        "binn_info" => (isset($binn_info)) ? $binn_info : null,
        "no_img_base64" => (isset($no_img_base64)) ? $no_img_base64 : null,
        "with_iva" => (isset($with_iva)) ? $with_iva : null
    ];
}

function mockAjaxProcedure($container){

    if(!empty($_SERVER['CONTENT_TYPE']) && strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false) {
        
        //$data = file_get_contents("php://input");
        $input = $_POST;//json_decode($data, true);

        if(!empty($input["number"])){
            $dto = $container->make('binnDTO');
            $dto->usuario_id = $_SESSION["identity"]["Id"];
            $_SESSION["jsondecoded"]["followUpNumKey"] = intval($input["number"]);

            try {
                $pagination_arr = $container->make('binnService')->getAllInfo(
                    $dto, 
                    $_SESSION["jsondecoded"]["followUpNumKey"],
                    null,
                    $_GET["homeAction"]
                    );
            } catch (Exception $ex) {
                $_SESSION["exceptions"]["followUpQueryEx"] = "Se generó un error al "
                            ."interactuar con la base de datos para la "
                            ."obtención de datos necesarios crear "
                            ."la paginación de seguimiento de bitácoras, "
                            ."lo más probable es que se haya cortado la "
                            ."conexión a la base de datos";
            }finally{
                /*
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/");
                    exit;
                }

                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($pagination_arr);
                exit;
                */

            }  
        }
        
        if(!empty($input["binnsFilterNumber"])){

            $_SESSION["jsondecoded"]["binnsReportNumKey"] = intval($input["binnsFilterNumber"]);
            
            try {    
                $pagination_arr = $container->make('binnService')->getAllInfo(
                    null,
                    $_SESSION["jsondecoded"]["binnsReportNumKey"],
                    $_SESSION["binnFilterSession"],
                    $_GET["homeAction"]
                );
            } catch (Exception $ex) {
                //Utils::unsetBinnFilterSession();
                $_SESSION["exceptions"]["binnsRowsPaginationEx"] = "Se generó un "
                            . "error interactuando con la base de datos "
                            . "en cuanto a la generación de paginación, posible falta de conexión";
            }finally{
                /*
                if(!empty($_SESSION["exceptions"])){
                    header("Location: " . base_url . "home/user/?homeController=binnacle&homeAction=binnaclesReport");
                    exit;
                }

                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($pagination_arr);
                exit;*/
            }  
        }
        
        if(!empty($input["enterIdFromBinnFilter"])){
            
            $enter_id = $input["enterIdFromBinnFilter"];

            try{
                $dto = $container->make('contDTO');
                $dto2 = $container->make('dceDTO');
                $dto->empresa_id = $enter_id;
                $dto2->empresa_id = $enter_id;
                $dces_arr = [
                    "enterContactsToBinnsFilter"=> 
                        $container->make('contService')->getChildrenByEnterForSelect($dto),
                    "enterDcesToBinnsFilter"    => 
                        $container->make('dceService')->getChildrenByEnterForSelect($dto2)
                ];
                $_SESSION["binnFilterSession"]["enterpriseRelatedContacts"] = 
                    $dces_arr["enterContactsToBinnsFilter"];
                $_SESSION["binnFilterSession"]["enterpriseRelatedDevices"] = 
                    $dces_arr["enterDcesToBinnsFilter"];
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch (Exception $ex) {
                $_SESSION["exceptions"]["dces_arrException"] = "Se generó un "
                            ."error interactuando con la base de datos "
                            ."en cuanto a la generación de las opciones de selección "
                            ."de dispositivos en el reporte de bitácoras, "
                            ."lo más probable es que se haya cortado la conexión a la base de datos";
            }finally{
                /*
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/?homeController=binnacle&homeAction=binnaclesReport");
                    exit;
                }

                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($dces_arr);
                exit;*/
            }
        }
        
        if(!empty($input["enterpriseId"])){
            
            $enter_id = $input["enterpriseId"];
            
            try{
                $dto = $container->make('enterDTO');
                $dto2 = $container->make('contDTO');
                $dto3 = $container->make('dceDTO');
                $dto->enterprise_id = $enter_id;
                $dto2->empresa_id = $enter_id;
                $dto3->empresa_id = $enter_id;
                $enterprise_arr = [
                    "entInfo"           => 
                        $container->make('enterService')->getInfo($dto),
                    "enterpriseContacts"=> 
                        $container->make('contService')->getChildrenByEnterForSelect($dto2),
                    "enterpriseDevices" => 
                        $container->make('dceService')->getChildrenByEnterForSelect($dto3)
                ];
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch (Exception $ex) {
                $_SESSION["exceptions"]["enterpriseArrayException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la generación de datos automaticos de una empresa "
                        ."en el formulario de registro de bitácoras, "
                        ."lo más probable es que se haya cortado la conexión a la base de datos";
            }finally{
                /*
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/");
                    exit;
                }

                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($enterprise_arr);
                exit;*/
            }
        }
        
        if(!empty($input["newContactEnterId"])){
            
            $enter_id = $input["newContactEnterId"];

            try{
                $dto = $container->make('enterDTO');
                $dto->enterprise_id = $enter_id;
                $enterprise_arr = [
                    "entInfoForContactForm" => $container->make('enterService')->getInfo($dto)
                ];
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch (Exception $ex) {
                $_SESSION["exceptions"]["enterpriseArrayException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la generación de datos automaticos de una empresa "
                        ."en el formulario de registro de contactos, "
                        ."lo más probable es que se haya cortado la conexión a la base de datos";
            }finally{
                /*
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/");
                    exit;
                }

                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($enterprise_arr);
                exit;*/
            }
        }
        
        if(!empty($input["deviceId"])){
            
            $device_id = $input["deviceId"];

            try{
                $dto = $container->make('dceDTO');
                $dto->device_id = $device_id;
                $device_arr = $container->make('dceService')->getChild($dto);
            }catch(EntityException $ex){
                $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
            }catch (Exception $ex) {
                $_SESSION["exceptions"]["deviceArrayException"] = "Se generó un "
                        ."error interactuando con la base de datos "
                        ."en cuanto a la generación de datos automaticos de dispositivos "
                        ."en el formulario de registro de bitácoras, "
                        ."lo más probable es que se haya cortado la conexión a la base de datos";
                header("Location: ".base_url."home/");
                exit;
            }finally{
                /*
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/");
                    exit;
                }

                header('Content-Type: application/json; charset=utf-8');
                echo json_encode($device_arr);
                exit;*/
            }
        }            
    }

    return [
        "pagination_arr" => (isset($pagination_arr)) ? $pagination_arr : null,
        "enterprise_arr" => (isset($enterprise_arr)) ? $enterprise_arr : null,
        "device_arr" => (isset($device_arr)) ? $device_arr : null
    ];
}

function mockUpdateUserWithSignature($container){
    
    if(!empty($_SESSION["formSession"]["techSignature"])){

        try {
            $dto = $container->make('usrDTO');

            (!empty($_SESSION["idSession"]["userSign_userId"])) ? 
            $dto->user_id = $_SESSION["idSession"]["userSign_userId"] : 
            $dto->user_id = $_SESSION["identity"]["Id"];
            
            $dto->firma = $_SESSION["formSession"]["techSignature"];
            $container->make('usrSignService')->insertSignature($dto);

        }catch(EntityException $ex){
            $_SESSION["exceptions"]["entitiesEx"] = $ex->getMessage();
        }catch (Exception $ex) {
            
            $_SESSION["exceptions"]["techSignInsertException"] = "No se logró guardar "
                    ."la firma del técnico en la base de datos, se cortó "
                    ."la conexión a la base de datos";
            if(!unlink("uploads/firmas/".$_SESSION["formSession"]["techSignature"])){
                    $_SESSION["exceptions"]["unlinkTechSignEx"] = "La supuesta firma del técnico no se encontró en la aplicación web";
            }
            //Utils::unsetFormSessions();
        }finally{
            /*
            if(!empty($_SESSION["exceptions"])){
                header("Location: ".base_url."home/");
                exit;
            }*/
        
            try{
                if(!empty($_SESSION["idSession"]["userSign_userId"])){
                    if($_SESSION["idSession"]["userSign_userId"] === 
                        $_SESSION["identity"]["Id"]) 
                            $_SESSION["identity"] = $container->make('usrService')->getInfo($dto);
                }else{
                    $_SESSION["identity"] = $container->make('usrService')->getInfo($dto);
                }
            } catch (Exception $ex) {
                $_SESSION["exceptions"]["identitySessionUpdateEx"] = "No se logró actualizar la "
                        ."sesión de la información del usuario, posible corte de "
                        ."conexión a la base de datos, se recomienda no generar "
                        ."firma del usuario en una bitácora una vez establecido "
                        ."conexión a la base de datos, cierre sesión y vuelva entrar "
                        ."para tener una sesión de datos de usuario correcta";
                //Utils::unsetFormSessions();
            }finally{
                /*
                if(!empty($_SESSION["exceptions"])){
                    header("Location: ".base_url."home/");
                    exit;
                }*/

                if((!empty($_SERVER['HTTP_X_REQUESTED_WITH'])                           && 
                    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') &&
                    empty($_SESSION["clientSignature"])){
                    $result = "Firma de ".$_SESSION["formSession"]["dataSelectionForSigns"]["userName"]." se ha guardado con éxito";
                    //exit;
                }
            }
        }  
    }

    return $result;
}
//-------------------UTILS FEATURE METHODS MOCKUPS-------------------------- 