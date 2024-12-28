<?php


$config = require basePath('config/db.php');

$db = new Database($config);

$listings = $db->query("SELECT * FROM listings Limit 6")->fetchAll();

loadView('home', compact('listings'));