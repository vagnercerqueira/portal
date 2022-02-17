$("input[name=COMPETENCIA]").mask("9999-99");

	const COMPETENCIA = document.getElementById("COMPETENCIA");
	const TB_CALENDARIO = document.querySelector("#tableCalendario tbody");
	const SEMANA = ["Domingo", "Segunda-Feira", "Terça-Feira", "Quarta-Feira", "Quinta-Feira", "Sexta-Feira", "Sabado"];
	const FERIADOS = [];
	const btn_submit = document.querySelector(".btn_submit");
	
	COMPETENCIA.addEventListener("change", ()=>{
		TB_CALENDARIO.innerHTML = "";
		existe_competencia();
	});
	document.querySelector('#form_busca_dias').addEventListener("submit", (e)=>{
		e.preventDefault();
		TB_CALENDARIO.innerHTML = "";
		existe_competencia();
	});	

	existe_competencia();

	function existe_competencia(){
		let data = new FormData(document.getElementById('form_busca_dias'));
		fetch(pag_url + 'existe_competencia', {
			method: 'POST',
			credentials: 'same-origin',
			body: data
		})
			.then((json) => json.json())
			.then(function (resp) {				
				if(Object.entries(resp).length == 0){
					error_alert("#form_upload_mailing", false, "Peso do mes selecionado ainda nao foi cadastrada", 1000);
					monta_calendario();
					btn_submit.innerHTML = 'Cadastrar pesos';
				}else{

					let rows = '';
					Object.entries(resp).forEach(([key, value]) => {						
						rows += monta_rows(value.diasemana, value.dtpt,key,value.peso, value.trabalhado, value.data, value.cl)
					});
					
					if(rows != ""){
						setTimeout(function(){ MudaPeso(); }, 1500);
					}						
					TB_CALENDARIO.innerHTML = rows;
					btn_submit.innerHTML = 'Alterar pesos';			
				}
				btn_submit.disabled = false;
			
			})
			.catch(function (error) {
				error_alert("#form_upload_mailing", false, "Erro no servidor ao fazer verificar competencia ", 1000);
				toastr.clear();
			}).finally(function () {
				
			});
	}
	
	monta_calendario = function() {		
		let comp = (COMPETENCIA.value).split('-');
		let mes = parseInt(comp[1]);
		let ano = parseInt(comp[0]);
		var options = getDiasMes(mes, ano);		
		
		var rows = "";
		var trabalhado = 0;
		var tot_peso = 0;
		var tot_trabalhado = 0;
		
		var BoolnoMes = false;
		
		var dtHj = new Date();
		d = dtHj.getDate();
		m = (dtHj.getMonth()+1);
		var hj = (d < 10 ? '0'+d : d) +'/'+(m < 10 ? '0'+m : m)+'/'+dtHj.getFullYear();
		
		for (var i = 0; i < options.length; i++) {
				//console.log("dia - "+i);
			var opt = options[i];
			
			let peso = 1;
			if(opt.diasemana == 0 || FERIADOS.includes(opt.dia)){
				peso = 0;
			}else if(opt.diasemana == 6){
				peso = 0.5;
			}
			cl = '';
			//console.log("dia - "+dt);
			if(hj == opt.dt) {
				cl = "class='alert-success'";
				BoolnoMes = true;
			}
			if(options.length == (i+1) && BoolnoMes === false ){ cl = "class='alert-success'"; }
			
			trabalhado += peso;
			tot_peso += peso;
			
			console.log(dtIng);
			let dtpt = (opt.dtIng).split("-");
			dtpt = dtpt[2]+"/"+dtpt[1]+"/"+dtpt[0]			
			
			rows += monta_rows(SEMANA[opt.diasemana], dtpt, i,peso,trabalhado, opt.dtIng, cl)						
		}
		
		if(rows != ""){
			setTimeout(function(){ MudaPeso(); }, 1500);
		}						
		TB_CALENDARIO.innerHTML = rows;		
	}
	function monta_rows(diasemana,dtpt,i,peso, trabalhado, dtIng, cl){

		
		//console.log(dia_mes[2]);
		rows = `<tr `+cl+`>
					<td>`+(diasemana)+`</td>
					<td class='text-center'>`+dtpt+`</td>
					<td>
						<input  required step='0.5' name='peso[]' min='0' data-peso='`+i+`'  class='inp_peso form-control form-control-sm text-center' value='`+peso+`' type='number'/>
						<input type='hidden' name='dias[]' value='`+dtIng+`' />
						</td>
					<td class='text-center' data-trabalhado='`+i+`'>`+(trabalhado)+`</td>
				</tr>`;
		return rows;	
	}

	function getDiasMes(month, year) {
			month--;
			var date = new Date(year, month, 1);
			var days = [];
			while (date.getMonth() === month) {
				d = date.getDate();
				m = (date.getMonth()+1);

				dt = (d < 10 ? '0'+d : d) +'/'+(m < 10 ? '0'+m : m)+'/'+date.getFullYear();
				dtIng = date.getFullYear()+'-'+(m < 10 ? '0'+m : m)+'-'+(d < 10 ? '0'+d : d);
				days.push({'dia': date.getDate(), 'diasemana': date.getDay(), 'dt': dt, 'dtIng':dtIng})
				date.setDate(date.getDate() + 1);
			}
			return days;
	}
	
	
function MudaPeso(){
	let inputs = document.querySelectorAll('table .inp_peso');
	inputs.forEach((inp, index) =>{
		inp.addEventListener('change', function (e){
			recalcula_trabalhado(index, inputs.length);
		})
	})
}
function recalcula_trabalhado(ini, fim){
	for(i = ini; i<fim; i++){
		v = document.querySelector('[data-peso="'+(i)+'"]').value;
		v = Number(v.replace(',','.'));
		
		if(i == 0){
			htrab = v;
		}else{
			htrabAnt = document.querySelector('[data-trabalhado="'+(i-1)+'"]').innerHTML;
			htrab = v+Number(htrabAnt);
		}
		document.querySelector('[data-trabalhado="'+(i)+'"]').innerHTML = htrab;						
	}
}

btn_submit.addEventListener("click", ()=>{
	let data = new FormData(document.getElementById('form_peso_dias'));
	error_alert("#form_peso_dias", 'loading', '...!!!', 1000);
	fetch(pag_url + 'salva_competencia', {
		method: 'POST',
		credentials: 'same-origin',
		body: data
	})
		.then((json) => json.json())
		.then(function (resp) {	
			toastr.clear();						
			if(resp.tot > 0){
				error_alert("#form_peso_dias", true, 'Pesos atualizados com sucesso!!!', 1000);
				btn_submit.innerHTML = 'Alterar pesos';
			}
		})
		.catch(function (error) {
			error_alert("#form_peso_dias", false, "Erro no servidor ao cadastrar os pesos ", 1000);
			toastr.clear();
		}).finally(function () {
			
		});	
})