<?php
namespace app\index\controller;

use think\Controller;

class Comm extends Controller
{
    public function index()
    {
        // return "load success!";
        return $this->fetch();
    }

    public function top()
    {
        return $this->fetch();
    }

    public function main()
    {
        return $this->fetch();
    }

    public function footer()
    {
        return $this->fetch();
    }

}