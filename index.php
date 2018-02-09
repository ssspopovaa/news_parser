<?php session_start()?>
<!DOCTYPE html>
<html lang="en">
  <head>
        <meta charset="utf-8">
  </head>
  <title>Парсинг</title>
  <body>
      <h1>Парсинг сайта новостей</h1>
      <form action="index.php" method="POST">
          <p>Kоличество новостей:</p>
          <input type="text" name="news_count"><br><br>
          <input type="submit" name="start" value="Старт">
          <input type="submit" name = "clear" value="Очистить"><br><br>
      </form>
      
  </body>
  <?php
  ini_set('display_errors',1);
  error_reporting(E_ALL);
  
  require_once '/controllers/Router.php';
$router = new Router();
$router->run();