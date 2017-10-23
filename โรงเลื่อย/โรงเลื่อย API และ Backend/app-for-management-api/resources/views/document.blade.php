<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Document</title>
</head>
<body>
	{{-- <table width="100%" border="1">
		<tr height = "30px">
			<th width="10%">#</th> 
			<th width="30%">Detail</th>
			<th width="30%">Function</th>
			<th width="30%">Input</th>
		</tr>
	</table> --}}
	{!! Form::open(array('url' => 'api/user/user-login')) !!}
		<input type="text" name="email" value="chanakan@hotmail.com" placeholder="email">
		<input type="text" name="password" value="12345" placeholder="password">
		<button type="submit">Submit</button>
		<label for="test">postUserLogin</label>
	{!! Form::close() !!} <br>
	
	{!! Form::open(array('url' => 'api/user/user-register')) !!}
		<input type="text" name="email" value="" placeholder="email">
		<input type="text" name="password" value="" placeholder="password">
		<input type="text" name="password_confirmation" value="" placeholder="password_confirmation">
		<input type="text" name="firstname" value="" placeholder="firstname">
		<input type="text" name="lastname" value="" placeholder="lastname">
		<input type="text" name="status" value="" placeholder="status">
		<input type="text" name="type" value="" placeholder="type">
		<input type="text" name="branch" value="" placeholder="MSD,MNT">
		<button type="submit">Submit</button>
		<label for="test">postUserRegister</label>
	{!! Form::close() !!} <br>
	
	{!! Form::open(array('url' => 'api/user/change-password')) !!}
		<input type="text" name="email" value="chanakan@hotmail.com" placeholder="email">
		<input type="text" name="old_password" value="" placeholder="old_password">
		<input type="text" name="password" value="" placeholder="password">
		<input type="text" name="password_confirmation" value="" placeholder="password_confirmation">
		<button type="submit">Submit</button>
		<label for="test">postChangePassword</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/user/forget-password')) !!}
	<input type="text" name="email" value="chanakan@revoitmarketing.com" placeholder="email">
	<button type="submit">Submit</button>
	<label for="test">postForgetPassword</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/service/connection-log')) !!}
	<input type="text" name="from" value="" placeholder="from(access,mysql,mssql)">
	<input type="text" name="status" value="" placeholder="status">
	<input type="text" name="message" value="" placeholder="message">
	<button type="submit">Submit</button>
	<label for="test">postConnectionLog</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/transaction/realtime-transaction')) !!}
	<input type="text" name="sawId" value="" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postRealtimeTransaction</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/transaction/transaction-detail')) !!}
	<input type="text" name="tranId" value="36" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postTransactionDetail</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/transaction/insert-transaction')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="productId" value="1" placeholder="">
	<input type="text" name="truck_register_number" value="กท-1231" placeholder="">
	<input type="text" name="weight_no" value="" placeholder="">
	<input type="text" name="customer_name_in" value="ชนกันต์" placeholder="">
	<input type="text" name="datetime_in" value="2017-03-21" placeholder="">
	<input type="text" name="weight_in" value="7000" placeholder="">
	<input type="text" name="customer_name_out" value="ชนกันต์" placeholder="">
	<input type="text" name="datetime_out" value="2017-03-21" placeholder="">
	<input type="text" name="weight_out" value="3000" placeholder="">
	<input type="text" name="weight_net" value="0" placeholder="">
	<input type="text" name="weight_tare" value="0" placeholder="">
	<input type="text" name="weight_total" value="4000" placeholder="">
	<input type="text" name="product_price_unit" value="0.95" placeholder="">
	<input type="text" name="price_total" value="2700" placeholder="">
	<input type="text" name="type_name" value="in" placeholder="">
	<input type="text" name="pic_path" value="" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postInsertTransaction</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/transaction/insert-transactiontemp')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="productId" value="1" placeholder="">
	<input type="text" name="truck_register_number" value="กท-1231" placeholder="">
	<input type="text" name="weight_no" value="" placeholder="">
	<input type="text" name="customer_name_in" value="ชนกันต์" placeholder="">
	<input type="text" name="datetime_in" value="2017-03-21" placeholder="">
	<input type="text" name="weight_in" value="7000" placeholder="">
	<input type="text" name="customer_name_out" value="ชนกันต์" placeholder="">
	<input type="text" name="datetime_out" value="2017-03-21" placeholder="">
	<input type="text" name="weight_out" value="3000" placeholder="">
	<input type="text" name="weight_net" value="0" placeholder="">
	<input type="text" name="weight_tare" value="0" placeholder="">
	<input type="text" name="weight_total" value="4000" placeholder="">
	<input type="text" name="product_price_unit" value="0.95" placeholder="">
	<input type="text" name="price_total" value="2700" placeholder="">
	<input type="text" name="type_name" value="in" placeholder="">
	<input type="text" name="pic_path" value="" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postInsertTransactiontemp</label>
	{!! Form::close() !!} <br>

	<a href="{{ url('api/transaction/realtime-report') }}">getRealtimeReport</a>
	
	{!! Form::open(array('url' => 'api/transaction/realtime-report')) !!}
	<input type="text" name="email" value="" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postRealtimeReport</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/dialyreport/insert-woodpieces')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="wood_pieces_incoming" value="10000" placeholder="">
	<input type="text" name="timber_saw" value="" placeholder="">
	<input type="text" name="wood_sale" value="2000" placeholder="">
	<input type="text" name="total" value="7000" placeholder="">
	<input type="text" name="losts" value="50" placeholder="">
	<input type="text" name="datetime" value="2017-03-21" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postInsertWoodpieces</label>
	{!! Form::close() !!} <br>
	
	{!! Form::open(array('url' => 'api/dialyreport/wood-pieces')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="dateMonth" value="3" placeholder="">
	<input type="text" name="years" value="2017" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postWoodPieces</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/dialyreport/check-woodpieces')) !!}
	<input type="text" name="email" value="" placeholder="">
	<input type="text" name="dateMonth" value="4" placeholder="">
	<input type="text" name="years" value="2017" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postCheckWoodpieces</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/dialyreport/insert-firewood')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="fire_wood_incoming" value="5000" placeholder="">
	<input type="text" name="fire_wood_sale" value="2760" placeholder="">
	<input type="text" name="firewood_total" value="1" placeholder="">
	<input type="text" name="firewood_losts" value="1" placeholder="">
	<input type="text" name="datetime" value="2017-03-21" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postInsertFirewood</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/dialyreport/fire-wood')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="dateMonth" value="1" placeholder="">
	<input type="text" name="years" value="2017" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postFireWood</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/dialyreport/check-firewood')) !!}
	<input type="text" name="email" value="" placeholder="">
	<input type="text" name="dateMonth" value="4" placeholder="">
	<input type="text" name="years" value="2017" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postCheckFirewood</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/dialyreport/insert-weightoutcoming')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="wood_grades_weight" value="5000" placeholder="">
	<input type="text" name="slab_weight" value="2760" placeholder="">
	<input type="text" name="sawdust_weight" value="2890" placeholder="">
	<input type="text" name="datetime" value="2017-03-21" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postInsertWeightoutcoming</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/dialyreport/weight-outcoming')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="dateMonth" value="1" placeholder="">
	<input type="text" name="years" value="2017" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postWeightOutcoming</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/dialyreport/check-weightoutcoming')) !!}
	<input type="text" name="email" value="" placeholder="">
	<input type="text" name="dateMonth" value="4" placeholder="">
	<input type="text" name="years" value="2017" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postCheckWeightoutcoming</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/profit/insert-profit')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="incoming_total" value="14542" placeholder="">
	<input type="text" name="outcoming_total" value="1789123" placeholder="">
	<input type="text" name="gross_profit_total" value="13234" placeholder="">
	<input type="text" name="costs_total" value="1123" placeholder="">
	<input type="text" name="profit_loss_total" value="15671" placeholder="">
	<input type="text" name="datetime" value="2017-03-21" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postInsertProfit</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/profit/profit-loss')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="dateMonth" value="3" placeholder="">
	<input type="text" name="years" value="2017" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postProfitLoss</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/profit/check-profitloss')) !!}
	<input type="text" name="email" value="" placeholder="">
	<input type="text" name="dateMonth" value="4" placeholder="">
	<input type="text" name="years" value="2017" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postCheckProfitloss</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/profit/incoming-outcoming')) !!}
	<input type="text" name="email" value="" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postIncomingOutcoming</label>
	{!! Form::close() !!} <br>

	<a href="{{ url('api/profit/incoming-outcoming') }}">getIncomingOutcoming</a>

	{!! Form::open(array('url' => 'api/performance/insert-performance')) !!}
	<input type="text" name="sawId" value="1" placeholder="">
	<input type="text" name="volume_product" value="12356" placeholder="">
	<input type="text" name="goals" value="532365" placeholder="">
	<input type="text" name="performance_type" value="Volume_Product/Goals" placeholder="">
	<input type="text" name="datetime" value="2017-03-21" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postInsertPerformance</label>
	{!! Form::close() !!} <br>
	
	<a href="{{ url('api/performance/intensive-performance') }}">getIntensivePerformance</a>


	{!! Form::open(array('url' => 'api/performance/intensive-performance')) !!}
	<input type="text" name="email" value="" placeholder="">
	<input type="text" name="dateMonth" value="04" placeholder="">
	<input type="text" name="years" value="2017" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postIntensivePerformance</label>
	{!! Form::close() !!} <br>

	<a href="{{ url('api/transaction/max-number') }}">getMaxNumber</a>

	{!! Form::open(array('url' => 'api/utility/upload-image')) !!}
	<input type="text" name="imagePath" value="" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postUploadImage</label>
	{!! Form::close() !!} <br>

	{!! Form::open(array('url' => 'api/utility/check-transaction')) !!}
	<input type="text" name="countNumber" value="" placeholder="">
	<button type="submit">Submit</button>
	<label for="test">postCheckTransaction</label>
	{!! Form::close() !!} <br>
</body>
</html>