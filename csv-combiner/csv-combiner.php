#!/usr/bin/env php
<?php

if ($argc < 2) {
    echo "Please specify the CSV files to process.\n";
    exit(1);
}

// Use the first file as the column header
$headers = array_map('str_getcsv', file($argv[1]));

// Create an array to store the rows
$rows = array();

// Iterate through the input files
for ($i = 1; $i < $argc; $i++) {
    $file = $argv[$i];

    // Open the file for reading
    $handle = fopen($file, 'r');

    // Skip the first row (headers)
    fgetcsv($handle);

    // Read the rows from the file
    while (($data = fgetcsv($handle)) !== false) {
        // Add the filename column to the row data
        array_push($data, basename($file));
        array_push($rows, $data);
    }

    // Close the file
    fclose($handle);
}

// Add the new column to headers
array_push($headers[0], "Filename");

// output the csv
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=combined.csv');
$output = fopen('php://output', 'w');
fputcsv($output, $headers[0]);
foreach ($rows as $row) {
    fputcsv($output, $row);
}
fclose($output);
