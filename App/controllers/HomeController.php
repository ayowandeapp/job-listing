<?php

namespace App\Controllers;

use App\Services\ValidatorService;
use Framework\Database;


class HomeController extends Controller
{
    public function __construct(
        protected ValidatorService $validatorService,
        protected Database $db
    ) {


    }
    public function index(array $request = [])
    {

        $listings = $this->db->query(
            "SELECT * FROM listings ORDER BY created_at DESC Limit 6"
        )->fetchAll();

        loadView('home', compact('listings'));

    }
}