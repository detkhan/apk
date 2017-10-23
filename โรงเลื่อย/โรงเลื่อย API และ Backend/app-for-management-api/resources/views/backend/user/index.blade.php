@extends('backend._template._master')

@section('content')
<div class="row marketing">
	@include('backend._template.breadcrumb', ['breadcrumb' => ['ข้อมูลผู้ใช้งานระบบ' => null]])

	<div class="col-xs-6 col-lg-2 pd-0 mb-20">
		<button id="addUser" type="button" class="btn btn-default" data-toggle="modal" data-target="#modalUser"><span class="glyphicon glyphicon-plus"></span> เพิ่มผู้ใช้งาน</button>
	</div>
	<div class="col-xs-6 col-lg-4 col-lg-offset-6 pd-0 mb-20">
		<div class="form-group mb-0">
	  		<select name="type" class="form-control selectpicker">
	  			<option value="CEO">CEO</option>
	  			<option value="Manager">Manager</option>
	  			<option value="Admin">Admin</option>
			</select>
		</div>
	</div>
	<div class="col-xs-12 col-lg-12 pd-0">
		<div style="overflow: auto; height: 350px;">
			<table id="tableUsers" class="table table-bordered"> 
				<thead> 
					<tr> 
						<th class="text-center" style="width: 80px">อีเมล</th> 
						<th class="text-center">ชื่อ - นามสกุล</th> 
						<th class="text-center" style="width: 100px;">โรงเลื่อย</th> 
						<th class="text-center">สถานะ</th> 
						<th class="text-center" style="width: 80px;">แก้ไข</th> 
					</tr> 
				</thead> 
				<tbody>
					
				</tbody> 
			</table>
		</div>
	</div>
</div>

<div id="modalUser" class="modal fade" tabindex="-1" role="dialog">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				{!! Form::open(['id' => 'formUser', 'url' => '#']) !!}
				<input type="hidden" id="userId" name="userId">
				<div class="form-group">
					<label class="control-label" for="email">อีเมล</label>
					<input type="email" class="form-control" id="email" name="email" placeholder="อีเมล">
				</div>
				<div class="form-group">
					<label class="control-label" for="password">รหัสผ่าน</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="รหัสผ่าน" onkeypress="return checkPasswordCharacter();">
				</div>
				<div class="form-group">
					<label class="control-label" for="password_confirmation">ยืนยันรหัสผ่าน</label>
					<input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="ยืนยันรหัสผ่าน" onkeypress="return checkPasswordCharacter();">
				</div>
				<div class="form-group">
					<label class="control-label" for="firstname">ชื่อ</label>
					<input type="text" class="form-control" id="firstname" name="firstname" placeholder="ชื่อ">
				</div>
				<div class="form-group">
					<label class="control-label" for="lastname">นามสกุล</label>
					<input type="text" class="form-control" id="lastname" name="lastname" placeholder="นามสกุล">
				</div>
				<div class="form-group">
					<label class="control-label" for="type">ประเภท</label>
					<select id="type" name="type" class="form-control selectpicker">
			  			<option value="CEO">CEO</option>
			  			<option value="Manager">Manager</option>
			  			<option value="Admin">Admin</option>
					</select>
				</div>
				<div class="form-group">
					<label class="control-label" for="branch">สาขา</label>
					<select id="branch" name="branch[]" class="form-control selectpicker" title="กรุณาเลือกสาขา" multiple>
						@foreach ($branchs as $branch)
				  			<option value="{{ $branch->shortname }}">{{ $branch->fullname }}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group">
					<label class="control-label" for="status">สถานะ</label>
					<select id="status" name="status" class="form-control selectpicker">
			  			<option value="on">ปกติ</option>
			  			<option value="off">ปิดการใช้งาน</option>
					</select>
				</div>
				{!! Form::close() !!}
			</div>
			<div class="modal-footer">
				<button type="button" data-loading-text="กำลังส่งข้อมูล" class="btn btn-primary btn-submit">Save changes</button>
				<button type="button" class="btn btn-default" data-dismiss="modal">ปิดหน้าต่าง</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
@endsection

