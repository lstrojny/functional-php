<?php
if (!extension_loaded('functional')) {
    die("Extension 'functional' missing. Cannot run native tests\n");
}

require __DIR__ . '/AbstractTestCase.php';
