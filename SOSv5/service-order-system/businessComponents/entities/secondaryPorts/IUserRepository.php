<?php

interface IUserRepository{
    public function login($entity);
    public function adminPwdConfirmation($entity);
}