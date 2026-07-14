<?php

class FinishingUtils{

    public static function setUtils($container){
        Utils::putSessionWithVerify();
        Utils::sessionLifetime();
        Utils::saveSignaturesFiles();
        Utils::updateUserWithSignature(
            $container->make('usrDTO'),
            $container->make('usrSignService'),
            $container->make('usrService')
        );
        Utils::setDataSelectionForSigns();
    }
}