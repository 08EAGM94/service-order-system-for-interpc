<?php

interface IBinnacleService{
    public function getService($dto);
    public function followUpPartial($dto);
    public function resetActivities($dto);
    public function cancelBinnacle($dto);
    public function finishBinnacle($dto);
}