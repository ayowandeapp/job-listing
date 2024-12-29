<?php

namespace App\Controllers;

use Framework\Validation;


class ListingController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    public function index(array $request = []): void
    {
        $listings = $this->db->query("SELECT * FROM listings")->fetchAll();

        loadView('listings/index', compact('listings'));
    }
    public function create(array $request = [])
    {
        loadView('listings/create');
    }

    public function show(array $params): void
    {

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", ['id' => $params['id']])->fetch();

        if (empty($listing)) {
            ErrorController::notFound('listing not found');
            return;

        }

        loadView('listings/show', compact('listing'));
    }

    public function store(array $params)
    {
        $allowedFields = [
            'title',
            'description',
            'salary',
            'tags',
            'company',
            'address',
            'city',
            'state',
            'phone',
            'email',
            'requirements',
            'benefits'
        ];
        $newListingData = array_intersect_key($_POST, array_flip($allowedFields));
        $newListingData['user_id'] = 1;
        $newListingData = array_map('sanitize', $newListingData);

        $requiredFields = ['title', 'salary', 'description', 'email', 'city', 'state'];
        $errors = [];
        foreach ($requiredFields as $field) {
            if (
                empty($newListingData[$field]) ||
                !Validation::string($newListingData[$field])
            ) {
                $errors[$field] = ucfirst($field) . ' is required!';

            }
        }

        if (!empty($errors)) {
            //reload view with errrors
            loadView('listings/create', [
                'errors' => $errors,
                'listing' => $newListingData
            ]);
            return;
        }

        $fields = [];
        $values = [];
        foreach ($newListingData as $key => $value) {
            $fields[] = $key;
            if ($value === '') {
                $newListingData[$key] = null;
            }
            $values[] = ":$key";
        }
        $fields = implode(', ', $fields);
        $values = implode(', ', $values);

        $query = "INSERT INTO listings ({$fields}) VALUES ({$values})";
        // dd($fields, $values, $newListingData);

        $this->db->query($query, $newListingData);
        redirect('/listings');





    }
}