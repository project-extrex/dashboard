<?php

/****************
 * Index router *
 ***************/

$router->get('/', fn() => $renderer->view('index', []));
