<?php

namespace pizzashop\auth\api\domain\dto;

class TokenDTO
{
    public $access_token;
    public $refresh_token;
    public $activation_token;

    /**
     * @param $access_token
     * @param $refresh_token
     */
    public function __construct($access_token = null, $refresh_token = null)
    {
        $this->access_token = $access_token;
        $this->refresh_token = $refresh_token;
    }
}