<?php namespace App\Traits;

use App\Models\Sawmill;

trait Helper {

	public function user_branch()
	{
		$sawmill = $this->data_branch(\Auth::user()->branch);
		return $sawmill;
	}

	public function data_branch($shortname)
	{
		$sawmill = Sawmill::select('sawId', 'fullname', 'shortname')->where('shortname', $shortname)->first();
		return $sawmill;
	}

}