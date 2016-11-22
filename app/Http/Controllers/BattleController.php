<?php namespace App\Http\Controllers;

use App\Esim\Battle\Battle;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BattleController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function entire($server, $id)
    {
        $data = Battle::battle($server, $id);

        $mu = false;

        return view('battle.show', compact('server', 'data', 'mu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\View\View
     */
    public function round($server, $id, $round)
    {
        $data = Battle::battle($server, $id, $round);

        $mu = false;

        return view('battle.show', compact('server', 'data', 'mu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function mu($server, $id)
    {
        $data = Battle::mu($server, $id);

        $mu = true;

        return view('battle.show', compact('server', 'data', 'mu'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function live($server, $id)
    {
        $data = Battle::live($server, $id);

        return view('battle.live', compact('server', 'data'));
    }

}
