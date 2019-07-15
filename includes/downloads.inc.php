<?php

$filename = 'downloads/' . $_GET['edition_year'] . '_' . $_GET['edition_number'] . '/article ' . $_GET['article_number'] . '.pdf';

header('Pragma: public');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false); // required for certain browsers
header('Content-Type: application/pdf');

header('Content-Disposition: inline; filename="' . basename($filename) . '";');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($filename));

readfile($filename);

exit;