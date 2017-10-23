<?php namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Profit;

class ProfitLossController extends Controller {
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
		return view('backend.profit_loss.index');
	}

	public function postDataList(Request $request)
	{
		// set date format
		$start = date("Y-m-d", strtotime('01-' . str_replace('/', '-', $request->date)));
		$finish = date("Y-m-t", strtotime($start));
		// get profit loss data
		$data = [];
		$profit = Profit::select('incoming_total', 'outcoming_total', 'gross_profit_total', 'costs_total', 'profit_loss_total', 'datetime')->whereBetween('datetime', [$start, $finish])->orderBy('datetime')->where('sawId', $this->sawmill->sawId)->get()->toArray();
		foreach ($profit as $key => $value) {
			$data[(int)substr($value['datetime'], 8, 2)] = $value;
		}

		$tbody = null;
		$start_day = (int)substr($start, 8, 2);
		$finish_day = (int)substr($finish, 8, 2);
		for ($i = $start_day; $i <= $finish_day; $i++) { 
			if (array_key_exists($i, $data)) {
				$tbody .= '<tr>';
				$tbody .= sprintf('<td class="text-center">%s</td>', $i);
				$tbody .= sprintf('<td><input name="incoming_total" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[$i]['incoming_total']);
				$tbody .= sprintf('<td><input name="outcoming_total" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[$i]['outcoming_total']);
				$tbody .= sprintf('<td><input name="gross_profit_total" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[$i]['gross_profit_total']);
				$tbody .= sprintf('<td><input name="costs_total" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[$i]['costs_total']);
				$tbody .= sprintf('<td><input name="profit_loss_total" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[$i]['profit_loss_total']);
				$tbody .= sprintf('<td class="text-center"><button type="button" class="btn btn-primary btn-sm" data-day="' . $i . '">แก้ไข</button></td>');
				$tbody .= '</tr>';
			} else {
				$tbody .= '<tr>';
				$tbody .= sprintf('<td class="text-center">%s</td>', $i);
				$tbody .= sprintf('<td><input name="incoming_total" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td><input name="outcoming_total" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td><input name="gross_profit_total" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td><input name="costs_total" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td><input name="profit_loss_total" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td class="text-center"><button type="button" class="btn btn-primary btn-sm" data-day="' . $i . '">แก้ไข</button></td>');
				$tbody .= '</tr>';
			}
		}

		return ['tbody' => $tbody];
	}

	public function postUpdate(Request $request)
	{
		$date = date("Y-m-d", strtotime(str_replace('/', '-', $request->day . '/' . $request->date)));
		$data = Profit::where(['datetime' => $date])->where('sawId', $this->sawmill->sawId)->first();

		if (sizeof($data)) {
			$data->incoming_total = $request->incoming_total;
			$data->outcoming_total = $request->outcoming_total;
			$data->gross_profit_total = $request->gross_profit_total;
			$data->costs_total = $request->costs_total;
			$data->profit_loss_total = $request->profit_loss_total;
			$data->updated_by = \Auth::user()->email;
			$data->save();
		} else {
			// set data
			$data = [];
			$data['sawId'] = $this->sawmill->sawId;
			$data['datetime'] = $date;
			$data['incoming_total'] = $request->incoming_total;
			$data['outcoming_total'] = $request->outcoming_total;
			$data['gross_profit_total'] = $request->gross_profit_total;
			$data['costs_total'] = $request->costs_total;
			$data['profit_loss_total'] = $request->profit_loss_total;
			$data['created_by'] = \Auth::user()->email;
			$data['updated_by'] = \Auth::user()->email;
			// create
			$model = new Profit;
			$model->create($data);
		}
	}

}
