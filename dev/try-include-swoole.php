<?php

declare(strict_types=1);

(static function () {
    $file = \dirname(__DIR__) . '/src/Components/swoole/vendor/autoload.php';
    if (is_file($file))
    {
        include $file;
    }
})();
