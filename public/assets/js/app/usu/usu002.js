var tableAplicacoes = initDataTable({ idTab: 'tableAplicacoes', btCsv: true });
addEvent();
function addEvent() {
	document.querySelectorAll('.caret').forEach(item => {
		item.addEventListener('click', event => {
			item.parentElement.querySelector(".nested").classList.toggle("active");
			item.classList.toggle("caret-down");
		})
	})
}

var apos_cadastrar_alterar = (formu) => {
	atualiza_arvore();
};

var atualiza_arvore = (e) => {
	fetch(pag_url + "atualiza_arvore", {
		method: 'POST',
		body: oData,
	})
		.then(response => response.json())
		.then(json => {
			document.querySelector("#div_arvore").innerHTML = json.DADOS
			addEvent();
		})
		.catch(function (error) {
			alert("ocorreu algum erro");
		});
}