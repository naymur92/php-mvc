<?php

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\User;

class UserController extends Controller
{
    /**
     * index page of Users
     *
     * @return void
     */
    public function index()
    {
        $usersModel = new User();
        $users = $usersModel->getAll();
        // dd($users);
        view('pages.users.index', array('title' => "Users", 'users' => $users));
    }

    /**
     * Create user
     *
     * @return void
     */
    public function create()
    {
        view('pages.users.create', array('title' => "Create User"));
    }

    /**
     * Store user
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {
        // Define sanitization rules
        $request->setSanitizationRules([
            'name' => ['string'],
            'email' => ['email'],
        ]);

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:127',
            'mobile' => 'required|string|max:15',
            'password' => 'required|string|min:8',
        ];

        // Validate data
        $request->validate($rules);

        $errors = $request->errors();

        $errorFound = false;

        if (!empty($errors)) {
            $errorFound = true;
        }

        $email = $request->input('email');
        if ($email != "") {
            $usersModel = new User();
            $user = $usersModel->where('email', '=', $email)->get();

            if (count($user) > 0) {
                $errorFound = true;
                $errors['email'][] = "Email must be unique!";
            }
        }

        if ($errorFound) {
            // set errors and old data into session
            $_SESSION['error'] = $errors;
            $_SESSION['old'] = $request->all();

            return redirect('/users/create');
        }

        $data = $request->validated();

        $usersModel = new User();

        $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $usersModel->insert($data);

        return redirect('/users');
    }
}
