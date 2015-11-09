<?php

session_start();

$conf = dirname(__FILE__) . '/conf/conf.php';

require('core/Application.php');

spl_autoload_register(array('testProject', 'autoload'));

testProject::create($conf)->run();
