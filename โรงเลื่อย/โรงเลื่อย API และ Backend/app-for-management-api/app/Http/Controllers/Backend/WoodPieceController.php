<?php namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\WoodPieces;

class WoodPieceController extends Controller {
	protected $sawmill;

	public function __construct()
	{
		$this->middleware('auth');
		if (\Auth::user()->type != 'Admin') {
			\Redirect::to('auth/logout')->send();
		} else {
			$this->sawmill = $this->user_branch();
		}
	}

	public function getIndex()
	{
		return view('backend.wood_piece.index');
	}

	public function postDataList(Request $request)
	{
		// set date format
		$start = date("Y-m-d", strtotime('01-' . str_replace('/', '-', $request->date)));
		$finish = date("Y-m-t", strtotime($start));
		// get wood piece data
		$data = [];
		$wood = WoodPieces::select('total', 'losts', 'datetime')->whereBetween('datetime', [$start, $finish])->orderBy('datetime')->where('sawId', $this->sawmill->sawId)->get()->toArray();
		foreach ($wood as $key => $value) {
			$data[(int)substr($value['datetime'], 8, 2)] = $value;
		}

		$tbody = null;
		$start_day = (int)substr($start, 8, 2);
		$finish_day = (int)substr($finish, 8, 2);
		for ($i = $start_day; $i <= $finish_day; $i++) { 
			if (array_key_exists($i, $data)) {
				$tbody .= '<tr>';
				$tbody .= sprintf('<td class="text-center">%s</td>', $i);
				$tbody .= sprintf('<td><input name="total" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[$i]['total']);
				$tbody .= sprintf('<td><input name="losts" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[$i]['losts']);
				$tbody .= sprintf('<td class="text-center"><button type="button" class="btn btn-primary btn-sm" data-day="' . $i . '">แก้ไข</button></td>');
				$tbody .= '</tr>';
			} else {
				$tbody .= '<tr>';
				$tbody .= sprintf('<td class="text-center">%s</td>', $i);
				$tbody .= sprintf('<td><input name="total" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td><input name="losts" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td class="text-center"><button type="button" class="btn btn-primary btn-sm" data-day="' . $i . '">แก้ไข</button></td>');
				$tbody .= '</tr>';
			}
		}

		return ['tbody' => $tbody];
	}

	public function postUpdate(Request $request)
	{
		$date = date("Y-m-d", strtotime(str_replace('/', '-', $request->day . '/' . $request->date)));
		$data = WoodPieces::where(['datetime' => $date])->where('sawId', $this->sawmill->sawId)->first();

		if (sizeof($data)) {
			$data->total = $request->total;
			$data->losts = $request->losts;
			$data->updated_by = \Auth::user()->email;
			$data->save();
		} else {
			// set data
			$data = [];
			$data['sawId'] = $this->sawmill->sawId;
			$data['datetime'] = $date;
			$data['total'] = $request->total;
			$data['losts'] = $request->losts;
			$data['created_by'] = \Auth::user()->email;
			$data['updated_by'] = \Auth::user()->email;
			// create
			$model = new WoodPieces;
			$model->create($data);
		}
	}

}
