<?php
$root = dirname(__DIR__);
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
$count = 0;
foreach ($files as $file) {
    $path = $file->getPathname();
    if (!$file->isFile() || $file->getExtension() !== 'php' || preg_match('~/(vendor|\.git|\.phpunit.cache)/~', $path)) continue;
    $process = proc_open([PHP_BINARY, '-l', $path], [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes);
    $output = stream_get_contents($pipes[1]).stream_get_contents($pipes[2]);
    fclose($pipes[1]); fclose($pipes[2]);
    if (proc_close($process) !== 0) throw new RuntimeException($output);
    ++$count;
}
echo "PASS PHP ".PHP_VERSION." syntax: $count files\n";
