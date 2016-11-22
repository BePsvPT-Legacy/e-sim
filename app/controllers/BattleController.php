<?php

class BattleController extends \BaseController {

	public function index($server) {
		$data = [
			'latest_battle_id' => Information::get_value('secura_battle')
		];
		return View::make('battle.index', $data);
	}

	public function battle($server, $id, $round = null) {
		$battle = Battle::battle_data($server, $id, $round);
		$data = [
			'empty' => (count($battle)) ? false : true,
			'is_mu' => false,
			'battle' => $battle,
		];
		return View::make('battle.battle', $data);
	}

	public function battle_mu($server, $id) {
		$battle = Battle::battle_data_mu($server, $id);
		$data = [
			'empty' => (count($battle)) ? false : true,
			'is_mu' => true,
			'battle' => $battle,
		];
		return View::make('battle.battle', $data);
	}

}
