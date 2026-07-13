<?php

class FinishingContainerFactory{
    public static function build(){
        $builder = new DI\ContainerBuilder();

        $builder->addDefinitions([
            'ErrorController' => DI\factory(function(){return new ErrorController();}),
            
            'binnDTO' => DI\factory(function(){return new BitacorasDTO();}),
            'usrDTO' => DI\factory(function(){return new UsuariosDTO();}),

            'binnEntityMapper' => DI\factory(function(){return new BinnacleDTOToEntityMapper();}),
            'usrEntityMapper' => DI\factory(function(){return new UserDTOToEntityMapper();}),
            
            'binnModelMapper' => DI\factory(function(){return new BinnacleToModelMapper();}),
            'usrModelMapper' => DI\factory(function(){return new UserToModelMapper();}),

            'SOSv6Database' => DI\factory(function(){return new DataBaseMssql();}),

            'binnQueries' => DI\factory(function($c){
                $db = $c->get('SOSv6Database');
                return new BinnacleQueries($db);
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
            'binnParticularSrv' => DI\factory(function($c){
                $repository = $c->make('binnRepository');
                $mapperEntity = $c->make('binnEntityMapper');
                return new BinnacleService($repository, $mapperEntity);
            }),
            'usrService' => DI\factory(function($c){
                $repository = $c->make('usrRepository');
                $mapperEntity = $c->make('usrEntityMapper');
                $mapperModel = $c->make('usrModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel);
            }),
            'usrSignService' => DI\factory(function($c){
                $repository = $c->make('usrRepository');
                $mapperEntity = $c->make('usrEntityMapper');
                return new SignatureService($repository, $mapperEntity);
            }),


            'FollowupformController' => DI\factory(function($c){
                $binnDTO = $c->make('binnDTO'); 
                $binnService = $c->make('binnService'); 
                $binnParticularSrv = $c->make('binnParticularSrv');
                return new FollowupformController($binnDTO, $binnService, $binnParticularSrv);
            })
        ]);

        return $builder->build();
    }
}