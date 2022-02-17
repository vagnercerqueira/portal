var tableDfvs;

document.getElementById("form_busca").addEventListener('submit', (e) => {
    e.preventDefault();
    atualiza_tabela();

});



/*const links_docs = document.querySelectorAll(".links_uploads");
[...links_docs].forEach((idx, val)=>{
	idx.addEventListener("click", (e)=>{
		
	   })
});*/
function atualiza_tabela() {
    if (tableDfvs != undefined) tableDfvs.destroy();
    tableDfvs = $('#table_dfvs').DataTable({
        processing: true,
        info: true,
        paging: false,
        "scrollY": "600px",
        "scrollCollapse": true,
        pagingType: "full_numbers",
        ajax: {
            url: pag_url + "api_ceps",
            type: "POST",
            timeout: 15000,
            dataType: "json",
            data: function() { return $("#form_busca").serialize() }
        },
        "rowCallback": function(row, data) {
            if (data.tipo_viabilidade == "Viavel") {
                $('td:eq(9)', row).html("<i class='fas fa-wifi' style='font-size:20px;color:green'></i>");
            } else {
                $('td:eq(9)', row).html("<i class='fas fa-wifi' style='font-size:20px;color:red'></i>");
            }
        },
        "columnDefs": [
            { className: "text-center", "targets": [0, 3, 9] }
        ],
        "columns": [{
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