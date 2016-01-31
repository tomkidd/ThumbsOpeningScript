<?php

date_default_timezone_set("America/Chicago");

$xml=("http://www.idlethumbs.net/feeds/idle-thumbs");

$xmlDoc = new DOMDocument();
$xmlDoc->load($xml);

$item = $xmlDoc->getElementsByTagName('item')->item(0);
$title = $item->getElementsByTagName('title')->item(0)->nodeValue;

$pieces = explode(':', $title);
$current_episode = explode(" ", $pieces[0]);

echo("<h1>It's ");
echo(date("F jS, Y"));
echo("</h1>");

echo("<h1>This is Idle Thumbs ");
echo(intval($current_episode[2]) + 1);
echo("</h1>");

?>