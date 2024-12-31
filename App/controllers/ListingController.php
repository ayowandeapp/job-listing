<?php

namespace App\Controllers;

use Framework\Session;
use Framework\Validation;


class ListingController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    public function index(array $params = [], array $request = []): void
    {
        $keywords = $request['keywords'] ?: '';
        $location = $request['location'] ?: '';

        $sql = "SELECT * FROM listings";

        $params = [];
        $conditions = [];

        // Add conditions based on filters
        if (!empty($keywords)) {
            $conditions[] = "(tags LIKE :keywords OR title LIKE :keywords OR description LIKE :keywords OR company LIKE :keywords)";
            $params['keywords'] = "%$keywords%";
        }
        if (!empty($location)) {
            $conditions[] = "(address LIKE :location OR city LIKE :location OR state LIKE :location)";
            $params['location'] = "%$location%";
        }
        // Append conditions to the query if they exist
        if ($conditions) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        // dd($sql);
        $sql .= " ORDER BY created_at DESC";
        $listings = $this->db->query($sql, $params)->fetchAll();

        loadView('listings/index', compact('listings', 'request'));
    }
    public function create()
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
        $newListingData['user_id'] = Session::get('user')['id'];
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

        if (
            !$listing
        ) {
            ErrorController::notFound('This listing is not found!');
            return;
        }

        if (
            Session::get('user')['id'] != $listing->user_id
        ) {
            Session::setFlashMessage('error_message', 'Unauthorized to delete this listing');
            redirect("/listings/$listing->id");
            return;
        }
        $this->db->query("DELETE FROM listings WHERE  id = :id ", $params);

        Session::setFlashMessage('success_message', 'Listing deleted successfully!');

        redirect('/listings');

    }

    public function edit(array $params): void
    {

        $listing = $this->db->query("SELECT * FROM listings WHERE id = :id", ['id' => $params['id']])->fetch();

        if (empty($listing)) {
            ErrorController::notFound('listing not found');
            return;
        }

        if (
            Session::get('user')['id'] != $listing->user_id
        ) {
            Session::setFlashMessage('error_message', 'You are Unauthorized to update this listing');
            redirect("/listings/$listing->id");
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

        if (
            Session::get('user')['id'] != $listing->user_id
        ) {
            Session::setFlashMessage('error_message', 'You are Unauthorized to update this listing');
            redirect("/listings/$listing->id");
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


        Session::setFlashMessage('success_message', 'Listing updated successfully!');

        redirect("/listings/$id");
    }


}