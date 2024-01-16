<?php

namespace pizzashop\auth\api\domain\dto;

class CredentialsDTO
{
    public string $email;
    public string $password;

    public function __construct($email, $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

}