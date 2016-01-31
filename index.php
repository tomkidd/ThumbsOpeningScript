<?php
date_default_timezone_set("America/Chicago");
$xml=("http://www.idlethumbs.net/feeds/idle-thumbs");
$xmlDoc = new DOMDocument();
$xmlDoc->load($xml);

// get latest episode title
$title = $xmlDoc->getElementsByTagName('item')->item(0)->getElementsByTagName('title')->item(0)->nodeValue;

// extrapolate number, add one to it
$current_episode = intval(explode(" ", explode(':', $title)[0])[2]) + 1;

echo("<h1>It's ");
echo(date("F jS, Y"));
echo("</h1>");

echo("<h1>This is Idle Thumbs ");
echo($current_episode);
echo("</h1>");

?>