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
        // @todo __CLASS__.__METHOD__.'twig.html
        $sContent=$oApp['twig']->render('Home.index.twig.html',
                                        ['name' => APP_NAME]);
        return new sfHttp\Response($sContent);
    }
}
