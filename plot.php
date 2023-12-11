<?php

$lats = $_GET['lats'];

// Convert the string of latitudes back to an array
$lat_array = explode(",", $lats);

// Plot histogram
$bin_count = 20; // You can adjust the number of bins as needed
$histogram = array_count_values($lat_array);

$fig = "<img src='data:image/png;base64,";
$fig .= base64_encode(generate_histogram_plot($histogram, $bin_count));
$fig .= "' alt='Distribution of datasets by latitude'>";
echo $fig;

function generate_histogram_plot($histogram, $bin_count) {
    $fig = new \PHPlot\PHPlot(800, 600);
    $data = [];
    foreach ($histogram as $lat => $count) {
        $data[] = [$lat, $count];
    }
    $fig->SetDataValues($data);
    $fig->SetPlotType('bars');
    $fig->SetTitle("Distribution of datasets by latitude");
    $fig->SetXTitle("Latitude");
    $fig->SetYTitle("Frequency");
    return $fig->DrawGraph();
}
