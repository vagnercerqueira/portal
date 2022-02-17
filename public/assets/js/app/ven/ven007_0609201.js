var tableDfvs;

document.getElementById("form_busca").addEventListener('submit', (e)=>{
	e.preventDefault();
	atualiza_tabela();
});

function atualiza_tabela(){
	if (tableDfvs != undefined)  tableDfvs.destroy();
	tableDfvs = $('#table_dfvs').DataTable({
		processing: true,
		info: true,
		paging: false,
		"scrollY":        "200px",
        "scrollCollapse": true,		
		pagingType: "full_numbers",
		ajax: {
			url: pag_url + "consulta_dfvs",
			type: "POST",
			timeout: 15000,
			dataType: "json",
			data: function () { return $("#form_busca").serialize() }
		},

		"columns": [
			{ "data": "uf"},
			{ "data": "municipio" },
			{ "data": "logradouro" },
			//{ "data": "cod_logradouro" },
			{ "data": "num_fachada"  },
			{ "data": "complemento" },
			{ "data": "complemento2" },
			{ "data": "complemento3" },
			{ "data": "bairro"  },
			{ "data": "cep"  },
			{ "data": "nome_cdo"  },
			{ "data": "tipo_viabilidade" }
		]
	});
}


document.getElementById('files_dfv').addEventListener('change', (ele) => {
	let dados_files = valida_files();
	document.querySelector(".table_files thead").innerHTML = "<tr><th class='text-center'>Total de arquivos: " + dados_files.tot + "</th></tr>";
	document.querySelector(".table_files tbody").innerHTML = dados_files.html;
});
function openDialog() {
	document.getElementById('files_dfv').click();
	limpa_tabela();
	limpa_relogio();
}

document.querySelector('.cancel').addEventListener('click', (ele) => {
	limpa_tabela();
	limpa_relogio();
});

function limpa_tabela() {
	document.querySelector(".table_files tbody").innerHTML = "";
	document.querySelector(".table_files thead").innerHTML = "";
}
function limpa_relogio() {
	document.querySelector(".timer_upload_ini").innerHTML = "";
	document.querySelector(".timer_upload_fim").innerHTML = "";
	document.querySelector('.row_msg_upload').innerHTML = "";
}

function valida_files(file) {
	var fileInput = document.getElementById("files_dfv");
	var files = fileInput.files;
	let dados_files = { tot: 0, html: [] }
	var rows = "";
	//console.log(files);
	[...files].forEach((file, ind) => {
		var fname = (file.name).substr(0, 3);
		var cl = '';
		if (!(['DFV', 'dfv'].includes(fname)))
			cl = "class='text-danger arquivo_invalido'";
		dados_files.tot++;
		dados_files.html += `<tr><td style='padding: 0px;' ` + cl + `><smal>` + (file.name) + `</smal></td></tr>`;
	});
	return dados_files;
}

async function uploadFile() {
	let return_data = { error: 0, message: '' };
	let invalidos = document.querySelectorAll('.arquivo_invalido').length
	if (invalidos > 0) {
		error_alert("#form_upload", false, 'Remova da lista os arquivos com nomes invalidos em vermelho!!!', 1000);
		return false;
	}

	try {
		let data = new FormData(document.getElementById('form_upload'));
		let response = await fetch(pag_url + 'upload', {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		});

		if (response.status != 200)
			throw new Error('HTTP response code != 200');

		let json_response = await response.json();

		if (json_response.error == 1)
			throw new Error(json_response.message);
		else {
			let exFiles = await execFiles();
		}

	}
	catch (e) {
		return_data = { error: 1, message: e.message };
	}

	return return_data;
}
//---------------------------------------------------------

const form_upload = document.getElementById('form_upload');
form_upload.addEventListener('submit', uploadSubmit);

async function uploadSubmit(event) {
	event.preventDefault();
	let upload_res = await uploadFile();
}

async function execFiles() {

	//let upload_res = await uploadFile();
	console.log('chegou aqui');
	
}
/*async function uploadSubmit(event) {
	event.preventDefault();
	var fileInput = document.getElementById("files_dfv");
	var files = fileInput.files;
	if (files.length > 0) {
		error_alert("#form_upload", 'loading', 'Processando...!!!', 1000);
		document.querySelector('.timer_upload_ini').innerHTML = "<i class='fas fa-hourglass'></i>&nbsp;Inicio: " + relogio();
		let upload_res = await uploadFile();

		document.querySelector('.timer_upload_fim').innerHTML = "<i class='far fa-hourglass'></i>&nbsp;Termino: " + relogio();
		toastr.clear();

		if (upload_res.error == 0) {
			error_alert("#form_upload", true, 'Arquivos processados com sucesso', 700);
			document.querySelector('.row_msg_upload').innerHTML = `<div class="col-md-12"><div class="alert alert-success">Arquivos processados com sucesso</div></div>`;
			atualiza_tabela();

			var rejeitados = upload_res.rejeitados;
			let rows = "";
			limpa_tabela();
			document.getElementById('form_upload').reset();
			if (rejeitados.length > 0) {
				[...rejeitados].forEach(element => {
					rows += "<tr class='text-danger'><td style='padding: 0px;'>" + element + "</td><tr>";
				});
				document.querySelector(".table_files thead").innerHTML = "<tr><th class='text-center text-danger'>Arquivos com problema: " + (rejeitados.length) + "</th></tr>";
				document.querySelector(".table_files tbody").innerHTML = rows;
			}

			if ((upload_res.info_card_upload).length > 0) {
				document.querySelector(".qtd_carros").innerHTML = (upload_res.info_card_upload).length;
				document.querySelector(".ultima_data").innerHTML = (upload_res.info_card_upload[0].DT_ARQUIVO);
			}
		}
		else if (upload_res.error == 1) {
			error_alert("#form_upload", false, 'Falha ao processar arquivos', 700);
			document.querySelector('.row_msg_upload').innerHTML = `<div class="col-md-12"><div class="alert alert-danger">Erro: `+(upload_res.message)+`</div></div>`;
		}

	} else {
		error_alert("#form_upload", false, 'Nenhum arquivo encontrado!!!', 1000);
	}
	return false;
}*/

function relogio() {
	var today = new Date();
	var hora = today.getHours() + ":" + today.getMinutes() + ":" + today.getSeconds();
	return hora;
}