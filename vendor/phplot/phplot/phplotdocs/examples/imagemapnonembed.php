<?php
# $Id$
# PHPlot example: Image Map and Non-embedded Plot Image
# This scripts creates a PHPlot plot image and an image map, without using
# embedded (data-url) images. The down-side is that this script has to 
# run twice, and generate the plot twice: once for the HTML wrapper page
# with image map, and once to create the actual image.
# This takes a 'mode' CGI parameter (HTTP GET) and generates a plot
# if mode=plot, and an HTML page and image map otherwise.
# Note: The image map links just produce popup (Javascript alert) messages,
# for demonstration purposes.
require_once 'phplot.php';

# This global string accumulates the image map AREA tags.
$image_map = "";

# Data for bar chart:
$data = array(
    array('1980', 13, 21,  8, 24, 10, 19),
    array('1990', 14, 19,  6, 26, 11, 13),
    array('2000', 11, 13,  8, 15,  9, 11),
    array('2010', 13, 16,  9, 21, 12, 13),
);
$legend = array('US', 'AL', 'CT', 'MS', 'PA', 'SD');

# Produce an image if the URL has mode=plot, and an HTML page otherwise:
$do_html = empty($_GET['mode']) || $_GET['mode'] != 'plot';

# Callback for 'data_points' : Generate 1 line in the image map.
function store_map($im, $data, $shape, $row, $col, $x1, $y1, $x2, $y2)
{
  global $image_map;
  if ($shape != 'rect') die("Error expecting rect shapes from plot\n");
  # Get the data point value. (Offset column by 1 to skip label)
  $value = $data[$row][$col+1];
  # See top note: URL for demonstration purposes.
  $message = "Data value: $value";
  $url = "javascript:alert('$message')";
  # Convert coords to integers:
  $coords = sprintf("%d,%d,%d,%d", $x1, $y1, $x2, $y2);
  # Area alt text (required attribute):
  $alt = "Region for Group $row bar $col";
  # Area title text, which is displayed as the tool-tip:
  $title = "Group $row bar $col";

  # Append one line to the image map:
  $image_map .= "  <area shape=\"rect\" coords=\"$coords\""
             .  " title=\"$title\" alt=\"$alt\" href=\"$url\">\n";
}

# Produce an HTML page which includes the image map and the reference
# to the plot image. The plot image is generated by this same script,
# with a mode=plot URL parameter.
function generate_html()
{
    global $image_map;

    # Self-referencing URL for <img>, with additional mode=plot parameter.
    # If the URL already has parameters, use & separator, else ?.
    $sep = empty($_SERVER['QUERY_STRING']) ? '?' : '&';
    $url = htmlspecialchars($_SERVER['REQUEST_URI'] . $sep .  'mode=plot');

    echo <<<END
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
     "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>PHPlot Example - Image Map and Non-embedded Plot Image</title>
</head>
<body>
<h1>PHPlot Example: Bar Chart with image map, non-embedded</h1>
<p>
This example uses a single PHP script to generate both a container HTML page
with image map, and the plot image itself. The image map is used to
produce tool-tip text for the plot image. The script is called twice.
</p>
<map name="map1">
$image_map
</map>
<p>
If you are viewing this image 'live', via a web server, you can float your
cursor over a bar to see the tooltip text, and click to get an alert popup
containing the data value. (This will not work if you are viewing the
example in the reference manual.)
</p>
<img src="$url" alt="Plot image" usemap="#map1">
</body>
</html>

END;
}


$plot = new phplot(640, 480);
if ($do_html) {
    # When producing HTML, don't output the plot image:
    $plot->SetPrintImage(False);
    # Just to make it clear, this script usage returns HTML:
    header("Content-type: text/html");
    # The image map callback is only needed when doing the HTML page.
    # Pass the data array so the callback can fetch values from it.
    $plot->SetCallback('data_points', 'store_map', $data);
}
$plot->SetTitle("PHPlot Example: Image Map and Non-embedded Plot");
$plot->SetImageBorderType('plain');
$plot->SetDataValues($data);
$plot->SetDataType('text-data');
$plot->SetPlotType('bars');
$plot->SetXTickPos('none');
$plot->SetPlotAreaWorld(NULL, 0, NULL, 30);
$plot->SetLegend($legend);
$plot->DrawGraph();
if ($do_html) generate_html();
