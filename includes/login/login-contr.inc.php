<?php

declare(strict_types=1);

function is_inputs_empty(string $email,string $pwd){
    if (empty($email) || empty($pwd)) {
        return true;
    }else{
        return false;
    }
}


function is_login_idn_wrong(array|bool $res){
    if (!$res) {
        return true;
    } else {
        return false;
    }
    
}

function is_password_wrong(string $pwd, string $hachedPwd){
    if (!password_verify($pwd, $hachedPwd)) {
        return true;
    } else {
        return false;
    }
    
}