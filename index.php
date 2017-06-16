<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title></title>
  </head>
  <body>
    <center>
      <?php
      use App\BarcodeRender;
      require_once 'vendor/autoload.php';
      $example_one = file_get_contents('example_one.html');
      BarcodeRender::parse($example_one);
      echo '<hr>';
      $example_two = file_get_contents('example_two.html');
      BarcodeRender::parse($example_two);
      ?>
    </center>
  </body>
</html>
