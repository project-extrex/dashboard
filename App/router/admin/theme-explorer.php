<?php

$router->get('/admin/theme-explorer', function () use ($renderer) {
  if (!isAdmin()) {
    exit;
  }
  $renderer->renderAdmin('theme-explorer');
});
