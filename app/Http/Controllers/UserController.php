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
        ];

        // Validate data
        $request->validate($rules);

        $errors = $request->errors();
        // $data = $request->validated();

        dd($errors);

        // Access specific sanitized input
        $email = $request->input('email');
    }
}
