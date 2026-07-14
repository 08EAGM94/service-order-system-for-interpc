<?php

interface IBinnacleRepository{
    public function followUpPartial($entity);
    public function resetActivities($entity);
    public function cancelBinnacle($entity);
    public function finishBinnacle($entity);
}