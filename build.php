<?php
chdir(__DIR__);

$filename = 'build/metrics.phar';
if (file_exists($filename)) {
    unlink($filename);
}

$phar = new \Phar($filename, 0, 'extension.phar');
$phar->setSignatureAlgorithm(\Phar::SHA1);
$phar->startBuffering();


$files = array_merge(rglob('*.php'), rglob('*.twig'));
$exclude = '!(.git)|(.svn)!';
foreach($files as $file) {
    if(preg_match($exclude, $file)) continue;
    $phar->addFromString($file, file_get_contents($file));
}

$phar->addFromString('init.php', file_get_contents(__DIR__.'/init.php'));

$phar->setStub(<<<STUB
<?php

/*
* This file is part of the PhpMetrics
*
* (c) Jean-François Lépine
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

Phar::mapPhar('extension.phar');

return require 'phar://extension.phar/init.php';

__HALT_COMPILER();
STUB
);
$phar->stopBuffering();

function rglob($pattern='*', $flags = 0, $path='')
{
    $paths=glob($path.'*', GLOB_MARK|GLOB_ONLYDIR|GLOB_NOSORT);
    $files=glob($path.$pattern, $flags);
    foreach ($paths as $path) { $files=array_merge($files,rglob($pattern, $flags, $path)); }
    return $files;
}