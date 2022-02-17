//-------------------------TAB DFV------------------------------------
var tableDfvs;
//$("input,  select, option, textarea", "#form_vendas").prop('readonly',true);
$("#OBS_TRATAMENTO_SUPERVISOR").prop('readonly',false);



function atualiza_tabela() {
	if (tableDfvs != undefined) tableDfvs.destroy();
	tableDfvs = $('#table_dfvs').DataTable({
		processing: true,
		info: true,
		paging: false,
		"scrollY": "200px",
		"scrollCollapse": true,
		pagingType: "full_numbers",
		ajax: {
			url: pag_url + "api_ceps",
			type: "POST",
			timeout: 15000,
			dataType: "json",
			data: function () { return $("#form_busca").serialize() }
		},
		"rowCallback": function (row, data) {
			if (data.tipo_viabilidade == "Viavel") {
				$('td:eq(9)', row).html("<i class='fas fa-wifi' style='font-size:20px;color:green'></i>");
			} else {
				$('td:eq(9)', row).html("<i class='fas fa-wifi' style='font-size:20px;color:red'></i>");
			}
		},
		"columnDefs": [
			{ className: "text-center", "targets": [0, 3, 9] }
		],
		"columns": [
			{
				"data": "uf",
			},
			{ "data": "municipio" },
			{ "data": "logradouro" },
			//{ "data": "cod_logradouro" },
			{ "data": "num_fachada" },
			{ "data": "complemento" },
			{ "data": "complemento2" },
			{ "data": "complemento3" },
			{ "data": "cep" },
			{ "data": "bairro" },
			{ "data": "tipo_viabilidade" },
			{ "data": "nome_cdo" },
			{ "data": "cod_logradouro" }
		]
	});
}

function limpa_tabela_dfv() {
	document.querySelector(".table_files_dfv tbody").innerHTML = "";
	document.getElementById('form_upload_dfv').reset();
}
function limpa_relogio_dfv() {
	document.querySelector(".timer_upload_ini_dfv").innerHTML = "";
	document.querySelector(".timer_upload_fim_dfv").innerHTML = "";
	document.querySelector('.row_msg_upload_dfv').innerHTML = "";
}

function valida_files_dfv(file) {
	let tab = document.querySelector(".table_files_dfv tbody");
	tab.innerHTML = "";
	var fileInput = document.getElementById("files_dfv");
	var files = fileInput.files;
	var formato = (files[0].name).split('.')[1];

	if (!(['ZIP', 'zip'].includes(formato))) {
		error_alert("#form_upload_dfv", false, ((files[0].name) + ", formato de arquivo invalido. Aceita somente .zip "), 1000);
		document.getElementById('form_upload_dfv').reset();
	} else {
		tab.innerHTML = "<tr><td>Arquivo: " + files[0].name + "</td></tr>";
	}
}

const form_upload_dfv = document.getElementById('form_upload_dfv');
//form_upload_dfv.addEventListener('submit', uploadSubmitDfv);

async function uploadSubmitDfv(event) {
	event.preventDefault();
	var fileInput = document.getElementById("files_dfv");
	var files = fileInput.files;

	if (files.length > 0) {

		document.querySelector('.timer_upload_ini_dfv').innerHTML = "<i class='fas fa-hourglass'></i>&nbsp;Inicio: " + relogio();
		console.log('inicio sobe: ' + relogio());
		error_alert("#form_upload_dfv", 'loading', 'Fazendo upload dos arquivos...!!!', 1000);
		let data = new FormData(document.getElementById('form_upload_dfv'));
		fetch(pag_url + 'upload_dfv', {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		})
			.then((json) => json.json())
			.then(function (resp) {
				if (resp.error == 1) {
					toastr.clear();
					error_alert("#form_upload_dfv", false, (resp.message), 3000);
					setTimeout(() => {
						toastr.clear();
					}, 3000);
				}
				else {
					execFiles_Dfv();
				}
			})
			.catch(function (error) {
				error_alert("#form_upload_dfv", false, "Erro no servidor ao fazer upload, ", 1000);
				toastr.clear();
			}).finally(function () {
				console.log('termino sobe: ' + relogio());
			});
	} else {
		alert('obrigatorio o arquivo');
	}
}

