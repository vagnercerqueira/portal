var debug = false;

$('[data-toggle="tooltip"]').tooltip();
$("a[href='" + window.location.href + "']").addClass('active');
$("a[href='" + window.location.href + "']").parents('.has-treeview').each(function () {
	//$(this).show();
	$(this).addClass('menu-open');
	$(this).find('a:first').addClass('active');
	//$(this).prev().attr('aria-expanded','true').removeClass('collapsed');
});

var body = $('body');

$(".alterar_senha").click(function () {
	trocar_senha()
});

function trocar_senha() {
	$('.altera_senha-modal-sm').remove();
	$("body").append('<div class="modal altera_senha-modal-sm fade" data-backdrop="static">' +
		'<div class="modal-dialog modal-sm">' +
		'<div class="modal-content">' +
		'<div class="modal-body"><form name="form_reset_formulario" id="form_reset_formulario">' +
		'<p><h3 class="center">ALTERAR SENHA</h3><span style="color: red" id="altsenhaprimacc"></span></p>' +
		'<p><input type="password" class="form-control center" id="senha_atual" name="senha_atual" required maxlength="255" placeholder="Senha Atual"/></p>' +
		'<p><input type="password" class="form-control center" id="senha_nova" name="senha_nova" required maxlength="255" placeholder="Senha Nova"/></p>' +
		'<p><input type="password" class="form-control center" id="confirma_senha_nova" name="confirma_senha_nova" required maxlength="255" placeholder="Confirma Senha Nova"/></p>' +
		'</form><span class="error_altera_senha" style="color: red;"></span></div>' +
		'<div class="modal-footer">' +
		'<div><button type="button" class="btn btn-primary" onclick="altera_senha()">Confirmar</button>&nbsp;' +
		'<button type="reset" class="btn btn-danger " data-dismiss="modal">Cancelar</button></div>' +
		'</div>' +
		'</div>' +
		'</div>' +
		'</div>'
	);
	$('.altera_senha-modal-sm').modal('show');
}

function modal_loading(t) { //H = hide S= show
	if (t == 'S') {
		$('.loadingM-modal-sm').remove();
		$("body").append('<div class="modal loadingM-modal-sm" data-backdrop="static">' +
			'<div class="modal-dialog modal-sm">' +
			'<div class="modal-content">' +
			'<div class="modal-body">' +
			'<div class="text-center">' +
			'  <div class="spinner-border" role="status">' +
			'	<span class="sr-only">Loading...</span>' +
			'  </div>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>' +
			'</div>'
		);
		$('.loadingM-modal-sm').modal('show');
	} else {
		$('.loadingM-modal-sm').modal('hide');
		$('.loadingM-modal-sm').remove();
	}
}

function altera_senha() {

	/*var senha_atual = ($("#senha_atual").val()).trim();
	var senha_nova = ($("#senha_nova").val()).trim();
	var confirma_senha_nova = ($("#confirma_senha_nova").val()).trim();*/
	var form = document.forms['form_reset_formulario'];
	var senha_atual = document.getElementById("senha_atual");
	var senha_nova = document.getElementById("senha_nova");
	var conf_senha_nova = document.getElementById("confirma_senha_nova");

	if (form.reportValidity()) {
		if (senha_nova.value != confirma_senha_nova.value) {
			conf_senha_nova.setCustomValidity('Senha e confirma senha estao diferente!!!');
			form.reportValidity();
			setTimeout(function () { conf_senha_nova.setCustomValidity(''); }, 2000);
		}
		else {
			error_alert(form, 'loading', '');
			oData = new FormData(form);
			document.querySelector('.altera_senha-modal-sm button[type=button]').disabled = true;
			if (qtd_acesso > 1)
				document.querySelector('.altera_senha-modal-sm button[type=reset]').disabled = true;

			fetch(base_url + "/login/altera_senha", {
				method: 'POST',
				body: oData,
			})
				.then(response => response.json())
				.then(json => {
					toastr.clear();
					if (json.sucesso == false) {
						msg = json.msg;
						if ((typeof msg) == 'object') {
							for (let obj in msg) {
								console.log(obj);
								field = document.getElementById(obj);
								field.setCustomValidity(msg[obj]);
								field.reportValidity();
								field.style.backgroundColor = 'pink'
								setTimeout(function () { field.setCustomValidity(''); field.style.backgroundColor = '' }, 3000);
								return;
							}
						};
					} else {
						error_alert(form, true, 'SENHA ALTERADA COM SUCESSO, VC SERA REDIRECIONADO PARA A TELA DE LOGIN!!!', 4000);
						setTimeout(function () { location.assign(base_url + "/login/logout"); }, 4000);
					}
				})
				.catch(function (error) {
					error_alert(form, false, 'Erro ao tentar alterar senha', 3000);
					console.log("ocorreu algum erro");
				})
				.finally(function () {
					document.querySelector('.altera_senha-modal-sm button[type=button]').disabled = false;
					if (qtd_acesso > 1)
						document.querySelector('.altera_senha-modal-sm button[type=reset]').disabled = false;
				});
		}
	}
	console.log(form.reportValidity()); return false;
}

