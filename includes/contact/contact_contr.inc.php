<?php

declare(strict_types=1);

function is_inputs_empty(...$inputs)
{
    foreach ($inputs as $input) {
        if (empty($input)) {
            return true;
        }
    }

    return false;
}

function is_name_invalid(string $name)
{
    $regex = '/^[a-zA-Z\x{00C0}-\x{00FF}\s]{1,30}$/u';
    return !preg_match($regex, $name) || strlen($name) > 30;
}

function is_email_invalid(string $email)
{
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_subject_invalid(string $sub)
{
    return strlen($sub) > 200;
}

function is_message_invalid(string $msg)
{
    return strlen($msg) > 65535;
}
function create_msg(object $pdo, string $name, string $email, string $subject, string $message)
{
    set_msg($pdo, $name, $email, $subject, $message);
}
