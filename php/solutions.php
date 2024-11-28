<?php

ini_set('max_execution_time', 600); // Increase to 10 minutes

// Matrix generation using associative array (O(1) lookup)
function generateMatrixUsingAssocArray(int $iMax, int $yMax): void
{
    $startMemory = memory_get_usage();
    $startTime   = microtime(true);

    $uniqueIntsLength = $iMax * $yMax;
    $uniqueInts       = [];

    // Generate unique random integers
    while (count($uniqueInts) < $uniqueIntsLength) {
        $uniqueInts[ rand() ] = true;
    }

    $uniqueKeys = array_keys($uniqueInts);
    $matrix     = [];
    $index      = 0;

    for ($i = 0; $i < $iMax; $i++) {
        $row = [];
        for ($y = 0; $y < $yMax; $y++) {
            $row[] = $uniqueKeys[ $index++ ];
        }
        $matrix[] = $row;
    }

    $endTime   = microtime(true);
    $endMemory = memory_get_usage();

    logPerformance($startTime, $endTime, $startMemory, $endMemory);
}

// Matrix generation using in_array (O(n) lookup)
function generateMatrixUsingInArray(int $iMax, int $yMax): void
{
    $startMemory = memory_get_usage();
    $startTime   = microtime(true);

    $uniqueIntsLength = $iMax * $yMax;
    $uniqueInts       = [];

    // Generate unique random integers
    while (count($uniqueInts) < $uniqueIntsLength) {
        $randInt = rand();
        if (!in_array($randInt, $uniqueInts)) {
            $uniqueInts[] = $randInt;
        }
    }

    $matrix = array_chunk($uniqueInts, $yMax);

    $endTime   = microtime(true);
    $endMemory = memory_get_usage();

    logPerformance($startTime, $endTime, $startMemory, $endMemory);
}

// Matrix generation using low-random best efficiency
function generateLowRandomMatrix(int $iMax, int $yMax): void
{
    $startMemory = memory_get_usage();
    $startTime   = microtime(true);

    $length     = $iMax * $yMax;
    $uniqueInts = range(1, $length);
    shuffle($uniqueInts);
    $matrix = array_chunk($uniqueInts, $yMax);

    $endTime   = microtime(true);
    $endMemory = memory_get_usage();

    logPerformance($startTime, $endTime, $startMemory, $endMemory);
}

// Log performance metrics
function logPerformance(float $startTime, float $endTime, int $startMemory, int $endMemory): void
{
    $duration    = $endTime - $startTime;
    $memoryUsage = ($endMemory - $startMemory) / 1024 / 1024;

    echo "Execution Time: " . round($duration, 5) . " seconds\n";
    echo "Memory Usage: " . round($memoryUsage, 5) . " MB\n";
    echo "---------------------------------\n";
}

// Define matrix sizes for benchmarking
$sizes = [
    ['rows' => 0, 'cols' => 10],
    ['rows' => 100, 'cols' => 100],
    ['rows' => 500, 'cols' => 500],
    ['rows' => 1000, 'cols' => 1000],
];

// Run benchmarks
function runBenchmarks(array $sizes): void
{
    echo "PHP Version: " . phpversion() . "\n";
    echo "Benchmarking with different matrix sizes...\n";
    echo "------------------------------------------\n";

    foreach ($sizes as $size) {
        $iMax = $size['rows'];
        $yMax = $size['cols'];

        echo "Matrix Size: {$iMax}x{$yMax}\n";
        echo "---------------------------------\n";

        // Associative Array Benchmark
        echo "Benchmark: Associative Array (O(1))\n";
        generateMatrixUsingAssocArray($iMax, $yMax);

        // in_array Benchmark
        echo "Benchmark: in_array (O(n))\n";
        generateMatrixUsingInArray($iMax, $yMax);

        // Low Random Benchmark
        echo "Benchmark: Low Random\n";
        generateLowRandomMatrix($iMax, $yMax);

        echo "\n";
    }
}

// Run all benchmarks
runBenchmarks($sizes);
