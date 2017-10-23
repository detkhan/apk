@extends('backend._template._master')

@section('content')
<div class="row marketing">
	@include('backend._template.breadcrumb', ['breadcrumb' => ['เพิ่มข้อมูลประมาณการกำไรขาดทุน' => null]])

	<div class="col-lg-4 col-lg-offset-8 pd-0 mb-20">
		<div class="input-group date">
	  		<input name="date" type="text" class="form-control" value="{{ date('m/Y') }}"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
		</div>
	</div>
	<div class="col-lg-12 pd-0">
		<div style="overflow: auto; height: 350px;">
			<table id="tableProfitLoss" class="table table-bordered"> 
				<thead> 
					<tr> 
						<th class="text-center" style="width: 60px">วันที่</th> 
						<th class="text-center">รายได้รวม</th> 
						<th class="text-center">ค่าใช้จ่าย<br>ผลิตรวม</th> 
						<th class="text-center">กำไรขั้นต้น</th> 
						<th class="text-center">ค่าใช้จ่าย<br>คงที่</th> 
						<th class="text-center">กำไร(ขาดทุน)<br>สุทธิ</th> 
						<th class="text-center" style="width: 80px;">แก้ไข</th> 
					</tr> 
				</thead> 
				<tbody>
					
				</tbody> 
			</table>
		</div>
	</div>
</div>
@endsection

@section('script')
	<script>
		$(window).on('load', function() {
			$('.input-group.date').datepicker({
			    format: "mm/yyyy",
			    minViewMode: 1,
			    maxViewMode: 2,
			    language: "th",
			    autoclose: true
			});
			// get default data
			get_data();
		});

		$(document).ready(function() {
			$('#tableProfitLoss').on('click', 'button', function() {
				var	disabled = $(this).closest('tr').find('input').attr('disabled');
				if (typeof disabled === "undefined") {
					var post = {
						day: $(this).data('day'),
						date: $('[name="date"]').val(),
						incoming_total: $(this).closest('tr').find('[name="incoming_total"]').val(),
						outcoming_total: $(this).closest('tr').find('[name="outcoming_total"]').val(),
						gross_profit_total: $(this).closest('tr').find('[name="gross_profit_total"]').val(),
						costs_total: $(this).closest('tr').find('[name="costs_total"]').val(),
						profit_loss_total: $(this).closest('tr').find('[name="profit_loss_total"]').val(),
					}
					$.post('profit-loss/update', post);

					$('#tableProfitLoss input').prop('disabled', true);
					$('#tableProfitLoss button').prop('disabled', false);
				} else {
					$(this).closest('tr').find('input').prop('disabled', false);
					$('#tableProfitLoss button').not($(this)).prop('disabled', true);
				}
				return false;
			});

			$('[name="date"]').on('change', function() {
				get_data();
			});
		});

		function get_data() {
			table_loading('#tableProfitLoss > tbody', 7);

			var	post = { date: $('[name="date"]').val() }
			$.post('profit-loss/data-list', post, function(data) {
				$('#tableProfitLoss > tbody').html(data.tbody);
			});

			return false;
		}
	</script>
@endsection