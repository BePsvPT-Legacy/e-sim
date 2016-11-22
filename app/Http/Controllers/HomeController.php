<?php namespace App\Http\Controllers;

use App\Esim\GameDatum;

class HomeController extends Controller {

    /**
     * Show the application dashboard to the user.
     *
     * @return Response
     */
    public function index()
    {
        $data = GameDatum::all();

        return view('home', compact('data'));
    }

}
