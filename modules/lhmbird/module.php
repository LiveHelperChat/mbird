<?php

$Module = array( "name" => "Bird.com",
    'variable_params' => true );

$ViewList = array();

$ViewList['index'] = array(
    'params' => array(),
    'uparams' => array('action','csfr'),
    'functions' => array('use_admin'),
);

$ViewList['settings'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['subscriptions'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$ViewList['channels'] = array(
    'params' => array(),
    'uparams' => array(),
    'functions' => array('configure'),
);

$FunctionList['use_admin'] = array('explain' => 'Allow operator to use Bird.com');
$FunctionList['configure'] = array('explain' => 'Allow operator to configure Bird.com');