<?php

/**
 * Theme function that will run if the theme we're active
 */

use App\Core\AdminSidebar;
use App\Core\Option;
use App\Icon\Icon;

//Icon::downloadSvgIcon('https://www.svgfind.com/download/10878549/chevron%20up.svg', 'chevron_right');

AdminSidebar::addMenu([
  'title' => 'test',
  'slug' => 'test',
  'position' => 5,
  'callback' => function () {
    ?>
    <h1>Hello world </h1>
    <form action="<?= $_SERVER['REQUEST_URI'] ?>" method="post">
    <?= htmlspecialchars($_GET['msg']) ?>
    <input type="color" name="color" value="<?= Option::get_option('color') ?? '' ?>">
    <input type="submit" name="save">
    <?php
    if (isset($_POST['save'])) {
      Option::set_option('color', $_POST['color']);
      // echo "saved successfully";
      // sleep(1);
      echo <<<Red
        <script>
        window.location.href = '{$_SERVER['REQUEST_URI']}?msg=Successfully+changed'
        </script>
        Red;
    }
    ?>
  </form>
    <?php
  },
  'submenus' => [
    'test' => [
      'title' => 'test',
      'slug' => 'test',
      'callback' => function () {
         echo 'hello world';
      }
    ]
  ]
]);


\App\Registry\Menu::add([
  "url" => "/",
  "title" => "extrex",
  "icon" => "fa fa-e"
]);