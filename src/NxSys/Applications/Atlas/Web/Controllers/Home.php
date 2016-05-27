<?php

/** Local Namespace **/
namespace NxSys\Applications\Atlas\Web\Controllers;
use Symfony\Component\HttpFoundation as SfHttp;

class Home
{
    #code
    public function index()
    {
        return new sfHttp\Response('Hello world, from '.APP_NAME);
    }
}
