<?php

namespace pizzashop\auth\api\domain\dto;

class CredentialsDTO
{
    public string $email;
    public string $password;
    public string $username;

    public function __construct($email, $password, $username="")
    {
        $this->email = $email;
        $this->password = $password;
        $this->username = $username;
    }

}