function execFiles_Dfv() {
	console.log('inicio le csv: ' + relogio());
	error_alert("#form_upload_dfv", 'loading', 'Fazendo upload dos arquivos...!!!', 1000);
	fetch(pag_url + 'leCsvsDfv')
		.then((resp) => resp.json())
		.then(function (data) {
			toastr.clear();
			if (data.error == 0) {
				error_alert("#form_upload_dfv", true, 'Base dfv atualizada com sucesso!!!', 1000);
				document.querySelector(".table_files_dfv tbody").innerHTML = "";
				document.getElementById('form_upload_dfv').reset();
			} else {
				error_alert("#form_upload_dfv", false, 'Erro ao processar: ' + (data.message), 1000);
			}
		}).catch(function (error) {
			error_alert("#form_upload_dfv", false, 'Erro no servidor', 1000);
			toastr.clear();
		}).finally(function () {
			console.log('le csv: ' + relogio());
			document.querySelector('.timer_upload_fim_dfv').innerHTML = "<i class='far fa-hourglass'></i>&nbsp;Termino: " + relogio();
		});
}

function relogio() {
	var today = new Date();
	let sec = today.getSeconds();
	let min = today.getMinutes();
	let hr = today.getHours();
	var hrCompl = (hr < 10 ? '0' : "") + hr + ":" + (min < 10 ? '0' : "") + min + ":" + (sec < 10 ? '0' : "") + sec;
	return hrCompl;
}
/*-------------------TAB VENDA MANUAL----------------*/
//#DATATABLE
var tableBasicas = initDataTable({
	idTab: 'tableBasicas', alinhamento: {
		"className": 'text-center',
		//"width": "20%",
		"targets": [0, 1, 2, 3],
	},
	"columns": [
		{"data": "teste",},
		{ "data": "auditoria" },
		{ "data": "dt_agendamento" },
		{ "data": "dt_reagendamento_1" },
		{ "data": "dt_reagendamento_2" },
		{ "data": "dt_reagendamento_3" },
		{ "data": "status_bov" },
		{ "data": "desc_status_ativacao" },
		{ "data": "dt_venda_csv" },
		{ "data": "num_os" },
		{ "data": "cpf_cnpj_csv" },
		{ "data": "nome_cliente" },
	  ],
	order: [8, 'desc']
});
//#MASCARAS
$('#FATURAMENTO').mask('#.##0,00', { reverse: true });
$('#NUM_OS').mask('0-00000000000');
$('#DT_NASCIMENTO_CSV').mask('00/00/0000');
$('#CONTATO_PRINCIPAL_CSV').mask('(99) 999999999');
$('#CONTATO_SECUNDARIO_CSV').mask('(99) 999999999');
$('#CEP_INSTALACAO_CSV').mask('99.999-999');
$('#NUM_INSTALACAO_CSV').mask('00000');
$('#PAG_AGENCIA_CSV').mask('0000');
$('#PAG_CONTA_CSV').mask('00000000000');
$('#PAG_AGENCIA_DIGITO_CSV').mask('0');
$('#PAG_OPERACAO_CSV').mask('0000');
$('input[type=date]').keydown(function (event) {
	return false;
});

//$('#num_os').validate({ debug: true });

