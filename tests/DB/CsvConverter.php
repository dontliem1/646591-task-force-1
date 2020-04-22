<?php

declare(strict_types=1);

ini_set('display_errors', 'On');
error_reporting(E_ALL);

use Taskforce\DB\CsvConverter;
use Taskforce\Exceptions\SourceFileException;

require_once '../../vendor/autoload.php';

$files = glob('../../data/*.csv', GLOB_BRACE);
$converters = [];
foreach ($files as $counter => $file) {
    try {
        $converters[$counter] = new CsvConverter($file);
        $converters[$counter]->saveToSql();
    } catch (SourceFileException $e) {
        error_log('Can\'t load the file '.$file.' into converter: ' . $e->getMessage());
    }
}
