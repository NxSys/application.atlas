<?php
use NxSys\Applications\Atlas;
use Codeception\Verify;

$I = new FunctionalTester($scenario);
$I->wantTo('ensure application is bootable');
