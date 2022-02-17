var tableBasicas = initDataTable();
//------------------------------------------------------
var desab_status = (e) => {
	form = document.getElementById("form_usuarios");
	stt = e.getAttribute('st');
	stTxt = e.textContent;
	chave = e.getAttribute("data-id");
	oData = new FormData();
	oData.append('chave', chave);
	oData.append('stat', stt);

	error_alert(form, 'loading', '')
	fetch(pag_url + "altera_status", {
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
				if (stt == 'A') {
					texto.classList.add('btn-success');
					texto.classList.remove('btn-warning');
				} else {
					texto.classList.remove('btn-success');
					texto.classList.add('btn-warning');
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
function reset_senha(v) {
	$("#modal_reset_senha").modal('show');
	document.getElementById("btn_conf_senha").setAttribute('data-chave', v);
}
function conf_resetar_senha(e) {

	chave = e.getAttribute("data-chave");
	oData = new FormData();
	oData.append('chave', chave);

	error_alert('#modal_reset_senha', 'loading', '')
	fetch(pag_url + "conf_altera_senha", {
		method: 'POST',
		body: oData,
	}).then(response => response.json())
		.then(json => {
			toastr.clear();
			if (json.TOT > 0) {
				setTimeout(function () { error_alert('#modal_reset_senha', true, 'Senha resetada para: '+json.NOVA_SENHA); }, 600);
				$("#modal_reset_senha").modal('hide');
			} else {
				setTimeout(function () { error_alert('#modal_reset_senha', false, 'Erro ao resetar senha!!!'); }, 600);
			}
		})
		.catch(function (error) {
			toastr.clear();
			setTimeout(function () { error_alert('#modal_reset_senha', false, 'Erro ao alterar a senha!!!'); }, 600);
			console.log("ocorreu algum erro");
		});
}

const grupo = document.getElementById("GRUPO");
grupo.addEventListener("blur", (e) => {
	let val_grupo = e.target.value;	
	monta_equipe(val_grupo, '');
})

function monta_equipe(val_grupo, val) {
	
	let cod_grupo = document.querySelector("#GRUPO option[value='" + val_grupo + "']").dataset.cod;
	var equipe = document.getElementById("EQUIPE_ID");
	equipe.value=val;
	if (['980', '960'].includes(cod_grupo)) {
		equipe.closest('div').style.display = "block";
		equipe.setAttribute('required', true);
		
		var allOpt = document.querySelectorAll('#EQUIPE_ID option');
		allOpt.forEach((i, v) => {
			i.style.display = "none";
			if(cod_grupo == '960' && (i.dataset.cod) == '970'){
				i.style.display = "block";
			}else if(cod_grupo == '980' && (i.dataset.cod) == '990'){
				i.style.display = "block";
			}else if(i.dataset.cod == ''){
				i.style.display = "block";
			}
		})

	}else{		
		equipe.closest('div').style.display = "none";
		equipe.removeAttribute('required');
	}
}
var apos_editar = function (formu, dados) {
	monta_equipe((dados.GRUPO), (dados.EQUIPE_ID));
};
var apos_cancelar = (formu) => {
	monta_equipe('', '');
};
var apos_cadastrar_alterar = function (formu) {
	monta_equipe('', '');
};

var apos_excluir = function (formu) {
	monta_equipe('', '');
};