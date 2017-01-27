<?php

/** Local Namespace **/
namespace NxSys\Applications\Atlas\Web\Controllers\Components;

use Silex\Application as WebApp;

use Symfony\Component\HttpFoundation as SfHttp;

class Editor
{
    #code
    public function view(WebApp $oApp)
    {
        // @todo __CLASS__.__METHOD__.'twig.html
        $oVCSUtilSvc=$oApp['atlas.vcs'];
        if(!count($oVCSUtilSvc->repos))
        {
            //@todo
            throw new RuntimeException;
        }
        $oVCSUtilSvc->cacheDir('Atlas');

        $oCurrRepo=$oVCSUtilSvc->repos['Atlas'];
        $oFileNode=$oCurrRepo->files->find('/trunk/src/NxSys/Applications/Atlas/Application.php');

        $sFileContents = $oFileNode->getContents();
        $sEscapedContents = str_replace("\\", "\\\\", $sFileContents); //Required to escape JS escaping in front-end.

        $aPage=[];
        $aPage['file_contents']=$sEscapedContents;
        $aPage['file_ext']=$oFileNode->getFileExtenstion();

        $sContent=$oApp['twig']->render('components\Editor.view.twig.html', $aPage);
        return new sfHttp\Response($sContent);
    }
}
