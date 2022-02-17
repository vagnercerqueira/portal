var tableLpgto;

document.getElementById("form_busca").addEventListener('submit', (e) => { 
	e.preventDefault();
	atualiza_tabela();
});

function atualiza_tabela() {
	cardTotal();
	if (tableLpgto != undefined) tableLpgto.destroy();
	tableLpgto = $('#table_lpgto').DataTable({
		processing: true,
		info: true,
		paging: false,
		"scrollY": "200px",
		"scrollCollapse": true,
		pagingType: "full_numbers",
		order: [3, 'desc'],

		ajax: {
			url: pag_url + "retorna_pgtos",
			type: "POST",
			timeout: 15000,
			dataType: "json",
			data: function () { return $("#form_busca").serialize() }
		},
		  "initComplete": function(settings, json) {
			  let tam = json.data.length;
			  let btn_exclui = document.querySelector(".btn_exclui");
			  let btn_exporta = document.querySelector(".btn_exporta");
			  btn_exclui.style.display = (tam > 0   ? 'block' : 'none');
			  btn_exporta.style.display = (tam > 0   ? 'block' : 'none');
			  
			  DataTableCsv("table_lpgto", document.querySelector(".btn_download"), false, "Linha_pgto");

		  },
		   /* "rowCallback": function( row, data ) {
				if ( data[1] == "A" ) {
				  $('td:eq(4)', row).html( '<b>A</b>' );
				}
			  },*/
		"columns": [
			{ "data": "cod_sap" },
			{ "data": "valor"},
			{ "data": "num_os" },
			{ "data": "data_instalacao" },
			{ "data": "filial" },
			{ "data": "ciclo" },
			{ "data": "quinzena" },
			{ "data": "cpf_cliente"},
			{ "data": "tipo" }
		]
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
//-------------------------TAB LINHA PGTO-----------------------------------------------
document.getElementById('files_linha_pgto').addEventListener('change', (ele) => {
	valida_files_linha_pgto();
	limpa_relogio_linha_pgto();
});
function openDialoglinha_pgto() {
	document.getElementById('files_linha_pgto').click();
	limpa_relogio_linha_pgto();
}

document.querySelector('.cancel_linha_pgto').addEventListener('click', (ele) => {
	limpa_relogio_linha_pgto();
	document.querySelector(".table_files_linha_pgto tbody").innerHTML = "";
	document.getElementById('form_upload_linha_pgto').reset();
});

function limpa_tabela_linha_pgto() {
	document.querySelector(".table_files_linha_pgto tbody").innerHTML = "";
	document.getElementById('form_upload_linha_pgto').reset();
}
function limpa_relogio_linha_pgto() {
	document.querySelector(".timer_upload_ini_linha_pgto").innerHTML = "";
	document.querySelector(".timer_upload_fim_linha_pgto").innerHTML = "";
	document.querySelector('.row_msg_upload_linha_pgto').innerHTML = "";
}

function valida_files_linha_pgto(file) {
	let tab = document.querySelector(".table_files_linha_pgto tbody");
	tab.innerHTML = "";
	var fileInput = document.getElementById("files_linha_pgto");
	var files = fileInput.files;

	var rows = "";
	[...files].forEach((file, ind) => {
		var formato = (file.name).split('.')[1];
		if (!(['CSV', 'csv'].includes(formato))) {
			error_alert("#form_upload_linha_pgto", false, ((file.name) + ", formato de arquivo invalido. Aceita somente .csv "), 1000);
			document.getElementById('form_upload_linha_pgto').reset();
		} else {
			rows += "<tr><td>Arquivo: " + file.name + "</td></tr>";
		}
	});
	tab.innerHTML = rows;
}

const form_upload_linha_pgto = document.getElementById('form_upload_linha_pgto');
form_upload_linha_pgto.addEventListener('submit', uploadSubmitLinhaPgto);

async function uploadSubmitLinhaPgto(event) {
	event.preventDefault();
	var fileInput = document.getElementById("files_linha_pgto");
	var files = fileInput.files;

	if (files.length > 0) {

		document.querySelector('.timer_upload_ini_linha_pgto').innerHTML = "<i class='fas fa-hourglass'></i>&nbsp;Inicio: " + relogio();
		console.log('inicio sobe linha pgto: ' + relogio());
		error_alert("#form_upload_linha_pgto", 'loading', 'Fazendo upload dos arquivos...!!!', 1000);
		let data = new FormData(document.getElementById('form_upload_linha_pgto'));
		fetch(pag_url + 'upload_linha_pgto', {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		})
			.then((json) => json.json())
			.then(function (resp) {
				toastr.clear();
				if (resp.error == 0) {
					error_alert("#form_upload_linha_pgto", true, 'Base bov atualizada com sucesso!!!', 1000);
					document.querySelector(".table_files_linha_pgto tbody").innerHTML = "";
					document.getElementById('form_upload_linha_pgto').reset();
					atualiza_tabela();
				} else {
					error_alert("#form_upload_linha_pgto", false, 'Erro ao processar: ' + (resp.message), 1000);
				}
			})
			.catch(function (error) {
				error_alert("#form_upload_linha_pgto", false, "Erro no servidor ao fazer upload bov ", 1000);
				toastr.clear();
			}).finally(function () {
				console.log('termino sobe linha pgto: ' + relogio());
				document.querySelector('.timer_upload_fim_linha_pgto').innerHTML = "<i class='far fa-hourglass'></i>&nbsp;Termino: " + relogio();
			});
	} else {
		alert('obrigatorio o arquivo linha pgto');
	}
}

const exclui = document.querySelector(".btn_conf_exclui");
exclui.addEventListener('click', (ele) => {
	let data = new FormData(document.getElementById('form_busca'));
	fetch(pag_url + 'exclui_linhas', {
		method: 'POST',
		credentials: 'same-origin',
		body: data
	})
	.then((json) => json.json())
	.then(function (resp) {
		toastr.clear();
		if(resp.deletado > 0){
			$("#modal-deleta_lin").modal('hide');
			error_alert("#form_busca", true, 'Linhas excluidas com sucesso!!!', 1000);
			atualiza_tabela()
		}else{
			error_alert("#form_busca", false, "Erro no excluir Linhas ", 1000);
		}
	})
	.catch(function (error) {
		error_alert("#form_busca", false, "Erro no servidor ao excluir Linhas ", 1000);
		toastr.clear();
	});

});

function cardTotal(){
	let data = new FormData(document.getElementById('form_busca'));
	fetch(pag_url + 'soma_total', {
		method: 'POST',
		credentials: 'same-origin',
		body: data
	})
	.then((json) => json.json())
	.then(function (resp) {
		document.querySelector(".btn_total").innerHTML = resp.valor;
		document.querySelector(".btn_total_estorno").innerHTML = resp.estorno;
	})
	.catch(function (error) {
		error_alert("#form_busca", false, "Erro no servidor ao excluir Linhas ", 1000);
		toastr.clear();
	});	
}


//-------------------------TAB BLINDAGEM-----------------------------------------------
const btn_download_lpgto = document.querySelector('.btn_download_lpgto'); 
btn_download_lpgto.addEventListener('mouseover', (e)=>{
	download_modelo(btn_download_lpgto, 'MODELO_LINHA_PAGAMENTO');
});

function download_modelo(btn_download, nome){
	let cols = btn_download.getAttribute("data-colscsv");
	let csvString  = [cols];
	btn_download.setAttribute('href',`data:application/csv;charset=UTF-8,${encodeURIComponent(csvString.join('\n'))}`);
	btn_download.setAttribute('download', (nome+'.csv'));
}