$(".control-sidebar").on('click', 'div[class^="bg-"]', function () {

	navbarCor = corFundoOpaNav + " " + corFundoNav;

	const elem = document.querySelector('.nav-link');
	const css = getComputedStyle(elem);
	const color = css.color;

	var request = $.ajax({
		url: base_url + "login/altera_config",
		type: 'POST',
		dataType: 'text',
		data: { navbar: navbarCor, corfonte_navbar: color },
	});
	request.done(function (data) {
		//$('.nav-link').css('color', color+" !important ");
		//elem.style.removeProperty('color');
		//$('.nav-link').attr('style', 'color: '+color+' !important');
	});
	request.fail(function (jqXHR, textStatus) {
		alert("Requisicao falhou: " + textStatus);
	});

});
function monta_search() {
	var serachs = document.querySelectorAll(".monta_seach");
	serachs.forEach((e, i) => {
		var s = e.getAttribute('mysearch');
		var lista = JSON.parse(s);
		var req = (lista.REQUIRED) == undefined ? "required" : lista.REQUIRED;
		var mybody = '<input type="hidden" name="' + (lista.ID) + '" id="' + (lista.ID) + '" />' +
			'<label for="LISTA_' + (lista.ID) + '">' + (lista.LABEL) + '</label>' +
			'<div class="input-group">' +
			'<input ' + req + ' list="LISTA_' + (lista.ID) + '" id="DESC_' + (lista.ID) + '" name="DESC_' + (lista.ID) + '" class="form-control form-control-sm" placeholder="Buscar ' + (lista.LABEL) + '..." />' +
			'<div class="input-group-append">' +
			'<a href="javascript:;" onclick="clear_search(\'' + (lista.ID) + '\')" class="btn btn-default btn-sm btn-block btn_clear_list">' +
			'<i class="fa fa-times-circle"></i>' +
			'</a>' +
			'</div>' +
			'</div>' +
			'<datalist id="LISTA_' + (lista.ID) + '" name="LISTA_' + (lista.ID) + '">';
		//for (var [k, v] of Object.entries(lista.LIST)) {
		for (let i in lista.LIST) {
			v = (lista.LIST[i]);
			mybody += '<option value="' + (v.DESCRICAO) + '" val="' + (v.ID) + '"></option>';
		}
		mybody += "</datalist>";
		e.removeAttribute('mysearch');
		e.innerHTML = (mybody);
		autocomplete("DESC_" + (lista.ID), "#LISTA_" + (lista.ID), (lista.ID), lista.OUTLIST);
	})
}
var autocomplete = function (desc, lista, idTarg, outlist) {
	document.getElementById(desc).addEventListener("change", function (e) {
		obj = document.querySelector(lista + ' option[value="' + (document.getElementById(desc)).value + '"]');
		if (obj === null) {
			if (outlist !== 'T')
				document.getElementById(desc).value = '';
			document.getElementById(idTarg).value = '';
		} else {
			document.getElementById(idTarg).value = obj.getAttribute('val');
		}
		post_autocomplete(desc);
	});
}
function post_autocomplete(desc) { }
function atualiza_list(url, alvo, valW, sel) {
	$(alvo).html('');
	$("input[list=" + alvo.replace("#", "") + "]").val('');
	if (valW.trim() !== "") {
		var request = $.ajax({
			url: pag_url + url,
			type: 'POST',
			dataType: 'json',
			data: { filtro: valW },
		});
		request.done(function (data) {
			$.each(data, function (idx, obj) {
				$(alvo).append('<option value="' + obj + '" val="' + idx + '"></option>')
			});
			if (sel != "")
				$("input[list=" + (alvo.replace('#', "")) + "]").val(sel);
		});
		request.fail(function (jqXHR, textStatus) {
			alert("Requisicao falhou: " + textStatus);
		});
	}
}

function setAttributes(el, attrs) {
	for (var key in attrs)
		el.setAttribute(key, attrs[key]);
}

