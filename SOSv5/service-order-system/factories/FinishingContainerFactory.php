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

            'binnQueries' => DI\factory(function(){return new BinnacleQueries();}),
            'usrQueries' => DI\factory(function(){return new UserQueries();}),

            'binnRepository' => DI\factory(function($c){
                $queries = $c->get('binnQueries');
                $mapperModel = $c->get('binnModelMapper');
                return new BinnacleRepository($queries, $mapperModel);
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
            'binnParticularSrv' => DI\factory(function($c){
                $repository = $c->get('binnRepository');
                $mapperEntity = $c->get('binnEntityMapper');
                return new BinnacleService($repository, $mapperEntity);
            }),
            'usrService' => DI\factory(function($c){
                $repository = $c->get('usrRepository');
                $mapperEntity = $c->get('usrEntityMapper');
                $mapperModel = $c->get('usrModelMapper');
                return new CommonService($repository, $mapperEntity, $mapperModel);
            }),
            'usrSignService' => DI\factory(function($c){
                $repository = $c->get('usrRepository');
                $mapperEntity = $c->get('usrEntityMapper');
                return new SignatureService($repository, $mapperEntity);
            }),


            'FollowupformController' => DI\factory(function($c){
                $binnDTO = $c->get('binnDTO'); 
                $binnService = $c->get('binnService'); 
                $binnParticularSrv = $c->get('binnParticularSrv');
                return new FollowupformController($binnDTO, $binnService, $binnParticularSrv);
            })
        ]);

        return $builder->build();
    }
}