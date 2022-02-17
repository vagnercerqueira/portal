var tableMailing;
const tb = document.querySelector("#table_mailing tbody");
const col_bt_down = document.querySelector(".col_bt_down");
const bt_tot_filtro = document.querySelector('.bt_tot_filtro');

const f_nome_mailing = document.querySelector("select[name=f_nome_mailing]");
const f_cidade_mailing = document.querySelector("select[name=f_cidade_mailing]");
const f_cep_mailing = document.querySelector("select[name=f_cep_mailing]");

function download_modelo(btn_download, nome) {
    let cols = btn_download.getAttribute("data-colscsv");
    let csvString = [cols];
    btn_download.setAttribute('href', `data:application/csv;charset=UTF-8,${encodeURIComponent(csvString.join('\n'))}`);
    btn_download.setAttribute('download', (nome + '.csv'));
}

const btn_download_mailing = document.querySelector('.btn_download_mailing');
btn_download_mailing.addEventListener('mouseover', (e) => {
    download_modelo(btn_download_mailing, 'MAILING');
})

col_bt_down.addEventListener('mouseover', (e) => {

    let idMaili = f_nome_mailing.value;
    let cidade = f_cidade_mailing.value;
    let params = "f_nome_mailing=" + idMaili + "&f_cidade_mailing=" + cidade;

    col_bt_down.setAttribute("href", pag_url + "download_mailing?" + params);
})

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
        .then(function(resp) {
            let rows = "";
            let tam = Object.keys(resp).length;

            let i = 0;
            Object.entries(resp).forEach(([key, value]) => {
                console.log(value)
                value[13] = (value[13] == 'V' ? "<i class='fas fa-wifi' style='font-size:20px;color:green'></i>" : "<i class='fas fa-wifi' style='font-size:20px;color:red'></i>");
                rows += ("<tr><td>" + value.join('</td><td>') + "</td></tr>");
                if (tam == (i + 1)) tb.innerHTML = rows;
                i++;
            });
        })
        .catch(function(error) {
            alert("ocorreu algum erro");
        }).finally(function() {
            toastr.clear();
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
document.getElementById('files_mailing').addEventListener('change', (ele) => {
    valida_files_mailing();
    limpa_relogio_mailing();
});

function openDialogmailing() {
    document.getElementById('files_mailing').click();
    limpa_relogio_mailing();
}

document.querySelector('.cancel_mailing').addEventListener('click', (ele) => {
    limpa_relogio_mailing();
    document.querySelector(".table_files_mailing tbody").innerHTML = "";
    document.getElementById('form_upload_mailing').reset();
});

function limpa_tabela_mailing() {
    document.querySelector(".table_files_mailing tbody").innerHTML = "";
    document.getElementById('form_upload_mailing').reset();
}

function limpa_relogio_mailing() {
    document.querySelector(".timer_upload_ini_mailing").innerHTML = "";
    document.querySelector(".timer_upload_fim_mailing").innerHTML = "";
    document.querySelector('.row_msg_upload_mailing').innerHTML = "";
}

function valida_files_mailing(file) {
    let tab = document.querySelector(".table_files_mailing tbody");
    tab.innerHTML = "";
    var fileInput = document.getElementById("files_mailing");
    var files = fileInput.files;

    var rows = "";
    [...files].forEach((file, ind) => {
        var formato = (file.name).split('.')[1];
        if (!(['CSV', 'csv'].includes(formato))) {
            error_alert("#form_upload_mailing", false, ((file.name) + ", formato de arquivo invalido. Aceita somente .csv "), 1000);
            document.getElementById('form_upload_mailing').reset();
        } else {
            rows += "<tr><td>Arquivo: " + file.name + "</td></tr>";
        }
    });
    tab.innerHTML = rows;
}

const form_upload_mailing = document.getElementById('form_upload_mailing');
form_upload_mailing.addEventListener('submit', uploadSubmitLinhaPgto);

async function uploadSubmitLinhaPgto(event) {
    event.preventDefault();
    var fileInput = document.getElementById("files_mailing");
    var files = fileInput.files;

    if (files.length > 0) {

        document.querySelector('.timer_upload_ini_mailing').innerHTML = "<i class='fas fa-hourglass'></i>&nbsp;Inicio: " + relogio();
        console.log('inicio sobe linha pgto: ' + relogio());
        error_alert("#form_upload_mailing", 'loading', 'Fazendo upload dos arquivos...!!!', 1000);
        let data = new FormData(document.getElementById('form_upload_mailing'));
        fetch(pag_url + 'upload_mailing', {
                method: 'POST',
                credentials: 'same-origin',
                body: data
            })
            .then((json) => json.json())
            .then(function(resp) {
                toastr.clear();
                if (resp.error == 0) {
                    error_alert("#form_upload_mailing", true, 'Base mailing atualizada com sucesso!!!', 1000);
                    monta_option_mailings(resp);
                } else {
                    error_alert("#form_upload_mailing", false, 'Erro ao processar: ' + (resp.message), 1000);
                }
            })
            .catch(function(error) {
                error_alert("#form_upload_mailing", false, "Erro no servidor ao fazer upload mailing ", 1000);
                toastr.clear();
            }).finally(function() {
                console.log('termino sobe linha pgto: ' + relogio());
                document.querySelector('.timer_upload_fim_mailing').innerHTML = "<i class='far fa-hourglass'></i>&nbsp;Termino: " + relogio();
            });
    } else {
        alert('obrigatorio o arquivo linha pgto');
    }
}

function monta_option_mailings(resp) {
    document.querySelector(".btn_tot_reg_base").innerHTML = (resp.tot_reg_base) + " registros na base";
    tb.innerHTML = "";
    bt_tot_filtro.innerHTML = "0 registros filtrados";
    document.querySelector(".table_files_mailing tbody").innerHTML = "";
    document.getElementById('form_upload_mailing').reset();
    let OptVaz = "<option value='' selected>Selecione...</option>";
    f_nome_mailing.innerHTML = OptVaz;
    f_cidade_mailing.innerHTML = OptVaz;

    Object.entries(resp.options_mailing).forEach(([key, value]) => {
        opt = document.createElement("option");
        opt.value = value.id;
        opt.text = value.nome + " (data: " + value.data + ")";
        f_nome_mailing.add(opt);
    });
}

const exclui = document.querySelector(".btn_conf_exclui");
exclui.addEventListener('click', (ele) => {

    if (f_nome_mailing.value != "") {
        let data = new FormData(document.getElementById('form_busca'));
        fetch(pag_url + 'exclui_mailings', {
                method: 'POST',
                credentials: 'same-origin',
                body: data
            })
            .then((json) => json.json())
            .then(function(resp) {
                toastr.clear();
                if (resp.sucesso == "ok") {
                    $("#modal-deleta_mailing").modal('hide');
                    error_alert("#form_busca", true, 'Mailings excluidas com sucesso!!!', 1000);
                    monta_option_mailings(resp);
                } else {
                    error_alert("#form_busca", false, "Erro no excluir mailings ", 1000);
                }
            })
            .catch(function(error) {
                error_alert("#form_busca", false, "Erro no servidor ao excluir mailings ", 1000);
                toastr.clear();
            });
    } else {
        error_alert("#form_busca", false, "Selecione o mailing para excluir!!!", 1000);
    }
});

f_nome_mailing.addEventListener("change", () => {
    limpa_filtros();
    let fcidades = "<option value='' selected>Selecione...</option>";

    if (f_nome_mailing.value != "") {
        let data = new FormData(document.getElementById('form_busca'));
        fetch(pag_url + 'busca_cidades', {
                method: 'POST',
                credentials: 'same-origin',
                body: data
            })
            .then((json) => json.json())
            .then(function(resp) {
                Object.entries(resp.cidades).forEach(([key, value]) => {
                    fcidades += "<option value='" + value + "'>" + value + "</option>";
                });
                f_cidade_mailing.innerHTML = fcidades;
                bt_tot_filtro.innerHTML = (resp.tot) + " registros filtrados";
            })
            .catch(function(error) {
                error_alert("#form_busca", false, "Erro no servidor", 1000);
            });
    } else {
        f_cidade_mailing.innerHTML = fcidades;
    }
})

f_cidade_mailing.addEventListener("change", () => {
    limpa_filtros();

    let fceps = "<option value='' selected>Selecione...</option>";
    if (f_cidade_mailing.value != "") {
        let data = new FormData(document.getElementById('form_busca'));
        fetch(pag_url + 'busca_ceps', {
                method: 'POST',
                credentials: 'same-origin',
                body: data
            })
            .then((json) => json.json())
            .then(function(resp) {

                /* Object.entries(resp.ceps).forEach(([key, value]) => {
                     fceps += "<option value='" + value + "'>" + value + "</option>";
                 });
                 //f_cep_mailing.innerHTML = fceps;*/
                bt_tot_filtro.innerHTML = (resp.tot) + " registros filtrados";
            })
            .catch(function(error) {
                error_alert("#form_busca", false, "Erro no servidor", 1000);
            });
    } else {
        // f_cep_mailing.innerHTML = fceps;
    }
});


/*f_cep_mailing.addEventListener("change", () => {
    tb.innerHTML = "";
    bt_tot_filtro.innerHTML = "0 registros filtrados";
    // col_bt_down.style.display = "none";
});*/

function limpa_filtros() {
    // f_cep_mailing.innerHTML = "<option value='' selected>Selecione...</option>";
    tb.innerHTML = "";
    bt_tot_filtro.innerHTML = "0 registros filtrados";
}