<?php

interface IUserService{
    public function login($dto);
    public function adminPwdConfirmation($dto);  
}