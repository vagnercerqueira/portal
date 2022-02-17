monta_search();
/*$("#form_aplicacoes").on('click', '.caret', function () {
	this.parentElement.querySelector(".nested").classList.toggle("active");
	this.classList.toggle("caret-down");
	//$(this).find('.caret').toggle();
});*/
document.querySelectorAll('.caret').forEach(item => {
	item.addEventListener('click', event => {
		item.parentElement.querySelector(".nested").classList.toggle("active");
		item.classList.toggle("caret-down");
	})
});
function post_autocomplete(desc) {
	atualiza_acesso(document.getElementById("ID_USUARIO").value);
}
var atualiza_acesso = (usuario) => {
	if (usuario != "") {
		(document.querySelector("#div_arvore")).style.display = 'block';
		oData = new FormData();
		oData.append('usuario', usuario);

		error_alert((document.querySelector("#form_aplicacoes")), 'loading', '')
		fetch(pag_url + "atualiza_acesso",{
			method: 'POST' ,
			body: oData,						 
	  	})
	  	.then( response => response.json() )
		.then( json => { 
			document.querySelector("#div_arvore").innerHTML = json.dirs
			document.querySelectorAll('input').forEach(item => {
				item.addEventListener('click', event => {
					(item.classList.contains('input_pai') || item.classList.contains('input_filho')) ? sanfona(item) : perm_crud(item);
				})
			});
			toastr.clear();
	  	})
		.catch( function ( error ){
		  toastr.clear();
		   console.log("ocorreu algum erro");
	  	});
	} else {
		(document.querySelector("#div_arvore")).style.display = 'none';
		(document.querySelector("#div_arvore")).innerHTML = '';
	}
}

function post_clear_list(tar) {
	atualiza_acesso('');
};
var getClosestCheck = (item, pai) => {
	li = item.parentElement;
	ul = li.parentElement;
	liP = ul.parentElement;
	if (liP.classList.contains('pai')) {
		liP.querySelector('input').checked = true;
		getClosestCheck(liP.querySelector('input'), pai);
	}
	else
		return false;
}
var getClosestUnch = (item, pai) => {
	li = item.parentElement;

	ul = li.parentElement;
	liP = ul.parentElement;
	if (liP.classList.contains('pai')) {
		totCH = liP.querySelectorAll('.input_filho:checked');
		if (totCH.length == 0)
			liP.querySelector('input').checked = false;
		getClosestUnch(liP.querySelector('input'), pai);
	}
	else
		return false;
}

var sanfona = (item) => {
	if (item.checked === true) {
		inps = item.closest("li").querySelectorAll('.perm_crud');
		for (var i = 0; i < inps.length; i++) {
			inps[i].style.display = 'block';
		}
		inps = item.closest("li").querySelectorAll('input');
		for (var i = 0; i < inps.length; i++) {
			(inps[i]).checked = true;
		}
		getClosestCheck(item, "pai");
	} else {
		inps = item.closest("li").querySelectorAll('input');
		for (var i = 0; i < inps.length; i++) {
			(inps[i]).checked = false;
		}
		inps = item.closest("li").querySelectorAll('.perm_crud');
		for (var i = 0; i < inps.length; i++) {
			inps[i].style.display = 'none';
		}
		getClosestUnch(item, "pai");
	}
	ajax_altera_acesso(item);
}

var perm_crud = (e) => {
	bool = e.checked ? 'S' : 'N';

	oData = new FormData();
	oData.append('usuario', document.getElementById("ID_USUARIO").value);
	oData.append('campo', e.getAttribute('act'));
	oData.append('bool', bool);
	oData.append('aplicacao', e.value);
	form = document.getElementById("form_aplicacoes");
	error_alert(form, 'loading', '')
	fetch(pag_url + "update_acesso",{
		method: 'POST' ,
		body: oData,						 
	})
	.then( response => response.json() )
	.then( json => { 
		toastr.clear();
		if(json.TOT > 0 ){
			setTimeout(function () { error_alert(form, true, 'Permissao alterada!!!'); }, 600);	
		}else{
			setTimeout(function () { error_alert(form, false, 'Erro ao alterar permissao!!!'); }, 600);	
		}		
	})
	.catch( function ( error ){
	  	toastr.clear();
	   	alert("ocorreu algum erro");
	});
};

function ajax_altera_acesso(item) {
	form = document.getElementById("form_aplicacoes");
	oData = new FormData(form);
	fetch(pag_url + "altera_acesso",{
		method: 'POST' ,
		body: oData,						 
	})
	.then( response => response.json() )
	.then( json => { 
		toastr.clear();
		if(json.TOT > 0 ){
			setTimeout(function () { error_alert(form, true, 'Permissao alterada!!!'); }, 600);	
		}else{
			setTimeout(function () { error_alert(form, false, 'Erro ao alterar permissao!!!'); }, 600);	
		}	
	})
	.catch( function ( error ){
	  	toastr.clear();
	   alert("ocorreu algum erro");
	});	
}