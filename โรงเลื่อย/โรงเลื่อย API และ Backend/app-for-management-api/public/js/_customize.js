$( window ).on('load', function() {

	$('#modalChangePassword').on('show.bs.modal', function (e) {
		$('#modalChangePassword .alert').alert('close');
		$('#formChangePassword input').val('');
	});

	$('#modalChangePassword .btn-submit').on('click', function() {
		$.ajax({
			type: 'post',
			url: 'user/change-password',
			data: $('#formChangePassword').serialize(),
			dataType: 'json',
			beforeSend: function(data) {
				$('#formChangePassword div').removeClass('has-error');
				$('#modalChangePassword .btn-submit').button('loading');
			},
			success: function(data) {
				$('#modalChangePassword .alert').alert('close');
				if (data.status) {
					$('#formChangePassword input').val('');
					$('#modalChangePassword .modal-body').prepend(
				        '<div class="alert alert-success alert-dismissable">'+
				            '<button type="button" class="close" ' + 
				                    'data-dismiss="alert" aria-hidden="true">' + 
				                '&times;' + 
				           '</button>' + 
				             'บันทึกข้อมูลรหัสผ่านใหม่เรียบร้อยแล้ว' + 
				         '</div>');
				} else {
					$('#modalChangePassword .modal-body').prepend(
			        '<div class="alert alert-danger alert-dismissable">'+
			            '<button type="button" class="close" ' + 
			                    'data-dismiss="alert" aria-hidden="true">' + 
			                '&times;' + 
			           '</button>' + 
			             'ไม่สามารถแก้ไขรหัสผ่านได้' + 
			         '</div>');
				}
				$('#modalChangePassword .btn-submit').button('reset');
			},
			error: function(data) {
				// clear help block
				$('#formChangePassword .help-block').remove();
				// alert
				var errors = data.responseJSON;
				$.each(errors, function(key, value) {
					if (key == 'password') {
						$('#formChangePassword label[for="password"], #formChangePassword label[for="password_confirmation"]').closest('div').addClass('has-error');
					} else {
						$('#formChangePassword label[for="' + key + '"]').closest('div').addClass('has-error');
					}
					$('#formChangePassword label[for="' + key + '"]').closest('div').append('<span class="help-block red">' + value + '</span>');
				});
				$('#modalChangePassword .btn-submit').button('reset');
			}
		});
		return false;
	});

});

function table_loading(target, colspan) {
	$(target).html('<tr><td colspan="' + colspan + '" class="text-center"><img src="../../image/icon/loading.gif" style="width: 51%;"></td></tr>');
}

function checkPasswordCharacter() {
    var specialChar = [33, 64, 35, 36, 37, 38];
    if (event.keyCode >= 48 && event.keyCode <= 57 || 
        event.keyCode >= 65 && event.keyCode <= 90 || 
        event.keyCode >= 97 && event.keyCode <= 122 ||
        specialChar.indexOf(event.keyCode) >= 0
    ) {
        return true;
    } else {
        return false;
    }
}