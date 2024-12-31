<?php

namespace App\Controllers;

use Framework\Session;
use Framework\Validation;
class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();

    }
    public function login(): void
    {
        loadView('users/login');
    }

    public function create(): void
    {
        loadView('users/create');
    }

    public function store()
    {
        $email = $_POST['email'];
        $errors = [];
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        //check if email exist
        $params = [
            'email' => $_POST['email']
        ];

        $user = $this->db->query('SELECT * FROM users WHERE email = :email', $params)->fetch();
        if ($user) {
            $errors['email1'] = 'Email already exist!';
        }

        if (!Validation::string($_POST['name'], 2, 50)) {
            $errors['name'] = 'Name must be between two and 50 characters';
        }

        if (!Validation::string($_POST['password'], 6)) {
            $errors['password'] = 'Password must be at least 6 characters';
        }

        if (!Validation::match($_POST['password'], $_POST['password_confirmation'])) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }
        if (!empty($errors)) {
            loadView('users/create', [
                'errors' => $errors,
                'user' => $_POST
            ]);
            exit;
        }
        $acceptedFields = ['name', 'email', 'city', 'state', 'password'];

        $params = array_intersect_key($_POST, array_flip($acceptedFields));
        $params['password'] = password_hash($params['password'], PASSWORD_DEFAULT);

        $fields = implode(', ', $acceptedFields);
        $values = array_map(fn($item) => ":$item", $acceptedFields);

        $values = implode(', ', $values);

        $this->db->query(
            "INSERT INTO users ({$fields}) values ({$values})",
            $params
        );
        //get new user id
        $userId = $this->db->conn->lastInsertId();

        Session::set('user', array_merge(
            ['id' => $userId],
            array_intersect_key($_POST, array_flip(['name', 'email', 'city', 'state']))
        ));

        redirect('/');
    }

    public function logout()
    {
        Session::clearAll();

        $params = session_get_cookie_params();
        setcookie('PHPSESSID', '', time() - 86400, $params['path'], $params['domain']);

        redirect('/');
    }

    public function authenticate()
    {
        $email = $_POST['email'];
        $errors = [];
        if (!Validation::email($email)) {
            $errors['email'] = 'Please enter a valid email address';
        }

        if (!Validation::string($_POST['password'])) {
            $errors['password'] = 'Password field required';
        }

        if (!empty($errors)) {
            loadView('users/login', [
                'errors' => $errors,
                'user' => $_POST
            ]);
            exit;
        }

        $user = $this->db->query(
            "SELECT * FROM users WHERE email = :email",
            ['email' => $email]

        )->fetch();

        if (empty($user) || !password_verify($_POST['password'], $user->password)) {
            $errors['email'] = 'incorrect credentials';
            loadView('users/login', [
                'errors' => $errors,
            ]);
            exit;
        }

        Session::set('user', array_merge(
            array_intersect_key((array) $user, array_flip(['id', 'name', 'email', 'city', 'state']))
        ));

        redirect('/');


    }
}