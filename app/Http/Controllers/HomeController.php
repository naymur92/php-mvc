<?php

namespace App\Http\Controllers;

use App\Core\Controller;
use App\Utilities\DB;
use PDO;

class HomeController extends Controller
{
    /**
     * Display the home page
     *
     * @return void
     */
    public function index()
    {
        view('pages.homepage', array('title' => "Home"));
    }

    /**
     * Database connection testing route
     *
     * @return void
     */
    public function testDbConnection()
    {
        $data = DB::query("SELECT gateway, card_type, TotalAmount FROM online_payment_transaction ORDER BY created_at DESC LIMIT 500")->fetchAll();
        dd($data);

        echo "db connection page is here";
    }
}
