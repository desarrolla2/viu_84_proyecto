<?php


/*
 * This file is part of the Data Miner.
 *
 * Daniel González <daniel@devtia.com>
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 */

$directory = new RecursiveDirectoryIterator('../../bin');
$iterator = new RecursiveIteratorIterator($directory);
$files = new RegexIterator($iterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH);

$license = file_get_contents('license.txt');

foreach ($files as $file) {
    $filePath = $file[0];
    $content = file_get_contents($filePath);

    // Check if the license block is already present
    if (strpos($content, $license) === false) {
        // Add license block before namespace or at the top of the file
        if (preg_match('/<\?php\s+namespace/', $content)) {
            $content = preg_replace('/<\?php\s+namespace/', '<?php'.PHP_EOL.PHP_EOL.$license.PHP_EOL.'namespace', $content);
        } else {
            $content = '<?php'.PHP_EOL.PHP_EOL.$license.PHP_EOL.$content;
        }

        file_put_contents($filePath, $content);
        echo "License added to: $filePath\n";
    }
}

echo "License block added to all applicable files.\n";
