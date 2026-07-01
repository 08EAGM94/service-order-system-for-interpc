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

            'pagination' => DI\factory(function(){return new Zebra_Pagination();}),

            'binnQueries' => DI\factory(function($c){
                $pagination = $c->get('pagination');
                return new BinnacleQueries($pagination);
            }),
            'contQueries' => DI\factory(function(){return new ContactQueries();}),
            'dceQueries' => DI\factory(function(){return new DeviceQueries();}),
            'enterQueries' => DI\factory(function(){return new EnterpriseQueries();}),
            'typQueries' => DI\factory(function(){return new TypeQueries();}),
            'usrQueries' => DI\factory(function(){return new UserQueries();}),

            'binnRepository' => DI\factory(function($c){
                $queries = $c->get('binnQueries');
                $mapperModel = $c->get('binnModelMapper');
                return new BinnacleRepository($queries, $mapperModel);
            }),
            'contRepository' => DI\factory(function($c){
                $queries = $c->get('contQueries');
                $mapperModel = $c->get('contModelMapper');
                return new ContactRepository($queries, $mapperModel);
            }),
            'dceRepository' => DI\factory(function($c){
                $queries = $c->get('dceQueries');
                $mapperModel = $c->get('dceModelMapper');
                return new DeviceRepository($queries, $mapperModel);
            }),
            'enterRepository' => DI\factory(function($c){
                $queries = $c->get('enterQueries');
                $enterMapperModel = $c->get('enterModelMapper');
                $contMapperModel = $c->get('contModelMapper');
                return new EnterpriseRepository($queries, $enterMapperModel, $contMapperModel);
            }),
            'typRepository' => DI\factory(function($c){
                $queries = $c->get('typQueries');
                $mapperModel = $c->get('typModelMapper');
                return new TypeRepository($queries, $mapperModel);
            }),
            'usrRepository' => DI\factory(function($c){
                $queries = $c->get('usrQueries');
                $mapperModel = $c->get('usrModelMapper');
                return new UserRepository($queries, $mapperModel);
            }),


            'binnService' => DI\factory(function($c){
                $repository = $c->get('binnRepository');
                $mapperEntity = $c->get('binnEntityMapper');
                $mapperModel = $c->get('binnModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel);
            }),
            'enterService' => DI\factory(function($c){
                $repository = $c->get('enterRepository');
                $mapperEntity = $c->get('enterEntityMapper');
                $mapperEntity2 = $c->get('contEntityMapper');
                $mapperModel = $c->get('enterModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel, $mapperEntity2);
            }),
            'typService' => DI\factory(function($c){
                $repository = $c->get('typRepository');
                $mapperEntity = $c->get('typEntityMapper');
                $mapperModel = $c->get('typModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel);
            }),
            'usrService' => DI\factory(function($c){
                $repository = $c->get('usrRepository');
                $mapperEntity = $c->get('usrEntityMapper');
                $mapperModel = $c->get('usrModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel);
            }),
            'contService' => DI\factory(function($c){
                $repository = $c->get('contRepository');
                $mapperEntity = $c->get('contEntityMapper');
                $mapperModel = $c->get('contModelMapper');
                return new EnterpriseChildrenService($repository, $mapperEntity, $mapperModel);
            }),
            'dceService' => DI\factory(function($c){
                $repository = $c->get('dceRepository');
                $mapperEntity = $c->get('dceEntityMapper');
                $mapperModel = $c->get('dceModelMapper');
                return new EnterpriseChildrenService($repository, $mapperEntity, $mapperModel);
            }),


            'contSelectSrv' => DI\factory(function($c){
                $repository = $c->get('contRepository');
                return new SelectService($repository);
            }),
            'enterSelectSrv' => DI\factory(function($c){
                $repository = $c->get('enterRepository');
                return new SelectService($repository);
            }),
            'typSelectSrv' => DI\factory(function($c){
                $repository = $c->get('typRepository');
                return new SelectService($repository);
            }),


            'usrSignService' => DI\factory(function($c){
                $repository = $c->get('usrRepository');
                $mapperEntity = $c->get('usrEntityMapper');
                return new SignatureService($repository, $mapperEntity);
            }),
            'usrParticularSrv' => DI\factory(function($c){
                $repository = $c->get('usrRepository');
                $mapperEntity = $c->get('usrEntityMapper');
                return new UserService($repository, $mapperEntity);
            }),
            'binnParticularSrv' => DI\factory(function($c){
                $repository = $c->get('binnRepository');
                $mapperEntity = $c->get('binnEntityMapper');
                return new BinnacleService($repository, $mapperEntity);
            }),


            'BinnacleController' => DI\factory(function($c){
                $binnDTO = $c->get('binnDTO');
                $usrDTO = $c->get('usrDTO');
                $enterSelectSrv = $c->get('enterSelectSrv');
                $binnService = $c->get('binnService');
                $usrService = $c->get('usrService');
                $usrParticularSrv = $c->get('usrParticularSrv');
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
                $contDTO = $c->get('contDTO');
                $usrDTO = $c->get('usrDTO');
                $enterDTO = $c->get('enterDTO');
                $enterSelectSrv = $c->get('enterSelectSrv');
                $contService = $c->get('contService');
                $enterService = $c->get('enterService');
                $usrParticularSrv = $c->get('usrParticularSrv');
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
                $enterDTO = $c->get('enterDTO');
                $dceDTO = $c->get('dceDTO');
                $usrDTO = $c->get('usrDTO');
                $typService = $c->get('typService');
                $typSelectSrv = $c->get('typSelectSrv');
                $enterSelectSrv = $c->get('enterSelectSrv');
                $enterService = $c->get('enterService');
                $dceService = $c->get('dceService');
                $usrParticularSrv = $c->get('usrParticularSrv');
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
                $contDTO = $c->get('contDTO');
                $enterDTO = $c->get('enterDTO');
                $usrDTO = $c->get('usrDTO');
                $contService = $c->get('contService');
                $enterService = $c->get('enterService');
                $usrParticularSrv = $c->get('usrParticularSrv');
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
                $typDTO = $c->get('typDTO');
                $usrDTO = $c->get('usrDTO');
                $typService = $c->get('typService');
                $usrParticularSrv = $c->get('usrParticularSrv');
                return new TypeController(
                    $typDTO, 
                    $usrDTO, 
                    $typService, 
                    $usrParticularSrv
                );
            }),
            'UserController' => DI\factory(function($c){
                $usrDTO = $c->get('usrDTO');
                $usrService = $c->get('usrService');
                $usrSignService = $c->get('usrSignService');
                $usrParticularSrv = $c->get('usrParticularSrv');
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