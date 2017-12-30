<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/lib/bootstrap.php';

Lib\Executor::execute();

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<div id="app">
    <projects :projects="<?php echo Lib\VueFilter::filter(Lib\MagentoProjects::getProjects()); ?>"></projects>
    <brace-editor></brace-editor>
</div>
</body>
<script src="build/app.js"></script>
</html>
