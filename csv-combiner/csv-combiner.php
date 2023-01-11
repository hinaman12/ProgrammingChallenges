#!/usr/bin/env php
<?php

if ($argc < 2) {
    echo "Please specify the CSV files to process.\n";
    exit(1);
}

// Use the first file as the column header
$headers = array_map('str_getcsv', file($argv[1]));

// Create a generator function
function read_rows($file) {
    $handle = fopen($file, 'r');
    fgetcsv($handle);
    while (($data = fgetcsv($handle)) !== false) {
        // Add the filename column to the row data
        $data[] = basename($file);
        yield $data;
    }
    fclose($handle);
}

// Add the new column to headers
$headers[0][] = "filename";

// output the csv
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=combined.csv');
$output = fopen('php://output', 'w');
fputcsv($output, $headers[0]);

// iterate through files
for ($i = 1; $i < $argc; $i++) {
    $file = $argv[$i];
    foreach(read_rows($file) as $row)
    {
        fputcsv($output, $row);
    }
}
fclose($output);
