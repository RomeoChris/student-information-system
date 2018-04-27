<?php

namespace App\Core\Request;


interface IRequest
{
    public function getPost(): RequestData;
    public function getGet(): RequestData;
    public function getCookie(): RequestData;
}
