<?php

namespace App\Controllers;


class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    public function index(array $request = [])
    {

        $listings = $this->db->query(
            "SELECT * FROM listings ORDER BY created_at DESC Limit 6"
        )->fetchAll();

        loadView('home', compact('listings'));

    }
}