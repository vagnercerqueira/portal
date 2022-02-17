
$('#newpass').submit(function (e) {

	if ($("#senha").val() != $("#confirma_senha").val()) {
		$("#merror").show().addClass('text-danger').removeClass('text-success').html("Senha e confirma senha sao diferentes");
	} else {
		var request = $.ajax({
			url: base_url + "/email_senha/confirm_newpass",
			type: "POST",
			data: $('#newpass').serialize(),
			dataType: "json",
			beforeSend: function () { },
		}).done(function (data) {
			if (data.sucesso == true) {
				$("#merror").show().addClass('text-success').removeClass('text-danger').html(data.msg);
			} else {
				$("#merror").show().addClass('text-danger').removeClass('text-success').html(data.msg);
			}
		}).always(function (data) {

		}).fail(function (jqXHR, textStatus) {
			alert("Erro no Servidor, Requisicao falhou: " + textStatus);
		});
	}
	e.preventDefault();
	return false;
});