$('#CPF_CNPJ_CSV').mask('000.000.000-00', {
	onKeyPress: function (cpfcnpj, e, field, options) {
		const masks = ['000.000.000-000', '00.000.000/0000-00'];
		const mask = (cpfcnpj.length > 14) ? masks[1] : masks[0];
		$('#CPF_CNPJ_CSV').mask(mask, options);
	}
});
//#VALIDADORES
document.querySelector('#CPF_CNPJ_CSV').addEventListener("blur", function () {
	let cpf_cnpj = document.querySelector('#CPF_CNPJ_CSV');
	let genero = document.querySelector('#GENERO_CSV');
	let dt_nascimento = document.querySelector('#DT_NASCIMENTO_CSV');
	let rg = document.querySelector('#RG_CSV');
	let mae = document.querySelector('#NOME_MAE_CSV')

	if (cpf_cnpj.value.length == 14) {
		genero.required = true;
	//	dt_nascimento.required = true;
	//	rg.required = true;
	//	mae.required = true;
	}
	else {
		genero.required = false;
	//	dt_nascimento.required = false;
	//	rg.required = false;
	//	mae.required = false;
	}
});
//Campos requeridos caso a forma de pagamento seja debito
document.querySelector('#FORMA_PAGAMENTO_CSV').addEventListener("change", function () {
	let forma_pagamento = document.querySelector('#FORMA_PAGAMENTO_CSV');
	let selecionado = forma_pagamento.options[forma_pagamento.selectedIndex].text;
	let banco = document.querySelector('#PAG_BANCO_CSV');
	let agencia = document.querySelector('#PAG_AGENCIA_CSV');
	let conta = document.querySelector('#PAG_CONTA_CSV');
	let digito = document.querySelector('#PAG_AGENCIA_DIGITO_CSV');
	if (selecionado == 'débito') {
		banco.required = true;
		agencia.required = true;
		conta.required = true;
		digito.required = true;
	}
	else {
		banco.required = false;
		agencia.required = false;
		conta.required = false;
		digito.required = false;
	}
});

//Campos requeridos campos o status de tratamento seja em tratamento
document.querySelector('#STATUS_TRATAMENTO').addEventListener("change", function () {
	//console.log("entreou"); return false;
	let status_tratamento = document.querySelector('#STATUS_TRATAMENTO');
	let selecionado = status_tratamento.value;
	let dt_retorno = document.querySelector('#DT_RETORNO_TRATAMENTO');
	let setor_tratamento = document.querySelector('#SETOR_RESP_TRATAMENTO');
	let obs_tratamento = document.querySelector('#OBS_TRATAMENTO_BKO');

	if (selecionado == 'ET') {
		dt_retorno.required = true;
		setor_tratamento.required = true;
		obs_tratamento.required = true;
	}
	else {
		dt_retorno.required = false;
		setor_tratamento.required = false;
		obs_tratamento.required = false;
	}
});

//Campos requeridos caso tenha TV
document.querySelector('#COMBO_CONTRATADO_CSV').addEventListener("change", function () {
	let combo_contratado = document.querySelector('#COMBO_CONTRATADO_CSV');
	let selecionado = combo_contratado.options[combo_contratado.selectedIndex].text;
	let plano_tv = document.querySelector('#PLANO_TV_CSV');

	if (selecionado == 'Oi Total Residencial') {
		plano_tv.required = true;
	}
	else {
		plano_tv.required = false;
	}
});

//#BUSCA FATURAMENTO
let faturamento = document.querySelectorAll('#BANDA_LARGA_VELOCIDADE_CSV, #PLANO_TV_CSV, #DT_ATIVACAO');
faturamento.forEach(fat => {
	fat.addEventListener("change", function (e) {
		let velocidade = document.querySelector('#BANDA_LARGA_VELOCIDADE_CSV').value;
		let tv = document.querySelector('#PLANO_TV_CSV').value;
		let faturamento = document.querySelector('#FATURAMENTO');
		let data_ativacao = document.querySelector('#DT_ATIVACAO').value;
		oData = new FormData();
		oData.append('velocidade', velocidade);
		oData.append('tv', tv);
		oData.append('data_ativacao', data_ativacao);
		fetch(
			pag_url + 'BuscaFaturamento', {
			method: 'POST',
			body: oData
		})
			.then(function (response) {
				response.json().then(function (data) {
					faturamento.value = data.faturamento;
				});
			});
	})
})

