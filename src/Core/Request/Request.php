<?php

namespace App\Core\Request;


class Request implements IRequest
{
    private $post;
    private $get;
    private $cookie;

    public function __construct(array $post, array $get, array $cookie)
    {
        $this->post = $post;
        $this->get = $get;
        $this->cookie = $cookie;
    }

    public function getPost(): RequestData
    {
        return new RequestData($this->post);
    }

    public function getGet(): RequestData
    {
        return new RequestData($this->get);
    }

    public function getCookie(): RequestData
    {
        return new RequestData($this->cookie);
    }
}