@section('script')
	<script>
		$(window).on('load', function() {
			// get default data
			get_data();
		});

		$(document).ready(function() {
			$('#addUser').on('click', function() {
				clear_user_form();
				$('#modalUser .modal-title').text('เพิ่มข้อมูลผู้ใช้งาน');
				$('#modalUser .modal-footer > .btn-primary').text('เพิ่มข้อมูล');
			});

			$('#tableUsers').on('click', 'button', function() {
				// clear validate
				$('#formUser .help-block').remove();
				$('#formUser').find('div').removeClass('has-error');
				// post data
				var post = { id: $(this).data('user') }
				$.post('user/edit', post, function(data) {
					$.each(data, function(key, value) {
						if (key == 'branch' || key == 'status') {
							$('#formUser [id="' + key + '"]').selectpicker('val', value);
						} else if (key == 'type') {
							$('#formUser [id="type"]').selectpicker('val', value);
							$('#formUser [id="branch"]').selectpicker('destroy');
							if (value == 'Admin') {
								$('#formUser [id="branch"]').prop('multiple', false);
								var firstOption = $('#formUser [id="branch"] option:first').val();
								$('#formUser [id="branch"]').selectpicker('val', firstOption);
							} else {
								$('#formUser [id="branch"]').prop('multiple', 'multiple');
								$('#formUser [id="branch"] > option').remove('.bs-title-option');
								$('#formUser [id="branch"]').selectpicker('val', '');
							}
						} else {
							$('#formUser [id="' + key + '"]').val(value);
						}
						$('#formUser [id="email"]').prop('disabled', true);
						$('#formUser [id="password"], #formUser [id="password_confirmation"]').val('');
						$('#modalUser .modal-title').text('แก้ไขข้อมูลผู้ใช้งาน');
						$('#modalUser .modal-footer > .btn-primary').text('แก้ไขข้อมูล');
						$('#modalUser').modal('show');
					});
				});
				return false;
			});

			$('.row.marketing [name="type"]').on('change', function() {
				get_data();
			});

			$('#formUser [name="type"]').on('change', function() {
				$('#formUser [id="branch"]').selectpicker('destroy');
				if ($(this).val() == 'Admin') {
					$('#formUser [id="branch"]').prop('multiple', false);
					var firstOption = $('#formUser [id="branch"] option:first').val();
					$('#formUser [id="branch"]').selectpicker('val', firstOption);
				} else {
					$('#formUser [id="branch"]').prop('multiple', 'multiple');
					$('#formUser [id="branch"] > option').remove('.bs-title-option');
					$('#formUser [id="branch"]').selectpicker('val', '');
				}
			});

			$('#modalUser .btn-submit').on('click', function() {
				$('#formUser div').removeClass('has-error');
				$.ajax({
					type: 'post',
					url: 'user/save',
					data: $('#formUser').serialize(),
					dataType: 'json',
					beforeSend: function(data) {
						$('#modalUser .btn-submit').button('loading');
					},
					success: function(data) {
						if (data.status) {
							$('.alert-success').alert('close');
							$('.breadcrumb').after(
						        '<div class="alert alert-success alert-dismissable">'+
						            '<button type="button" class="close" ' + 
						                    'data-dismiss="alert" aria-hidden="true">' + 
						                '&times;' + 
						            '</button>' + 
						            'บันทึกข้อมูลผู้ใช้งานเรียบร้อยแล้ว' + 
						         '</div>');
							// refresh data and close modal
							get_data();
							$('#modalUser .btn-submit').button('reset');
							$('#modalUser').modal('hide');
						}
					},
					error: function(data) {
						// clear help block
						$('#formUser .help-block').remove();
						// alert
						var errors = data.responseJSON;
						$.each(errors, function(key, value) {
							if (key == 'password') {
								$('#formUser label[for="password"], #formUser label[for="password_confirmation"]').closest('div').addClass('has-error');
							} else {
								$('#formUser label[for="' + key + '"]').closest('div').addClass('has-error');
							}
							$('#formUser label[for="' + key + '"]').closest('div').append('<span class="help-block red">' + value + '</span>');
						});
						$('#modalUser .btn-submit').button('reset');
					}
				});
				return false;
			});
		});

		function get_data() {
			table_loading('#tableUsers > tbody', 5);

			var	post = { type: $('.row.marketing [name="type"]').val() }
			$.post('user/data-list', post, function(data) {
				$('#tableUsers > tbody').html(data.tbody);
			});

			return false;
		}

		function clear_user_form() {
			$('#modalUser input').val('');
			$('#formUser .help-block').remove();
			$('#formUser').find('div').removeClass('has-error');
			$('#formUser [id="email"]').prop('disabled', false);
			$('#formUser [id="status"]').selectpicker('val', 'on');
			$('#formUser [id="type"]').selectpicker('val', 'CEO');
			$('#formUser [id="branch"]').prop('multiple', true);
			$('#formUser [id="branch"]').selectpicker('destroy');
			$('#formUser [id="branch"]').selectpicker('deselectAll');
			$('#formUser [id="branch"] > option').remove('.bs-title-option');
			$('#formUser [id="branch"]').selectpicker();
		}
	</script>
@endsection