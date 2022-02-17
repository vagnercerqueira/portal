let clearPosIns = null;
let focusIni = 1;
var acaoAtualForm = {};

formToModal();

function cria_barra_load(pai) {
	var barLoad = '<div class="progress-bar progress-bar-striped progress-bar-animated" style="width:100%">Carregando...</div>';
	var progress = document.createElement("div");
	setAttributes(progress, { 'class': 'progress', 'style': 'width:100%;display:none;' });
	pai.insertBefore(progress, pai.childNodes[0]);
	progress.innerHTML = barLoad;
}

//document.form_crud

function formToModal() {
	var fcm = document.querySelectorAll(".form_crud_modal");
	if (fcm.length > 0) {
		var modalHeader = '<div class="modal-header" style="padding:0.5em;"> <h4 class="modal-title">' + ($('.titulo_pag').html()) + '</h4><button onclick="bt_limpaForm(this)" type="reset" class="close" data-dismiss="modal" style="font-size:2em;">&times;</button></div>';
		for (var i = 0; i < fcm.length; i++) {
			var frm = fcm[i].closest("form");
			acaoAtualForm[frm.id] = null;
			if (frm.getAttribute("id") === null) {
				alert("SEU FORMULARIO NAO POSSUI ID, funcao: formToModal"); return false;
			}
			setAttributes(frm, { 'onsubmit': 'return formCrud(this)' });
			var ccnt_frm = fcm[i];
			ccnt_frm.style.display = 'block';
			fcm[i].remove();
			newItem = document.createElement("div");
			setAttributes(newItem, { 'class': 'modal fade', 'id': "modal_" + (frm.id), 'data-backdrop': 'static' });
			frm.insertBefore(newItem, frm.childNodes[0]);
			var modalBody = '<div class="modal-body">' + ccnt_frm.outerHTML + '</div>';
			newItem.innerHTML = '<div class="modal-dialog modal-xl modal-dialog-scrollable  modal-dialog-centered"><div class="modal-content">' + modalHeader + modalBody + '</div></div>';
		}
	}
	var fc = document.querySelectorAll(".form_crud");
	if (fc.length > 0) {
		for (var i = 0; i < fc.length; i++) {
			var frm = fc[i].closest("form");
			acaoAtualForm[frm.id] = null;
			setAttributes(frm, { 'onsubmit': 'return formCrud(this)' });
		}
	}
}
var ativa_form = (form) => {
	if (form.querySelector(".form_crud") !== null) {
		form.querySelector(".col_btn_novo").style.display = 'none';
		form.querySelector(".form_crud").style.display = 'block';
	}
}
var desativa_form = (form) => {
	if (form.querySelector(".form_crud") !== null) {
		//	form.querySelector(".dataTables_wrapper").style.display = 'block';
		form.querySelector(".col_btn_novo").style.display = '';
		form.querySelector(".form_crud").style.display = 'none';
	}
}
var bt_novo = function (e) {
	form = e.closest('form');

	acaoAtualForm[form.getAttribute('id')] = 'cadastrar';
	form.getAttribute
	$("#modal_" + (form.getAttribute('id'))).modal('show'); // QUANDO FOR VERSAO NOVA DO BOOTSTRAP, SUBSTITUIR ESSA LINHA POR JAVASCRIPT, POIS ESTA EM JQUERY
	x = form.querySelectorAll('input, select, textarea');
	ativa_form(form);
	setTimeout(function () { x[focusIni].focus(); }, 500);
	limparFormulario(form);
	pre_novo(form);
}
var bt_limpaForm = (e) => {
	form = e.closest('form');
	acaoAtualForm[(form.getAttribute('id'))] = null;
	$('#modal_' + (form.getAttribute('id'))).modal('hide');//FUTURAMENTE MUDAR DE QUERY PARA JAVASCRIPT
	desativa_form(form);
	limparFormulario(form);
	apos_cancelar(form);
	//atualiza_Datatable(form);
}
var limparFormulario = (form) => {
	form.querySelector("button[type=submit]").innerHTML = 'Incluir';
	form.reset();
	fieldslimpa = form.querySelectorAll('input[type=hidden], textarea');
	fieldslimpa.forEach((v, i) => fieldslimpa[i].value = '');
}

//******************************FUNÇÃO AJAX***************************************
var HabDesBtnForm = (form, hd) => {
	btDis = form.querySelectorAll("button[type=submit], button[type=reset]");
	btDis.forEach((v, i) => (hd == 'D') ? setAttributes(btDis[i], { 'disabled': true }) : btDis[i].removeAttribute("disabled"));
}

