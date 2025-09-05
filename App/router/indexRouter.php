<?php

/****************
 * Index router *
 ***************/

$router->get('/', fn() => $renderer->render('index', []));
