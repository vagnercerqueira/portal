atualiza_base();
var tableBasicas = initDataTable({
	idTab: 'tableBasicas', alinhamento: {
		"className": 'text-center',
		"width": "1%",
		//"targets": [6, 7, 8, 9, 10, 11, 12,13],
	},
});

$('textarea').keyup(function () {
	var id = this.id;
	var characterCount = $(this).val().length;
	if (id == "OBS_ACOMPANHAMENTO") {
		var current = $('#current_acompanhamento');
	}
	current.text(characterCount);
});


function atualiza_base(){
	fetch(pag_url + 'atualiza_base', {
        //method: 'POST',
        credentials: 'same-origin',
        //body: data
    }).then(function (response) {
        response.json().then(function (data) {
          if(data >0 ){
            error_alert(form, true, "Registros Atualizados: " +data);
          }
        });
    });
}

var desab_status_ativo = (e) => {
  form = document.getElementById("form_acompanhamento_cliente");
	stt = e.getAttribute('st');
  chave = e.getAttribute("data-id");
	stTxt = e.textContent;
	
	oData = new FormData();
	oData.append('chave', chave);
	oData.append('stat', stt);

	error_alert(form, 'loading', '')
	fetch(pag_url + "desab_status_ativo", {
		method: 'POST',
		body: oData,
	})
		.then(response => response.json())
		.then(json => {
			toastr.clear();
			if (json.TOT > 0) {
				var s = true;
				var msg = "Alteração efetuada com sucesso!!";
				dropDwn = e.closest('.dropdown');
				texto = dropDwn.querySelector('.txtativ');
				texto.textContent = stTxt;
				if (stt == 'S') {
					texto.classList.add('btn-success');
					texto.classList.remove('btn-danger');
				} else {
					texto.classList.remove('btn-success');
					texto.classList.add('btn-danger');
				}
				setTimeout(function () { error_alert(form, true, 'Status Alterado!!!'); }, 600);
			} else {
				setTimeout(function () { error_alert(form, false, 'Erro ao alterar Status!!!'); }, 600);
			}
		})
		.catch(function (error) {
			toastr.clear();
			setTimeout(function () { error_alert(form, false, 'Erro ao alterar permissao!!!'); }, 600);
			console.log("ocorreu algum erro");
		});
}

var desab_status_adiplente = (e) => {
  form = document.getElementById("form_acompanhamento_cliente");
	stt = e.getAttribute('st');
  chave = e.getAttribute("data-id");
	stTxt = e.textContent;
	
	oData = new FormData();
	oData.append('chave', chave);
	oData.append('stat', stt);

	error_alert(form, 'loading', '')
	fetch(pag_url + "desab_status_adiplente", {
		method: 'POST',
		body: oData,
	})
		.then(response => response.json())
		.then(json => {
			toastr.clear();
			if (json.TOT > 0) {
				var s = true;
				var msg = "Alteração efetuada com sucesso!!";
				dropDwn = e.closest('.dropdown');
				texto = dropDwn.querySelector('.txtativ');
				texto.textContent = stTxt;
				if (stt == 'S') {
					texto.classList.add('btn-success');
					texto.classList.remove('btn-danger');
				} else {
					texto.classList.remove('btn-success');
					texto.classList.add('btn-danger');
				}
				setTimeout(function () { error_alert(form, true, 'Status Alterado!!!'); }, 600);
			} else {
				setTimeout(function () { error_alert(form, false, 'Erro ao alterar Status!!!'); }, 600);
        
			}
      
		})
		.catch(function (error) {
			toastr.clear();
			setTimeout(function () { error_alert(form, false, 'Erro ao alterar permissao!!!'); }, 600);
			console.log("ocorreu algum erro");
		});
}

function atualiza_zap(arg, arg2) {
	oData = new FormData();
	oData.append('num_os', arg);
	oData.append('tipo', arg2);
	fetch(
		pag_url + 'atualiza_zap', {
		method: 'POST',
		body: oData
	})
		.then(function (response) {
			response.json().then(function (data) {
				let contato = data.contato;
				let mensagem = data.mensagem;
				tableBasicas.draw();
				error_alert(".whatsapp", true, "Finalize o envio de mensagem!");
				window.open("https://web.whatsapp.com/send?phone=55" + contato + "&text=" + mensagem, "_blank");
			});
		});
}

document.getElementById("form_expo_vendas").addEventListener('submit', (e) => {
	e.preventDefault();
	let perido_final = document.querySelector("#expo_per_fim").value;
	let tipo = document.querySelector('input[name="tipo"]:checked').value;
	window.open(pag_url + 'exportarVendas?expo_per_fim=' + perido_final+'&tipo='+tipo);
});
function gerar_pdf_ficha(arg) {
	if (arg === "") {
		alert("Nenhuma os selecionada!!!");
		return;
	}
	window.open(pag_url + 'gerar_pdf_ficha?id=' + arg, 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
}