var formCrud = (form) => {
	var formElement = document.forms.namedItem((form.getAttribute('id')));
	oData = new FormData(formElement);
	ajax_crud(form, oData);
	return false;
}
var formCrud_pre_ajax = (form, oData) => {
	//form.querySelector('.progress').style.display = 'block';
	if (acaoAtualForm[(form.getAttribute('id'))] === 'cadastrar' || acaoAtualForm[(form.getAttribute('id'))] === 'alterar') {
		oData = (acaoAtualForm[(form.getAttribute('id'))] === "cadastrar") ? pre_cadastrar(form, oData) : pre_alterar(form, oData);
		oData = pre_cadastrar_alterar(form, oData);
	}
	return oData;
}
var formCrud_pos_ajax = (form, response) => {
	//form.querySelector('.progress').style.display = 'none';
	tempo = 1500;
	x = document.getElementById((form.getAttribute('id'))).querySelectorAll('input, select, textarea');
	if (acaoAtualForm[(form.getAttribute('id'))] === 'cadastrar' || acaoAtualForm[(form.getAttribute('id'))] === 'alterar') {
		if (response.sucesso) {
			atualiza_Datatable(form);
			if (clearPosIns == 'N') {//SE NAO PERMITIR LIMPAR CAMPOS APOS INSERT/UPDATE
				fields_clear = form.querySelectorAll(".clearPosIns");
				for (var i = 0; i < fields_clear.length; i++)
					setAttributes(fields_clear[i], { 'value': '' });
			} else {
				limparFormulario(form);
			}

			if (acaoAtualForm[(form.getAttribute('id'))] === "cadastrar") {
				apos_cadastrar(form)
				tempo = 2000;
			} else {
				apos_alterar(form);
				desativa_form(form);
				$('#modal_' + (form.getAttribute('id'))).modal('hide');
			}
			apos_cadastrar_alterar(form); //FUNCAO SOMENTE APOS CADASTRAR E ALTERAR
			PostAcoes(form);// FUNCAO INDEPENDENTE DA ACAO
		} else {
			console.log(response.list_erros);
			if(response.list_erros === undefined){
				error_alert(form, false, response.msg);
				return;
			}
			const campos = Object.keys(response.list_erros);
			
			obj = response.list_erros;

			for (let prop in obj) {
				field = document.getElementById(prop);
				field.setCustomValidity(obj[prop]);
				field.reportValidity();
				field.style.backgroundColor = 'pink'
				setTimeout(function () { field.setCustomValidity(''); field.style.backgroundColor = '' }, 3000);
				return;
			}
			if (acaoAtualForm[(form.getAttribute('id'))] == "cadastrar") {
				x[focusIni].focus();
			}
		}
	} else if (acaoAtualForm[(form.getAttribute('id'))] == 'editar') {
		if (response.sucesso === true) {
			acaoAtualForm[(form.getAttribute('id'))] = 'alterar';
			$('#modal_' + (form.getAttribute('id'))).modal('show');
			ativa_form(form);
			const campos = Object.keys(response.row)
			campos.forEach(key => {
				var field = (form.querySelector("[name=" + key + "]"));

				if (field !== null) {

					switch (field.type) {
						case 'select-multiple':
							ar = ((response.row[key]).split(","));
							for (var i = 0, l = field.options.length, o; i < l; i++) {
								o = field.options[i];
								if (ar.indexOf(o.value) != -1)
									o.selected = true;
							}
							break;
						case ('radio'):
						case ('checkbox'):
							radios = (form.querySelectorAll("input[name=" + key + "]"));
							ar = ((response.row[key]).split(","));
							for (var i = 0; i < radios.length; i++) {
								if (ar.indexOf(radios[i].value) != -1)
									radios[i].checked = true;
							}
							break;
						case ('file'):
							break;
						default:

							field.value = response.row[key];
					}
				} else {
					if (debug)
						console.log("CAMPO COM NOME: " + key + " NAO EXISTE NO FORMULARIO, funcao: formCrud_pos_ajax")
				}
			});
			apos_editar(form, response.row);
			form.querySelector("button[type=submit]").innerHTML = 'Alterar';
			$('#modal_' + (form.getAttribute('id'))).modal('show');

			PostAcoes(form, 'editar');
			return false;
		} else {
			tempo = 1500;
			desativa_form(form);
			limparFormulario(form);
		}

		setTimeout(function () { x[focusIni].focus(); }, 500);

		//return false;
	} else if (acaoAtualForm[(form.getAttribute('id'))] == 'excluir') {
		if (response.sucesso) {
			atualiza_Datatable(form);
			$('.bs-delete-modal-sm_' + (form.getAttribute('id'))).modal('hide');
			desativa_form(form);
			(form.querySelector('.bs-delete-modal-sm_' + (form.getAttribute('id')))).remove();
			acaoAtualForm[(form.getAttribute('id'))] = null;
			limparFormulario(form);
			apos_excluir(form);
			tempo = 2000;
		}
	}
	setTimeout(function () { error_alert(form, response.sucesso, response.msg, tempo); }, 500);
}
var ajax_crud = (form, oData) => {	
	HabDesBtnForm(form, 'D');
	oData.append("form_", (form.getAttribute('id')));
	oData.append("myacao", acaoAtualForm[(form.getAttribute('id'))]);
	formCrud_pre_ajax(form, oData);

	error_alert(form, 'loading', '')
	fetch(pag_url + "crud", {
		method: 'POST',
		body: oData,
	})
	
	.then(response => {		
		if(response.redirected){
			alert("Sessao expirada");
			window.location.href = base_url;
		}
		return response.json()
	})
	//	.then(response => response.json())
		.then(response => {
			toastr.clear();
			formCrud_pos_ajax(form, response);
		})
		.catch(err => {
			toastr.clear();
			if (acaoAtualForm[(form.getAttribute('id'))] == 'editar') {
				desativa_form(form);
				limparFormulario(form);
			}			
			console.log(err)
		})
		.finally(() => {			
			HabDesBtnForm(form, 'H');
		});
	return false;
}

