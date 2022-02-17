var tableBasicas = initDataTable();
$('input[type=month]').keydown(function (event) {
    return false;
});

//Busca supervisor
document.querySelector('#EQUIPE').addEventListener("change", function () {
    let supervisor = document.querySelector('#SUPERVISOR');
    oData = new FormData();
    oData.append('id', this.value);
    fetch(
        pag_url + 'ProcuraSupervisor', {
        method: 'POST',
        body: oData
    })
        .then(function (response) {
            response.json().then(function (data) {
                supervisor.value = data.supervisor;
            });
        });

});
document.querySelector('#COMPETENCIA').addEventListener("change", function () {

    let competencia = document.querySelector('#COMPETENCIA');
    let tipo_meta = document.querySelector('#TIPO_META');
    let tipo = document.querySelector('#TIPO');
    let venda = document.querySelector('#VENDA');
    let instalacao = document.querySelector('#INSTALACAO');

    oData = new FormData();
    oData.append('competencia', this.value);
    fetch(
        pag_url + 'ProcuraCompetencia', {
        method: 'POST',
        body: oData
    })
        .then(function (response) {
            response.text().then(function (data) {
                if (data === "") {
                    venda.value = "";
                    instalacao.value = "";
                    tipo_meta.value = "";
                    error_alert("#form_meta_vendedor", false, "Ainda não há tipo de meta definida para o mês selecionado", 3000);
                }
                else {
                    tipo.value = data;
                    if (data == 1) {
                        tipo_meta.value = "Unitario";
                    }
                    else {
                        tipo_meta.value = "Faturamento";
                    }
                }
                tipoMascara();
            });
        });

});
var pre_cadastrar_alterar = function (formu, dados) {
    let tipo_meta = document.querySelector('#TIPO_META');
    if (tipo_meta.value == "") {
        error_alert("#form_meta_vendedor", false, "Voce não conseguir cadastrar, tipo de meta não definido", 3000);
        return false;
    }
    else {
        return dados;
    }

}

function tipoMascara() {
    $('#VENDA').val("");
    $('#INSTALACAO').val("");
    let venda = document.querySelector('#VENDA');
    let instalacao = document.querySelector('#INSTALACAO');
    let tipo_meta = document.querySelector('#TIPO_META');
    if (tipo_meta.value == "Unitario") {
        $('#VENDA').mask('00000');
        $('#INSTALACAO').mask('00000');
    }
    else if (tipo_meta.value == "Faturamento") {
        $('#VENDA').mask('#.##0,00', { reverse: true });
        $('#INSTALACAO').mask('#.##0,00', { reverse: true });
    }
}