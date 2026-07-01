<?php

class FinishingUtils{

    public static function setUtils($container){
        Utils::putSessionWithVerify();
        Utils::sessionLifetime();
        Utils::saveSignaturesFiles();
        Utils::updateUserWithSignature(
            $container->get('usrDTO'),
            $container->get('usrSignService'),
            $container->get('usrService')
        );
        Utils::setDataSelectionForSigns();
    }
}