@extends('backend._template._master')

@section('content')
<div class="row marketing">
	@include('backend._template.breadcrumb', ['breadcrumb' => ['เพิ่มข้อมูล Performance Intensive' => null]])
	<ul class="nav nav-tabs" id="myTabs" role="tablist">
		<li role="presentation" class="active">
			<a href="#volumn" id="volumn-tab" role="tab" data-toggle="tab" aria-controls="volumn" aria-expanded="true">ปริมาณ</a>
		</li> 
		<li role="presentation" class="">
			<a href="#goal" role="tab" id="goal-tab" data-toggle="tab" aria-controls="goal" aria-expanded="false">เป้าหมาย</a>
		</li>
	</ul>
	<div class="tab-content" id="myTabContent">
		<div class="tab-pane fade active in" role="tabpanel" id="volumn" aria-labelledby="volumn-tab" style="padding: 10px;">
			<div class="row marketing" style="margin-bottom: 0px;">
				<div class="col-lg-4 col-lg-offset-8 pd-0 mb-20">
					<div class="input-group date">
				  		<input name="date" type="text" class="form-control" value="{{ date('m/Y') }}"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
					</div>
				</div>
				<div class="col-lg-12 pd-0">
					<div style="overflow: auto; height: 350px;">
						<table id="tableVolume" class="table table-bordered"> 
							<thead> 
								<tr> 
									<th class="text-center" style="width: 80px">วันที่</th> 
									<th class="text-center">ปริมาณการผลิต</th> 
									<th class="text-center">ยิว AB</th> 
									<th class="text-center">ยิว AB+C</th> 
									<th class="text-center" style="width: 80px;">แก้ไข</th> 
								</tr> 
							</thead> 
							<tbody>
								
							</tbody> 
						</table>
					</div>
				</div>
			</div>
		</div> 
		<div class="tab-pane fade" role="tabpanel" id="goal" aria-labelledby="goal-tab" style="padding: 10px;">
			<div class="row marketing" style="margin-bottom: 0px;">
				<div class="col-lg-4 col-lg-offset-8 pd-0 mb-20">
					<div class="input-group date">
				  		<input name="date" type="text" class="form-control" value="{{ date('Y') }}"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
					</div>
				</div>
				<div class="col-lg-12 pd-0">
					<div style="overflow: auto; height: 350px;">
						<table id="tableGoals" class="table table-bordered"> 
							<thead> 
								<tr> 
									<th class="text-center" style="width: 150px">เดือน</th> 
									<th class="text-center">ปริมาณการผลิต</th> 
									<th class="text-center">ยิว AB</th> 
									<th class="text-center">ยิว AB+C</th> 
									<th class="text-center" style="width: 80px;">แก้ไข</th> 
								</tr> 
							</thead> 
							<tbody>
								
							</tbody> 
						</table>
					</div>
				</div>
			</div>
		</div>
</div>
@endsection

@section('script')
	<script>
		$(window).on('load', function() {
			$('#volumn .input-group.date').datepicker({
			    format: "mm/yyyy",
			    minViewMode: 1,
			    maxViewMode: 2,
			    language: "th",
			    autoclose: true
			});
			$('#goal .input-group.date').datepicker({
			    format: "yyyy",
			    minViewMode: 2,
			    maxViewMode: 2,
			    language: "th",
			    autoclose: true
			});
			// get default data
			get_volume();
			get_goals();
		});

		$(document).ready(function() {
			$('#myTabs a').click(function (e) {
				e.preventDefault()
				$(this).tab('show');
			});

			$('#tableVolume').on('click', 'button', function() {
				var	disabled = $(this).closest('tr').find('input').attr('disabled');
				if (typeof disabled === "undefined") {
					var post = {
						day: $(this).data('day'),
						date: $('#volumn .input-group.date>input').val(),
						Volume_Product: $(this).closest('tr').find('[name="Volume_Product"]').val(),
						AB: $(this).closest('tr').find('[name="AB"]').val(),
						ABC: $(this).closest('tr').find('[name="ABC"]').val(),
					}
					$.post('performance/update-volume', post);

					$('#tableVolume input').prop('disabled', true);
					$('#tableVolume button').prop('disabled', false);
				} else {
					$(this).closest('tr').find('input').prop('disabled', false);
					$('#tableVolume button').not($(this)).prop('disabled', true);
				}
				return false;
			});

			$('#volumn .input-group.date>input').on('change', function() {
				get_volume();
			});

			$('#tableGoals').on('click', 'button', function() {
				var	disabled = $(this).closest('tr').find('input').attr('disabled');
				if (typeof disabled === "undefined") {
					var post = {
						month: $(this).data('month'),
						year: $('#goal .input-group.date>input').val(),
						volume_product: $(this).closest('tr').find('[name="volume_product"]').val(),
						ab: $(this).closest('tr').find('[name="ab"]').val(),
						ab_c: $(this).closest('tr').find('[name="ab_c"]').val(),
					}
					$.post('performance/update-goal', post);

					$('#tableGoals input').prop('disabled', true);
					$('#tableGoals button').prop('disabled', false);
				} else {
					$(this).closest('tr').find('input').prop('disabled', false);
					$('#tableGoals button').not($(this)).prop('disabled', true);
				}
				return false;
			});

			$('#goal .input-group.date>input').on('change', function() {
				get_goals();
			});
		});

		function get_volume() {
			table_loading('#tableVolume > tbody', 5);

			var	post = { date: $('#volumn .input-group.date>input').val() }
			$.post('performance/volume-list', post, function(data) {
				$('#tableVolume > tbody').html(data.tbody);
			});

			return false;
		}

		function get_goals() {
			table_loading('#tableGoals > tbody', 5);

			var	post = { year: $('#goal .input-group.date>input').val() }
			$.post('performance/goal-list', post, function(data) {
				$('#tableGoals > tbody').html(data.tbody);
			});

			return false;
		}
	</script>
@endsection