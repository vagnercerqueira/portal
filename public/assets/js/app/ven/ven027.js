const tb = document.querySelector("#table_mailing tbody");
//const col_bt_down = document.querySelector(".col_bt_down");
const bt_tot_filtro = document.querySelector('.bt_tot_filtro');	
//const btn_download_mailings =  document.querySelector(".btn_download_mailings");

const f_cidade_mailing = document.querySelector("select[name=f_cidade_mailing]");
const f_nome_mailing = document.querySelector("select[name=f_nome_mailing]");
const f_cep_mailing = document.querySelector("select[name=f_cep_mailing]");
const filter_table = document.querySelector(".input-table-filter");
/*btn_download_mailings.addEventListener("click", ()=>{
	let nmCsv = (f_nome_mailing.value);
	DataTableCsv("table_mailing",btn_download_mailings, false, nmCsv);
});*/

document.getElementById("form_busca").addEventListener('submit', (e) => { 
	e.preventDefault();
	atualiza_tabela();
});

function atualiza_tabela() {
	error_alert("#form_upload_mailing", 'loading', 'Carregando...!!!', 1000);
	let data = new FormData(document.getElementById('form_busca'));
		fetch(pag_url + 'retorna_mailings', {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		})
			.then((json) => json.json())
			.then(function (resp) { 				
				let rows = "";
				//myObj = JSON.parse(resp.data);
				[...resp].forEach((v,i)=>{
					let viab = (v.viabilidade == 'V' ? "<i class='fas fa-wifi' style='font-size:20px;color:green'></i>" : "<i class='fas fa-wifi' style='font-size:20px;color:red'></i>");
					rows += `<tr>
								<td>`+(v.nome)+`</td>
								<td>`+(v.cpf)+`</td>
								<td style='width:7%'>`+(v.email)+`</td>
								<td>`+(v.contato1)+`</td>
								<td>`+(v.contato2)+`</td>
								<td>`+(v.contato3)+`</td>
								<td>`+(v.contato4)+`</td>
								<td>`+(v.cep)+`</td>
								<td>`+(v.uf)+`</td>
								<td>`+(v.cidade)+`</td>
								<td>`+(v.bairro)+`</td>
								<td>`+(v.logradouro)+`</td>
								<td>`+(v.num_fachada)+`</td>
								<td>`+viab+`<span style='display: none'>`+(v.viabilidade)+`</span></td>																
							</tr>`;
				});
				tb.innerHTML = rows;
				
				bt_tot_filtro.innerHTML= ( Object.keys(resp).length )+" registros filtrados";
				
				if( Object.keys(resp).length > 0){
					//col_bt_down.style.display = "block";
				}else{
					//col_bt_down.style.display = "none";
				}
			})
			.catch(function (error) {
				alert("ocorreu algum erro");
			}).finally(function () {
				toastr.clear();
			});
}

document.querySelector("select[name=f_nome_mailing]").addEventListener("change", ()=>{
	limpa_filtros();

	let data = new FormData(document.getElementById('form_busca'));
	fetch(pag_url + 'busca_cidades', {
		method: 'POST',
		credentials: 'same-origin',
		body: data
	})
	.then((json) => json.json())
	.then(function (resp) {
		let fcidades = "<option value='' selected>Selecione...</option>";
		Object.entries(resp).forEach(([key, value]) => {
			fcidades += "<option value='"+value.descr+"'>"+value.descr+"</option>";
		});
		f_cidade_mailing.innerHTML = fcidades;
	})
	.catch(function (error) {
		error_alert("#form_busca", false, "Erro no servidor", 1000);		
	});
})

document.querySelector("select[name=f_cidade_mailing]").addEventListener("change", ()=>{
	limpa_filtros();
	let data = new FormData(document.getElementById('form_busca'));
	fetch(pag_url + 'busca_ceps', {
		method: 'POST',
		credentials: 'same-origin',
		body: data
	})
	.then((json) => json.json())
	.then(function (resp) {
		let fceps = "<option value='' selected>Selecione...</option>";
		Object.entries(resp).forEach(([key, value]) => {
			fceps += "<option value='"+value.descr+"'>"+value.descr+"</option>";
		});
		f_cep_mailing.innerHTML = fceps;
	})
	.catch(function (error) {
		error_alert("#form_busca", false, "Erro no servidor", 1000);		
	});
});

document.querySelector("select[name=f_cep_mailing]").addEventListener("change", ()=>{
	tb.innerHTML = "";
	bt_tot_filtro.innerHTML = "0 registros filtrados";
	filter_table.value='';
	//col_bt_down.style.display = "none";
});

function limpa_filtros(){
	f_cep_mailing.innerHTML = "<option value='' selected>Selecione...</option>";
	tb.innerHTML = "";
	//col_bt_down.style.display = "none";
	bt_tot_filtro.innerHTML = "0 registros filtrados"; 
	filter_table.value='';
}



//----------------------------FILTRO TABELA---------------------------------------//
filter_table.addEventListener("keyup", ()=>{
	FsearchTb("#table_mailing", ".input-table-filter");
})	
