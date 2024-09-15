<?php
// visitor_count.php

// Path to the file where the count is stored
$file = 'counter.txt';

// Check if the file exists and read the count
if (file_exists($file)) {
    $count = file_get_contents($file);
    $count++;
} else {
    $count = 1;
}

// Write the updated count back to the file
file_put_contents($file, $count);

// Output the count for use in your HTML
echo $count;
?>
    