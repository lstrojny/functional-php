<?php
if (extension_loaded('functional')) {
    die("Extension 'functional' loaded. Cannot run userspace implementation\n");
}

include __DIR__ . '/AbstractTestCase.php';
include __DIR__ . '/../../src/Functional/_import.php';
