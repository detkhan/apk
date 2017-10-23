<?php namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Performance;
use App\Models\PerformanceGoals;

class PerformanceController extends Controller {
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
		return view('backend.performance.index');
	}

	public function postVolumeList(Request $request)
	{
		// set date format
		$start = date("Y-m-d", strtotime('01-' . str_replace('/', '-', $request->date)));
		$finish = date("Y-m-t", strtotime($start));
		// get performance volume data
		$data = [];
		$volume = Performance::select('volume_product', 'performance_type', 'datetime')->whereBetween('datetime', [$start, $finish])->orderBy('datetime')->where('sawId', $this->sawmill->sawId)->get()->toArray();
		foreach ($volume as $key => $value) {
			$type = str_replace('+', '', str_replace('/Goals', '', $value['performance_type']));
			$data[(int)substr($value['datetime'], 8, 2)][$type] = $value['volume_product'];
		}

		$tbody = null;
		$start_day = (int)substr($start, 8, 2);
		$finish_day = (int)substr($finish, 8, 2);
		for ($i = $start_day; $i <= $finish_day; $i++) { 
			if (array_key_exists($i, $data)) {
				$volume = isset($data[$i]['Volume_Product']) ? $data[$i]['Volume_Product'] : 0;
				$ab = isset($data[$i]['AB']) ? $data[$i]['AB'] : 0;
				$abc = isset($data[$i]['ABC']) ? $data[$i]['ABC'] : 0;

				$tbody .= '<tr>';
				$tbody .= sprintf('<td class="text-center">%s</td>', $i);
				$tbody .= sprintf('<td><input name="Volume_Product" type="text" class="form-control" value="%s" disabled="disabled"></td>', $volume);
				$tbody .= sprintf('<td><input name="AB" type="text" class="form-control" value="%s" disabled="disabled"></td>', $ab);
				$tbody .= sprintf('<td><input name="ABC" type="text" class="form-control" value="%s" disabled="disabled"></td>', $abc);
				$tbody .= sprintf('<td class="text-center"><button type="button" class="btn btn-primary btn-sm" data-day="' . $i . '">แก้ไข</button></td>');
				$tbody .= '</tr>';
			} else {
				$tbody .= '<tr>';
				$tbody .= sprintf('<td class="text-center">%s</td>', $i);
				$tbody .= sprintf('<td><input name="Volume_Product" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td><input name="AB" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td><input name="ABC" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td class="text-center"><button type="button" class="btn btn-primary btn-sm" data-day="' . $i . '">แก้ไข</button></td>');
				$tbody .= '</tr>';
			}
		}
		return ['tbody' => $tbody];
	}

	public function postUpdateVolume(Request $request)
	{
		$this->updateVolume($request, 'Volume_Product/Goals');
		$this->updateVolume($request, 'AB/Goals');
		$this->updateVolume($request, 'AB+C/Goals');
	}

	public function updateVolume($request, $type)
	{
		$date = date("Y-m-d", strtotime(str_replace('/', '-', $request->day . '/' . $request->date)));
		$data = Performance::where(['datetime' => $date, 'performance_type' => $type])->where('sawId', $this->sawmill->sawId)->first();
		if (sizeof($data)) {
			if ($type == 'Volume_Product/Goals') {
				$data->volume_product = $request->Volume_Product;
			} elseif ($type == 'AB/Goals') {
				$data->volume_product = $request->AB;
			} elseif ($type == 'AB+C/Goals') {
				$data->volume_product = $request->ABC;
			}
			$data->updated_by = \Auth::user()->email;
			$data->save();
		} else {
			// set data
			$data = [];
			$data['sawId'] = $this->sawmill->sawId;
			$data['datetime'] = $date;
			$data['volume_product'] = 0;
			if ($type == 'Volume_Product/Goals') {
				$data['volume_product'] = $request->Volume_Product;
			} elseif ($type == 'AB/Goals') {
				$data['volume_product'] = $request->AB;
			} elseif ($type == 'AB+C/Goals') {
				$data['volume_product'] = $request->ABC;
			}
			$data['performance_type'] = $type;
			$data['created_by'] = \Auth::user()->email;
			$data['updated_by'] = \Auth::user()->email;
			// create
			$model = new Performance;
			$model->create($data);
		}
	}

	public function postGoalList(Request $request)
	{
		$data = [];
		$goals = PerformanceGoals::select('volume_product', 'ab', 'ab_c', 'datetime')->whereYear('datetime', '=', $request->year)->orderBy('datetime')->where('sawId', $this->sawmill->sawId)->get()->toArray();
		foreach ($goals as $key => $value) {
			$data[(int)substr($value['datetime'], 5, 2)] = $value;
		}

		$tbody = null;
		$month = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
		for ($i=0; $i < 12; $i++) { 
			if (array_key_exists(($i + 1), $data)) {
				$tbody .= '<tr>';
				$tbody .= sprintf('<td class="text-center">%s</td>', $month[$i]);
				$tbody .= sprintf('<td><input name="volume_product" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[($i + 1)]['volume_product']);
				$tbody .= sprintf('<td><input name="ab" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[($i + 1)]['ab']);
				$tbody .= sprintf('<td><input name="ab_c" type="text" class="form-control" value="%s" disabled="disabled"></td>', $data[($i + 1)]['ab_c']);
				$tbody .= sprintf('<td class="text-center"><button type="button" class="btn btn-primary btn-sm" data-month="' . ($i + 1) . '">แก้ไข</button></td>');
				$tbody .= '</tr>';
			} else {
				$tbody .= '<tr>';
				$tbody .= sprintf('<td class="text-center">%s</td>', $month[$i]);
				$tbody .= sprintf('<td><input name="volume_product" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td><input name="ab" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td><input name="ab_c" type="text" class="form-control" value="0" disabled="disabled"></td>');
				$tbody .= sprintf('<td class="text-center"><button type="button" class="btn btn-primary btn-sm" data-month="' . ($i + 1) . '">แก้ไข</button></td>');
				$tbody .= '</tr>';
			}
		}
		return ['tbody' => $tbody];
	}

	public function postUpdateGoal(Request $request)
	{
		$date = date("Y-m-d", strtotime($request->year . '-' . $request->month . '-01'));
		$data = PerformanceGoals::where('datetime', $date)->where('sawId', $this->sawmill->sawId)->first();
		if (sizeof($data)) {
			$data->volume_product = $request->volume_product;
			$data->ab = $request->ab;
			$data->ab_c = $request->ab_c;
			$data->updated_by = \Auth::user()->email;
			$data->save();
		} else {
			// set data
			$data = [];
			$data['sawId'] = $this->sawmill->sawId;
			$data['datetime'] = $date;
			$data['volume_product'] = $request->volume_product;
			$data['ab'] = $request->ab;
			$data['ab_c'] = $request->ab_c;
			$data['created_by'] = \Auth::user()->email;
			$data['updated_by'] = \Auth::user()->email;
			// create
			$model = new PerformanceGoals;
			$model->create($data);
		}
	}

}