//#BUSCA VENDEDOR
/*document.querySelector('#EQUIPE').addEventListener("change", function () {
	//function Procura_outros(){
	let supervisor = document.querySelector('#SUPERVISOR');
	let id_supervisor = document.querySelector('#ID_SUPERVISOR');
	let vendedor = document.querySelector('#ID_VENDEDOR');
	oData = new FormData();
	oData.append('id', this.value);
	fetch(
		pag_url + 'ProcuraSupervisor', {
		method: 'POST',
		body: oData
	})
		.then(function (response) {
			response.json().then(function (data) {
				//console.log(data.vendedor); return false;
				supervisor.value = data.supervisor;
				vendedor.innerHTML = data.vendedor;
				id_supervisor.value = data.id_supervisor;
			});
		});
});*/

$('textarea').keyup(function () {
	var id = this.id;
	var characterCount = $(this).val().length;
	if (id == "OBS_ATIVACAO") {
		var current = $('#current_ativacao');
	}
	if (id == "OBS_TRATAMENTO_BKO") {
		var current = $('#current_bko');
	}
	if (id == "OBS_TRATAMENTO_SUPERVISOR") {
		var current = $('#current_supervisor');
	}
	current.text(characterCount);
});

//Busca Cep
document.querySelector('#CEP_INSTALACAO_CSV').addEventListener("blur", function () {
	pesquisacep(document.querySelector('#CEP_INSTALACAO_CSV').value); //NOVA FUNCAO PARA BUSCAR O CEP VIA WEB
})
function pesquisacep(valor) {
	//Nova variavel "cep" somente com digitos.
	var cep = valor.replace(/\D/g, '');
	//Verifica se campo cep possui valor informado.
	if (cep != "") {
		//Expressão regular para validar o CEP.
		var validacep = /^[0-9]{8}$/;
		//Valida o formato do CEP.
		if (validacep.test(cep)) {
			//Preenche os campos com "..." enquanto consulta webservice.
			document.querySelector('#LOGRADOURO_INSTALACAO_CSV').value = '';
			document.querySelector('#BAIRRO_INSTALACAO_CSV').value = '';
			document.querySelector('#CIDADE_INSTALACAO_CSV').value = '';
			document.querySelector('#UF_INSTALACAO_CSV').value = '';
			//Cria um elemento javascript.
			var script = document.createElement('script');
			//Sincroniza com o callback.
			script.src = 'https://viacep.com.br/ws/' + cep + '/json/?callback=meu_callback';

			//Insere script no documento e carrega o conteudo.
			document.body.appendChild(script);
		} //end if.
		else {
			console.log('cep invalido');
		}
	} //end if.
	else {
		document.querySelector('#LOGRADOURO_INSTALACAO_CSV').value = '';
		document.querySelector('#BAIRRO_INSTALACAO_CSV').value = '';
		document.querySelector('#CIDADE_INSTALACAO_CSV').value = '';
		document.querySelector('#UF_INSTALACAO_CSV').value = '';
	}
};
function meu_callback(conteudo) {
	if (!("erro" in conteudo)) {
		document.querySelector('#LOGRADOURO_INSTALACAO_CSV').value = conteudo.logradouro;
		document.querySelector('#BAIRRO_INSTALACAO_CSV').value = conteudo.bairro;
		document.querySelector('#CIDADE_INSTALACAO_CSV').value = conteudo.localidade;
		document.querySelector('#UF_INSTALACAO_CSV').value = conteudo.uf;
	}
	else {
		document.querySelector('#LOGRADOURO_INSTALACAO_CSV').value = '';
		document.querySelector('#BAIRRO_INSTALACAO_CSV').value = '';
		document.querySelector('#CIDADE_INSTALACAO_CSV').value = '';
		document.querySelector('#UF_INSTALACAO_CSV').value = '';
		console.log('CEP nao encontrado.');
	}
}
var apos_editar = function (formu, dados) {
	//Controle  dos disabled de reagendamentos
	agendamento_reagendamento();
	/*FIM*/
	let vendedor = document.querySelector('#ID_VENDEDOR');
	vendedor.innerHTML = dados.VENDEDORES;
	vendedor.value = dados.ID_VENDEDOR;

};
var pre_cadastrar_alterar = function (formu, dados) {
	let nascim_corrente = dados.get('DT_NASCIMENTO_CSV');
	
	if( nascim_corrente != "" ){
	let ano = nascim_corrente.substr(-4);
	let mes = nascim_corrente.substr(3, 2);
	let dia = nascim_corrente.substr(0, 2);
	let nascimento_completo = ano + "-" + mes + "-" + dia;
	dados.set('DT_NASCIMENTO_CSV', nascimento_completo);
	}
	return dados;

}
document.getElementById('AUDIO_AUDIT_QUALITY_1').addEventListener('change', (ele) => {
	valida_files('AUDIO_AUDIT_QUALITY_1');
});
document.getElementById('AUDIO_AUDIT_QUALITY_2').addEventListener('change', (ele) => {
	valida_files('AUDIO_AUDIT_QUALITY_2');
});
function valida_files(campo) {
	var fileInput = document.getElementById(campo);
	var fileInput_1 = document.getElementById('AUDIO_AUDIT_QUALITY_1');
	var fileInput_2 = document.getElementById('AUDIO_AUDIT_QUALITY_2');
	if (fileInput_1.files.length > 0 && fileInput_2.files.length > 0) {
		if (fileInput_1.files[0].name == fileInput_2.files[0].name) {
			fileInput_2.value = '';
			error_alert("#form_vendas", false, "Audios iguais");
			return;
		}
	}

	var file = fileInput.files[0];
	if (file.size > 6032304) { //5,8 mb
		fileInput.value = '';
		error_alert("#form_vendas", false, "Tamanho de arquivo excede 5,5 mb");
	}
}

