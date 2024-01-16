<?php

namespace pizzashop\auth\api\domain\dto;

class UserDTO
{
    public string $username;
    public string $email;
    public string $refresh_token;

    /**
     * @param string $username
     * @param string $email
     * @param string $refresh_token
     */
    public function __construct(string $username, string $email, string $refresh_token = "")
    {
        $this->username = $username;
        $this->email = $email;
        $this->refresh_token = $refresh_token;
    }


}