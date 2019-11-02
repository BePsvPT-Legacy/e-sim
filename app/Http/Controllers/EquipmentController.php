<?php

namespace App\Http\Controllers;

use App\Equipment;
use Illuminate\Http\Request;

class EquipmentController extends Controller
{
    /**
     * Show equipment information.
     *
     * @param string $server
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function show($server, $id)
    {
        $equipments = Equipment::where('server', $server)
            ->whereIn('equipment_id', explode(',', $id))
            ->get();

        return view('equipments.index', compact('equipments'));
    }
}
