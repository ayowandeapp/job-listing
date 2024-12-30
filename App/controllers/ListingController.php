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

    public function delete(array $params): void
    {
        //validate
        if (empty($params['id'])) {

            redirect('/listings');
        }
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id ", ['id' => $params['id']])->fetch();

        if (!$listing) {
            ErrorController::notFound('This listing is not found!');
            return;
        }

        $this->db->query("DELETE FROM listings WHERE  id = :id ", $params);

        $_SESSION['success_message'] = 'Listing deleted successfully!';

        redirect('/listings');

    }

    public function edit(array $params): void
    {

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", ['id' => $params['id']])->fetch();

        if (empty($listing)) {
            ErrorController::notFound('listing not found');
            return;

        }
        // dd($listing);

        loadView('listings/edit', compact('listing'));
    }


    public function update(array $params)
    {
        $id = $params['id'];
        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", ['id' => $id])->fetch();

        if (empty($listing)) {
            ErrorController::notFound('listing not found');
            return;
        }


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
        $requiredFields = ['title', 'salary', 'description', 'email', 'city', 'state'];

        $updateListingData = array_intersect_key($_POST, array_flip($allowedFields));
        $updateListingData = array_map('sanitize', $updateListingData);

        $errors = [];
        foreach ($requiredFields as $field) {
            if (
                empty($updateListingData[$field]) ||
                !Validation::string($updateListingData[$field])
            ) {
                $errors[$field] = ucfirst($field) . ' is required!';

            }
        }

        if (!empty($errors)) {
            //reload view with errrors
            loadView("listings/edit", [
                'errors' => $errors,
                'listing' => (object) array_merge((array) $listing, $updateListingData)
            ]);
            return;
        }

        $updatefields = [];
        foreach ($updateListingData as $key => $value) {
            // $fields[] = $key;
            if ($value === '') {
                $updateListingData[$key] = null;
            }
            $updatefields[] = "$key = :$key";
        }
        $updatefields = implode(', ', $updatefields);


        $query = "UPDATE listings SET {$updatefields} WHERE id = :id";
        $updateListingData['id'] = $id;

        $this->db->query($query, $updateListingData);


        $_SESSION['success_message'] = 'Listing updated successfully!';
        redirect("/listings/$id");
    }


}