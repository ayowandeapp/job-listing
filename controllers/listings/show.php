<?php

$config = require basePath('config/db.php');

$db = new Database($config);

$id = $_GET['id'] ?? '';


$listing = $db->query("SELECT * FROM listings WHERE id = :id", ['id' => $id])->fetch();

loadView('listings/show', compact('listing'));