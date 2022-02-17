carregaDados();
const input_data = document.querySelector("#COMPETENCIA");
const tipo_click = document.querySelectorAll(".small-box-footer")
const tabs = {
    "venda_bruta": ["zEquipe", "Supervisor", "Vendedor", "CPF/CNPJ", "Venda", "Data BOV", "BOV"],
    "venda_instalada": ["zEquipe", "Supervisor", "Vendedor", "CPF/CNPJ", "Venda", "Data BOV", "BOV", "OS"],
    "venda_agendamento": ["zEquipe", "Supervisor", "Vendedor", "CPF/CNPJ", "Venda", "Agendamento", "BOV", "OS"],
    "venda_tratamento": ["zEquipe", "Supervisor", "Vendedor", "CPF/CNPJ", "Venda", "Status tratamento", "Setor", "Retorno"],
    "venda_cancelada": ["zEquipe", "Supervisor", "Vendedor", "CPF/CNPJ", "Venda", "Data BOV", "Status Tratamento"],
    "venda_migracao": ["zEquipe", "Supervisor", "Vendedor", "CPF/CNPJ", "Venda", "Mig Fibra", "Mig Voip"],
    "venda_dados_incompletos": ["Equipe", "Supervisor", "Vendedor", "CPF/CNPJ", "Venda"],
    "venda_instalacao_atrasada": ["Equipe", "Supervisor", "Vendedor", "CPF/CNPJ", "Venda", "OS"],
}


tipo_click.forEach((i, v) => i.addEventListener("click", e => {
    let novo_titulo = i.dataset.titulo;
    document.querySelector(".modal-title").innerHTML = novo_titulo;
    dadosModal(novo_titulo);
}));

input_data.addEventListener("change", () => {
    carregaDados();
})

function carregaDados() {
    let data = new FormData(document.getElementById('form_busca_mes'));
    fetch(pag_url + 'carregaDados', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    }).then(function (response) {
        response.json().then(function (data) {
            //console.log(data); return; exit;
            document.querySelector("#venda_bruta").innerHTML = data.vendas_bruta;
            document.querySelector("#venda_instaladas").innerHTML = data.vendas_instaladas;
            document.querySelector("#venda_aprovisionamento").innerHTML = data.vendas_aprovisionamento;
            document.querySelector("#venda_emtratamento").innerHTML = data.vendas_emtratamento;
            document.querySelector("#venda_cancelados_bov").innerHTML = data.vendas_cancelados_bov;
            document.querySelector("#vendas_migracoes").innerHTML = data.vendas_migracoes;
            document.querySelector("#vendas_dados_incompletos").innerHTML = data.vendas_dados_incompletos;
            document.querySelector("#vendas_instalacoes_atrasadas").innerHTML = data.vendas_instalacoes_atrasadas;
            document.querySelector("#ultima_venda").innerHTML = data.ultima_venda;
            document.querySelector("#ultima_bov").innerHTML = data.ultima_bov;
			document.querySelector("#total_instalada").innerHTML = "<b>"+data.total_instalada+"</b>";
        });
    });
}

