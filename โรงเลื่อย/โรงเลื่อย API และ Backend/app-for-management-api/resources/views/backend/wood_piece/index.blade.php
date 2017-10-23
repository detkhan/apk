@extends('backend._template._master')

@section('content')
<div class="row marketing">
	@include('backend._template.breadcrumb', ['breadcrumb' => ['เพิ่มข้อมูลไม้ท่อน' => null]])

	<div class="col-lg-4 col-lg-offset-8 pd-0 mb-20">
		<div class="input-group date">
	  		<input name="date" type="text" class="form-control" value="{{ date('m/Y') }}"><span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
		</div>
	</div>
	<div class="col-lg-12 pd-0">
		<div style="overflow: auto; height: 350px;">
			<table id="tableWoodPiece" class="table table-bordered"> 
				<thead> 
					<tr> 
						<th class="text-center" style="width: 80px">วันที่</th> 
						<th class="text-center">คงเหลือ</th> 
						<th class="text-center">สูบเสีย</th> 
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
			$('#tableWoodPiece').on('click', 'button', function() {
				var	disabled = $(this).closest('tr').find('input').attr('disabled');
				if (typeof disabled === "undefined") {
					var post = {
						day: $(this).data('day'),
						date: $('[name="date"]').val(),
						total: $(this).closest('tr').find('[name="total"]').val(),
						losts: $(this).closest('tr').find('[name="losts"]').val(),
					}
					$.post('wood-piece/update', post);

					$('#tableWoodPiece input').prop('disabled', true);
					$('#tableWoodPiece button').prop('disabled', false);
				} else {
					$(this).closest('tr').find('input').prop('disabled', false);
					$('#tableWoodPiece button').not($(this)).prop('disabled', true);
				}
				return false;
			});

			$('[name="date"]').on('change', function() {
				get_data();
			});
		});

		function get_data() {
			table_loading('#tableWoodPiece > tbody', 4);

			var	post = { date: $('[name="date"]').val() }
			$.post('wood-piece/data-list', post, function(data) {
				$('#tableWoodPiece > tbody').html(data.tbody);
			});

			return false;
		}
	</script>
@endsection