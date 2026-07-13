<?php

//use DI\ContainerBuilder;
//use function DI\factory;
//use function DI\get;
class HomeContainerFactory{
    public static function build(){
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

            'binnEntityMapper' => DI\factory(function(){return new BinnacleDTOToEntityMapper();}),
            'contEntityMapper' => DI\factory(function(){return new ContactDTOToEntityMapper();}),
            'dceEntityMapper' => DI\factory(function(){return new DeviceDTOToEntityMapper();}),
            'enterEntityMapper' => DI\factory(function(){return new EnterpriseDTOToEntityMapper();}),
            'typEntityMapper' => DI\factory(function(){return new TypeDTOToEntityMapper();}),
            'usrEntityMapper' => DI\factory(function(){return new UserDTOToEntityMapper();}),

            'binnModelMapper' => DI\factory(function(){return new BinnacleToModelMapper();}),
            'contModelMapper' => DI\factory(function(){return new ContactToModelMapper();}),
            'dceModelMapper' => DI\factory(function(){return new DeviceToModelMapper();}),
            'enterModelMapper' => DI\factory(function(){return new EnterpriseToModelMapper();}),
            'typModelMapper' => DI\factory(function(){return new TypeToModelMapper();}),
            'usrModelMapper' => DI\factory(function(){return new UserToModelMapper();}),

            'SOSv6Database' => DI\factory(function(){return new DataBaseMssql();}),
            'pagination' => DI\factory(function(){return new Zebra_Pagination();}),

            'binnQueries' => DI\factory(function($c){
                $db = $c->get('SOSv6Database');
                $pagination = $c->get('pagination');
                return new BinnacleQueries($db, $pagination);
            }),
            'contQueries' => DI\factory(function($c){
                $db = $c->get('SOSv6Database');
                return new ContactQueries($db);
            }),
            'dceQueries' => DI\factory(function($c){
                $db = $c->get('SOSv6Database');
                return new DeviceQueries($db);
            }),
            'enterQueries' => DI\factory(function($c){
                $db = $c->get('SOSv6Database');
                return new EnterpriseQueries($db);
            }),
            'typQueries' => DI\factory(function($c){
                $db = $c->get('SOSv6Database');
                return new TypeQueries($db);
            }),
            'usrQueries' => DI\factory(function($c){
                $db = $c->get('SOSv6Database');
                return new UserQueries($db);
            }),

            'binnRepository' => DI\factory(function($c){
                $queries = $c->make('binnQueries');
                $mapperModel = $c->make('binnModelMapper');
                return new BinnacleRepository($queries, $mapperModel);
            }),
            'contRepository' => DI\factory(function($c){
                $queries = $c->make('contQueries');
                $mapperModel = $c->make('contModelMapper');
                return new ContactRepository($queries, $mapperModel);
            }),
            'dceRepository' => DI\factory(function($c){
                $queries = $c->make('dceQueries');
                $mapperModel = $c->make('dceModelMapper');
                return new DeviceRepository($queries, $mapperModel);
            }),
            'enterRepository' => DI\factory(function($c){
                $queries = $c->make('enterQueries');
                $enterMapperModel = $c->make('enterModelMapper');
                $contMapperModel = $c->make('contModelMapper');
                return new EnterpriseRepository($queries, $enterMapperModel, $contMapperModel);
            }),
            'typRepository' => DI\factory(function($c){
                $queries = $c->make('typQueries');
                $mapperModel = $c->make('typModelMapper');
                return new TypeRepository($queries, $mapperModel);
            }),
            'usrRepository' => DI\factory(function($c){
                $queries = $c->make('usrQueries');
                $mapperModel = $c->make('usrModelMapper');
                return new UserRepository($queries, $mapperModel);
            }),


            'binnService' => DI\factory(function($c){
                $repository = $c->make('binnRepository');
                $mapperEntity = $c->make('binnEntityMapper');
                $mapperModel = $c->make('binnModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel);
            }),
            'enterService' => DI\factory(function($c){
                $repository = $c->make('enterRepository');
                $mapperEntity = $c->make('enterEntityMapper');
                $mapperEntity2 = $c->make('contEntityMapper');
                $mapperModel = $c->make('enterModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel, $mapperEntity2);
            }),
            'typService' => DI\factory(function($c){
                $repository = $c->make('typRepository');
                $mapperEntity = $c->make('typEntityMapper');
                $mapperModel = $c->make('typModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel);
            }),
            'usrService' => DI\factory(function($c){
                $repository = $c->make('usrRepository');
                $mapperEntity = $c->make('usrEntityMapper');
                $mapperModel = $c->make('usrModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel);
            }),
            'contService' => DI\factory(function($c){
                $repository = $c->make('contRepository');
                $mapperEntity = $c->make('contEntityMapper');
                $mapperModel = $c->make('contModelMapper');
                return new EnterpriseChildrenService($repository, $mapperEntity, $mapperModel);
            }),
            'dceService' => DI\factory(function($c){
                $repository = $c->make('dceRepository');
                $mapperEntity = $c->make('dceEntityMapper');
                $mapperModel = $c->make('dceModelMapper');
                return new EnterpriseChildrenService($repository, $mapperEntity, $mapperModel);
            }),


            'contSelectSrv' => DI\factory(function($c){
                $repository = $c->make('contRepository');
                return new SelectService($repository);
            }),
            'enterSelectSrv' => DI\factory(function($c){
                $repository = $c->make('enterRepository');
                return new SelectService($repository);
            }),
            'typSelectSrv' => DI\factory(function($c){
                $repository = $c->make('typRepository');
                return new SelectService($repository);
            }),


            'usrSignService' => DI\factory(function($c){
                $repository = $c->make('usrRepository');
                $mapperEntity = $c->make('usrEntityMapper');
                return new SignatureService($repository, $mapperEntity);
            }),
            'usrParticularSrv' => DI\factory(function($c){
                $repository = $c->make('usrRepository');
                $mapperEntity = $c->make('usrEntityMapper');
                return new UserService($repository, $mapperEntity);
            }),
            'binnParticularSrv' => DI\factory(function($c){
                $repository = $c->make('binnRepository');
                $mapperEntity = $c->make('binnEntityMapper');
                return new BinnacleService($repository, $mapperEntity);
            }),


            'BinnacleController' => DI\factory(function($c){
                $binnDTO = $c->make('binnDTO');
                $usrDTO = $c->make('usrDTO');
                $enterSelectSrv = $c->make('enterSelectSrv');
                $binnService = $c->make('binnService');
                $usrService = $c->make('usrService');
                $usrParticularSrv = $c->make('usrParticularSrv');
                return new BinnacleController(
                    $binnDTO,
                    $usrDTO,
                    $enterSelectSrv,
                    $binnService,
                    $usrService,
                    $usrParticularSrv
                );
            }),
            'ContactController' => DI\factory(function($c){
                $contDTO = $c->make('contDTO');
                $usrDTO = $c->make('usrDTO');
                $enterDTO = $c->make('enterDTO');
                $enterSelectSrv = $c->make('enterSelectSrv');
                $contService = $c->make('contService');
                $enterService = $c->make('enterService');
                $usrParticularSrv = $c->make('usrParticularSrv');
                return new ContactController(
                    $contDTO, 
                    $usrDTO, 
                    $enterDTO, 
                    $enterSelectSrv, 
                    $contService, 
                    $enterService, 
                    $usrParticularSrv
                );
            }),
            'DeviceController' => DI\factory(function($c){
                $enterDTO = $c->make('enterDTO');
                $dceDTO = $c->make('dceDTO');
                $usrDTO = $c->make('usrDTO');
                $typService = $c->make('typService');
                $typSelectSrv = $c->make('typSelectSrv');
                $enterSelectSrv = $c->make('enterSelectSrv');
                $enterService = $c->make('enterService');
                $dceService = $c->make('dceService');
                $usrParticularSrv = $c->make('usrParticularSrv');
                return new DeviceController(
                    $enterDTO, 
                    $dceDTO, 
                    $usrDTO, 
                    $typService, 
                    $typSelectSrv, 
                    $enterSelectSrv, 
                    $enterService, 
                    $dceService, 
                    $usrParticularSrv
                );
            }),
            'EnterpriseController' => DI\factory(function($c){
                $contDTO = $c->make('contDTO');
                $enterDTO = $c->make('enterDTO');
                $usrDTO = $c->make('usrDTO');
                $contService = $c->make('contService');
                $enterService = $c->make('enterService');
                $usrParticularSrv = $c->make('usrParticularSrv');
                return new EnterpriseController(
                    $contDTO, 
                    $enterDTO, 
                    $usrDTO, 
                    $contService, 
                    $enterService, 
                    $usrParticularSrv
                );
            }),
            'TypeController' => DI\factory(function($c){
                $typDTO = $c->make('typDTO');
                $usrDTO = $c->make('usrDTO');
                $typService = $c->make('typService');
                $usrParticularSrv = $c->make('usrParticularSrv');
                return new TypeController(
                    $typDTO, 
                    $usrDTO, 
                    $typService, 
                    $usrParticularSrv
                );
            }),
            'UserController' => DI\factory(function($c){
                $usrDTO = $c->make('usrDTO');
                $usrService = $c->make('usrService');
                $usrSignService = $c->make('usrSignService');
                $usrParticularSrv = $c->make('usrParticularSrv');
                return new UserController(
                    $usrDTO, 
                    $usrService, 
                    $usrSignService, 
                    $usrParticularSrv
                );
            })
        ]);

        return $builder->build();
    }
}