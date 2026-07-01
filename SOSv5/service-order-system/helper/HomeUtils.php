<?php

class HomeUtils{
    
    public static function setUtils($container){
        Utils::putSessionWithVerify();
        Utils::sessionLifetime();
        Utils::reportPdfGenerator(
            $container->get('dceDTO'),
            $container->get('enterDTO'),
            $container->get('binnDTO'),
            $container->get('dceService'),
            $container->get('enterService'),
            $container->get('binnService')
        );
        Utils::ajaxProcedure(
            $container->get('contDTO'),
            $container->get('dceDTO'),
            $container->get('enterDTO'),
            $container->get('binnDTO'),
            $container->get('contService'),
            $container->get('dceService'),
            $container->get('enterService'),
            $container->get('binnService')
        );
    }
}