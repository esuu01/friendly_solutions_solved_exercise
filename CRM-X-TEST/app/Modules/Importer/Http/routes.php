<?php

$NS = MODULES_NS.'Importer\Http\Controllers\\';

$router->get("/logs", [$NS.'ImporterController', 'logs'])->name("importers.logs");

$router->resource('importers', $NS.'ImporterController');
