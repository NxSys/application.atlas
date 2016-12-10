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
        $oVCSUtilSvc=$oApp['atlas.vcs'];
        if(!count($oVCSUtilSvc->repos))
        {
            //@todo
            throw new Exception;
        }
        $oVCSUtilSvc->cacheDir('Atlas');

        $oCurrRepo=$oVCSUtilSvc->repos['Atlas'];
        $oFileNode=$oCurrRepo->files->find('/trunk/src/NxSys/Applications/Atlas/Application.php');
        
        $aPage=[];
        $aPage['file_contents']=$oFileNode->getContents();
        $aPage['file_ext']=$oFileNode->getFileExtenstion();
        
        $sContent=$oApp['twig']->render('Home.index.twig.html', $aPage);
        return new sfHttp\Response($sContent);
    }
}
