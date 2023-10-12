<?php

namespace pizzashop\auth\api\domain\dto;

class TokenDTO
{
    public string $active;
    public string $activation_token;
    public string $activation_token_expiration_date;
    public string $refresh_token;
    public string $refresh_token_expiration_date;
    public string $reset_passwd_token;
    public string $reset_passwd_token_expiration_date;
}