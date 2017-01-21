<?php

/** Local Namespace **/
namespace NxSys\Applications\Atlas\Web\Controllers;

use Silex\Application as WebApp;

use Symfony\Component\HttpFoundation as SfHttp;

class Home
{
    
    #code
    public function index(WebApp $oApp)
    {
        $sContent=$oApp['twig']->render('Home.index.twig.html', []);
        return new sfHttp\Response($sContent);
    }
}