function atualiza_zap(arg, arg2) {
	oData = new FormData();
	oData.append('id', arg);
	oData.append('tipo', arg2);
	fetch(
		pag_url + 'atualiza_zap', {
		method: 'POST',
		body: oData
	})
		.then(function (response) {
			response.json().then(function (data) {
				let contato = data.telefone;
				let mensagem = data.mensagem;
				tableBasicas.draw();
				error_alert(".whatsapp", true, "Finalize o envio de mensagem!");
				window.open("https://api.whatsapp.com/send?phone=55" + contato + "&text=" + mensagem, "_blank");
			});
		});
}
function agendamento_reagendamento() {
	let dt_gendamento = document.querySelector("#DT_AGENDAMENTO");
	let turno_agendamento = document.querySelector("#TURNO_AGENDAMENTO");

	let dt_reagendamento1 = document.querySelector("#DT_REAGENDAMENTO_1");
	let turno_reagendamento1 = document.querySelector("#TURNO_REAGENDAMENTO_1");

	let dt_reagendamento2 = document.querySelector("#DT_REAGENDAMENTO_2");
	let turno_reagendamento2 = document.querySelector("#TURNO_REAGENDAMENTO_2");

	let dt_reagendamento3 = document.querySelector("#DT_REAGENDAMENTO_3");
	let turno_reagendamento3 = document.querySelector("#TURNO_REAGENDAMENTO_3");

	if (dt_gendamento.value !== "") {
		dt_reagendamento1.disabled = false;
		turno_reagendamento1.disabled = false;

	}

	if (dt_reagendamento1.value !== "") {
		dt_reagendamento2.disabled = false;
		turno_reagendamento2.disabled = false;

	}

	if (dt_reagendamento2.value !== "") {
		dt_reagendamento3.disabled = false;
		turno_reagendamento3.disabled = false;

	}
}

function gerar_pdf(arg) {
	if (arg === "") {
		alert("Nenhuma os selecionada!!!");
		return;
	}
	window.open(pag_url + 'gerar_pdf?id=' + arg, 'formresult', 'scrollbars=no,menubar=no,height=600,width=800,resizable=yes,toolbar=no,status=no');
}

document.getElementById("form_expo_vendas").addEventListener('submit', (e) => {
	e.preventDefault();
	let perido_inicial = document.querySelector("#expo_per_ini").value;
	let perido_final = document.querySelector("#expo_per_fim").value;
	window.open(pag_url + 'exportarVendas?expo_per_ini=' + perido_inicial + '&expo_per_fim=' + perido_final);
});