function error_alert(ele, cor, msg_valida, tempo) {
	/*if (tempo == undefined)
		tempo = 4000;*/
	var corErro = { false: 'bg-danger', true: 'bg-success', loading: 'bg-info' };
	toastr.options = {
		closeButton: true,
		debug: false,
		newestOnTop: true,
		//	progressBar: true ,
		preventDuplicates: true,
		//rtl: $('#rtl').prop('checked'),
		positionClass: 'toast-top-center-element',
		//positionClass: 'toast-top-full-width',
		//showMethod: "slideDown",
		//hideMethod: "hide",
		//preventDuplicates: $('#preventDuplicates').prop('checked'),
		onclick: null
	};

	if (cor === 'loading') {
		toastr.options.timeOut = 0; //para nao fechar o toast
		toastr.options.extendedTimeOut = 0;
		toastr.info('<b><h5>Processando aguarde</5></b>&nbsp;<i class="fa fa-spinner fa-pulse fa-1x fa-fw"></i>')
	} else {
		toastr.options.progressBar = true;
		toastr.options.timeOut = 3000; //para nao fechar o toast
		toastr.options.extendedTimeOut = 1000;
		toastr.options.showDuration = 300;
		toastr.options.hideDuration = 1000;
		toastr.options.hideDuration = 1000;
		if (cor === false)
			toastr.error("<h6>" + msg_valida + "</h6>")
		else
			toastr.success("<h6><b>" + msg_valida + "</b></h6>")
	}
}
var clear_search = (e) => {
	document.getElementById(e).value = '';
	document.getElementById('DESC_' + e).value = '';
	document.getElementById('DESC_' + e).focus();
	post_clear_list(e);
}

function post_clear_list(tar) { };

var post_datalist_cli = function () { };
function windows_open(funcao_url, formu) {
	form = document.getElementById(formu);
	form.action = pag_url + funcao_url;
	form.setAttribute("target", "formresult");
	window.open('', 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
	form.submit();
}

//*******************************************************/
function createModal(nmModal, titulo) {
	var fcm = document.getElementById(nmModal);
	if (fcm === null) {

		var modalHeader = '<div class="modal-header" style="padding:0.5em;"> <h4 class="modal-title">' + (titulo) + '</h4><button type="reset" class="close" data-dismiss="modal" style="font-size:2em;">&times;</button></div>';
		var newItem = document.createElement("div");
		setAttributes(newItem, { 'class': 'modal fade', 'id': nmModal, 'data-backdrop': 'static' });
		newItem.innerHTML = '<div class="modal-dialog modal-xl modal-dialog-scrollable  modal-dialog"><div class="modal-content">' + modalHeader + '<div class="modal-body"></div></div></div>';
		document.body.appendChild(newItem);
	}
	$("#" + nmModal).modal('show');

}

function formSearchGeral(f) {
	error_alert('', 'loading', '');
	oData = new FormData();
	oData.append('G', document.querySelector('input[name=inputformsearchgeral]').value);
	error_alert('', 'loading', '');
	fetch((f.dataset.pag), {
		method: 'POST',
		body: oData,
	})
		.then(response => response.json())
		.then(json => {
			toastr.clear();
			if (json.DADOS == "") {
				setTimeout(function () { error_alert('modal_formsearchgeral', false, 'Nenhum resultado encontrado!!!'); }, 500);
				return;
			}
			createModal('modal_formsearchgeral', 'Resultado da busca');
			document.querySelector("#modal_formsearchgeral .modal-body").innerHTML = json.DADOS;
		})
		.catch(function (error) {
			toastr.clear();
		});
	return false;
}

var tableCsv = (idTabela, exportBtn) => {
	const tableRows = document.querySelectorAll("#" + idTabela + " tr");
	//[...tableRows]    transforma o nodelist em array
	const csvString = Array.from(tableRows)
		.map(row => Array.from(row.cells)
			.map(cell => cell.textContent)
			.join(';')
		)
		.join('\n');
	exportBtn.setAttribute('href', `data:application/csv;charset=UTF-8,${encodeURIComponent(csvString)}`);
	exportBtn.setAttribute('download', 'table.csv');
}

function FsearchTb(tb, input) {
  var filter, table, tr, td, cell, i, j;
  input = document.querySelector(input);
  filter = input.value.toUpperCase();
  table = document.querySelector(tb);
  tr = table.getElementsByTagName("tr");
  for (i = 1; i < tr.length; i++) {
    tr[i].style.display = "none";
  
    td = tr[i].getElementsByTagName("td");
    for (var j = 0; j < td.length; j++) {
      cell = tr[i].getElementsByTagName("td")[j];
      if (cell) {
        if (cell.innerHTML.toUpperCase().indexOf(filter) > -1) {
          tr[i].style.display = "";
          break;
        } 
      }
    }
  }
}