<?php

class HomeUtils{
    
    public static function setUtils($container){
        Utils::putSessionWithVerify();
        Utils::sessionLifetime();
        Utils::reportPdfGenerator(
            $container->make('dceDTO'),
            $container->make('enterDTO'),
            $container->make('binnDTO'),
            $container->make('dceService'),
            $container->make('enterService'),
            $container->make('binnService')
        );
        Utils::ajaxProcedure(
            $container->make('contDTO'),
            $container->make('dceDTO'),
            $container->make('enterDTO'),
            $container->make('binnDTO'),
            $container->make('contService'),
            $container->make('dceService'),
            $container->make('enterService'),
            $container->make('binnService')
        );
    }
}