function dadosModal(novo_titulo) {
    oData = new FormData();
    oData.append('rotulo', novo_titulo);
    oData.append('COMPETENCIA', input_data.value);
    fetch(pag_url + 'dadosModal', {
        method: 'POST',
        credentials: 'same-origin',
        body: oData
    }).then(function (response) {
        response.json().then(function (data) {
            let tabela = "<table class='table table-sm table-bordered table table-head-fixed' id='tableBasicas'>";
            let thead = "<thead><tr>";
            if (novo_titulo == "Vendas Brutas") {
                var tp_array = [...tabs.venda_bruta];
                var varre_array = [...data.vendas_brutas].forEach((v, i) => {
                    tabela +=
                        "<tr><td>" + v.equipe + "</td><td>" + v.supervisor + "</td><td>" + v.vendedor +
                        "</td><td>" + v.cpf_cnpj_csv +
                        "</td><td>" + v.dt_venda_csv + "</td><td>" + v.dt_instalacao + "</td><td>" + v.status_bov + "</td></tr>";
                });
            }

            else if (novo_titulo == "Vendas Instaladas") {
                var tp_array = [...tabs.venda_instalada];
                var varre_array = [...data.vendas_instaladas].forEach((v, i) => {
                    tabela +=
                        "<tr><td>" + v.equipe + "</td><td>" + v.supervisor + "</td><td>" + v.vendedor +
                        "</td><td>" + v.cpf_cnpj_csv +
                        "</td><td>" + v.dt_venda_csv + "</td><td>" + v.dt_instalacao + "</td><td>" + v.status_bov + "</td><td>" + v.num_os + "</td></</tr>";
                });
            }

            else if (novo_titulo == "Em Aprovisionamento") {
                var tp_array = [...tabs.venda_agendamento];
                var varre_array = [...data.vendas_aprovisionamento].forEach((v, i) => {
                    tabela +=
                        "<tr><td>" + v.equipe + "</td><td>" + v.supervisor + "</td><td>" + v.vendedor +
                        "</td><td>" + v.cpf_cnpj_csv +
                        "</td><td>" + v.dt_venda_csv + "</td><td>" + v.dt_agendamento + "</td><td>" + v.status_bov + "</td><td>" + v.num_os + "</td></</tr>";
                });
            }

            else if (novo_titulo == "Em Tratamento") {
                var tp_array = [...tabs.venda_tratamento];
                var varre_array = [...data.vendas_tratamento].forEach((v, i) => {
                    tabela +=
                        "<tr><td>" + v.equipe + "</td><td>" + v.supervisor + "</td><td>" + v.vendedor +
                        "</td><td>" + v.cpf_cnpj_csv +
                        "</td><td>" + v.dt_venda_csv + "</td><td>" + v.status_tratamento + "</td><td>" + v.setor_resp_tratamento + "</td><td>" + v.dt_retorno_tratamento + "</td></</tr>";
                });
            }
            else if (novo_titulo == "Cancelados BOV") {
                var tp_array = [...tabs.venda_cancelada];
                var varre_array = [...data.vendas_cancelada_bov].forEach((v, i) => {
                    tabela +=
                        "<tr><td>" + v.equipe + "</td><td>" + v.supervisor + "</td><td>" + v.vendedor +
                        "</td><td>" + v.cpf_cnpj_csv +
                        "</td><td>" + v.dt_venda_csv + "</td><td>" + v.data_status_bov + "</td><td>" + v.status_tratamento + "</td></</tr>";
                });
            }

            else if (novo_titulo == "Migrações VOIP ou FIBRA") {
                var tp_array = [...tabs.venda_migracao];
                var varre_array = [...data.vendas_cancelada_bov].forEach((v, i) => {
                    tabela +=
                        "<tr><td>" + v.equipe + "</td><td>" + v.supervisor + "</td><td>" + v.vendedor +
                        "</td><td>" + v.cpf_cnpj_csv +
                        "</td><td>" + v.dt_venda_csv + "</td><td>" + v.mig_cobre_velox_bov + "</td><td>" + v.mig_cobre_fixo_bov + "</td></</tr>";
                });
            }

            else if (novo_titulo == "Dados incompletos") {
                var tp_array = [...tabs.venda_dados_incompletos];
                var varre_array = [...data.vendas_dados_incompletos].forEach((v, i) => {
                    tabela +=
                        "<tr><td>" + v.equipe + "</td><td>" + v.supervisor + "</td><td>" + v.vendedor +
                        "</td><td>" + v.cpf_cnpj_csv +
                        "</td><td>" + v.dt_venda_csv + "</td></</tr>";
                });
            }
            else if (novo_titulo == "Instalações atrasadas") {
                var tp_array = [...tabs.venda_instalacao_atrasada];
                var varre_array = [...data.vendas_instalacoes_atrasadas].forEach((v, i) => {
                    tabela +=
                        "<tr><td>" + v.equipe + "</td><td>" + v.supervisor + "</td><td>" + v.vendedor +
                        "</td><td>" + v.cpf_cnpj_csv +
                        "</td><td>" + v.dt_venda_csv + "</td><td>" + v.num_os + "</td></tr>";
                });
            }

            tp_array.forEach((v, i) => {
                thead += "<th>" + v + "</th>";
            });
            thead += "</tr></thead>";
            tabela += thead;
            varre_array

            tabela += "</table>";
            document.querySelector("#tabela").innerHTML = tabela;
            $('#tableBasicas').DataTable({
                "order": [[0, "desc"], [1, "desc"], [2, "desc"], [3, "desc"], [4, "desc"]],
                "paging": false,
                "info": false

            });
        });
    });
}


function tableToCSV() {
    /* $('#tableBasicas').DataTable({
         "bDestroy": true,
         "order": [[0, "desc"]],
         "paging": false,
         "info": false
 
     });*/
    // Variable to store the final csv data
    var csv_data = [];
    // Get each row data
    var rows = document.getElementsByTagName('tr');
    for (var i = 0; i < rows.length; i++) {
        // Get each column data
        var cols = rows[i].querySelectorAll('td,th');
        // Stores each csv row data
        var csvrow = [];
        for (var j = 0; j < cols.length; j++) {
            // Get the text data of each cell
            // of a row and push it to csvrow
            csvrow.push(cols[j].innerHTML);
        }
        // Combine each column value with comma
        csv_data.push(csvrow.join(";"));
    }
    // Combine each row data with new line character
    csv_data = csv_data.join('\n');

    // Call this function to download csv file 
    downloadCSVFile(csv_data);
}

function downloadCSVFile(csv_data) {
    // Create CSV file object and feed
    // our csv_data into it
    CSVFile = new Blob([csv_data], {
        type: "text/csv"
    });

    // Create to temporary link to initiate
    // download process
    var temp_link = document.createElement('a');
    // Download csv file
    temp_link.download = "relatorio.csv";
    var url = window.URL.createObjectURL(CSVFile);
    temp_link.href = url;
    // This link should not be displayed
    temp_link.style.display = "none";
    document.body.appendChild(temp_link);
    // Automatically click the link to
    // trigger download
    temp_link.click();
    document.body.removeChild(temp_link);
}