function atualiza_Datatable(form) {

	tb = form.querySelectorAll(".dataTable");

	if (tb.length > 0) {

		tbScroll = form.querySelectorAll(".dtr-inline"); //quando colocamos "scrollCollapse": true na datatable
		tb = tbScroll.length == 0 ? tb[0].getAttribute('id') : tbScroll[0].getAttribute('id');
		console.log(tb);
		//tb = tb == undefined ? form.querySelector(".dtr-inline").getAttribute('id') : tb;
		if (tb === undefined)
			alert("Data table nao atualzou: O atributo table nao existe na sua tabela do formulario: " + (form.getAttribute('id')));
		else {
			var t = eval(tb);
			t.ajax.reload();
		}
	} else {
		console.log("TABELA N EXISTE NO SEU FORMULARIO");
	}
}

var editViwer = (key, ele, ac) => {

	form = ele.closest('form');
	acaoAtualForm[(form.getAttribute('id'))] = 'editar';
	(form.querySelector("button[type='submit']").closest('div')).style.display = (ac == 'V' ? 'none' : 'block');
	oData = new FormData();
	oData.append("chave", key);
	ajax_crud(form, oData, 'editar');
}
var excluir = (key, ele) => {
	form = ele.closest('form');
	col = form.querySelector('.dataTable thead  th').innerHTML;
	info = (ele.closest("tr")).querySelector('td').innerHTML;
	newItem = document.createElement("div");

	setAttributes(newItem, { 'class': 'modal fade bs-delete-modal-sm_' + (form.getAttribute('id')), 'data-backdrop': 'static' });

	form.insertBefore(newItem, form.childNodes[0]);
	var modalBody = '<div class="modal-dialog modal-sm">' +
		'<div class="modal-content">' +
		'<div class="modal-body">' +
		'<p>Confirmar a exclusão deste registro?</p>' +
		'<span class="text-warning">' + col + ': ' + info + '</span>' +
		'<p id="msg_erro_exclusao" style="color: #d43f3a"></p>' +
		'</div>' +
		'<div class="modal-footer">' +
		'<div><button type="button" class="btn btn-primary" key="' + key + '" onclick="confirma_delete(this)">Confirmar</button>&nbsp;' +
		'<button type="button" class="btn btn-danger" onclick="cancela_delete(this)" data-dismiss="modal">Cancelar</button></div>' +
		'</div>' +
		'</div>' +
		'</div>';
	newItem.innerHTML = modalBody;
	$('.bs-delete-modal-sm_' + (form.getAttribute('id'))).modal('show');
}
var confirma_delete = (e) => {
	form = e.closest('form');
	acaoAtualForm[(form.getAttribute('id'))] = 'excluir';
	oData = new FormData();
	oData.append("chave", e.getAttribute('key'));
	ajax_crud(form, oData, 'excluir');
}
var cancela_delete = (e) => {
	form = e.closest('form');
	acaoAtualForm[(form.getAttribute('id'))] = null;
	(e.closest('.bs-delete-modal-sm_' + (form.getAttribute('id')))).remove();
}
var pre_novo = (form) => { };
var pre_cadastrar = function (formu, dados) { return dados; }
var pre_alterar = function (formu, dados) { return dados; }
var pre_cadastrar_alterar = function (formu, dados) { return dados; }

var apos_cadastrar = function (formu) { };
var apos_alterar = function (formu) { };
var apos_cadastrar_alterar = function (formu) { };
var apos_editar = function (formu, dados) { };
var apos_excluir = function (formu) { };
var apos_cancelar = (formu) => { };
var PostAcoes = function (formu, acao) { };