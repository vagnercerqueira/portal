carregaDados();
const input_data = document.querySelector("#COMPETENCIA");
input_data.addEventListener("change", () => {
    carregaDados();
})

const tabs = {
    "titulo": ["Bruto", "Instalado"]
}

function carregaDados() {
    let data = new FormData(document.getElementById('form_busca_mes'));
    fetch(pag_url + 'geraGrafico', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    }).then(function (response) {
        response.json().then(function (data) {
            //TABELA PRINCIPAL
            let tabela = "<table class='table table-sm table-bordered table table-head-fixed' id='tableBasicas'>";
            let thead = "<thead><tr>";
            thead += "<th></th><th>Bruto</th><th>Instalado</th>";
            tabela += thead;
            var varre_array = [...data.resultado].forEach((v, i) => {
                tabela +=
                    "<tr><td>" + v.mes + "</td><td>" + v.qtd_venda + "</td><td>" + v.qtd_instalada + "</tr>";
            });
            varre_array
            tabela += "</table>";
            document.querySelector("#datatable_vendas_bruto_instalacao").innerHTML = tabela;
            geraGrafico();
            geraGrafico_Bruto(data.bruto);
            geraGrafico_Instalado(data.instalado);
        })
    });
}

function geraGrafico() {
    Highcharts.chart('container1', {
        data: {
            table: 'tableBasicas'
        },
        chart: {
            type: 'column'
        },
        title: {
            text: 'Analise de bruto e instalação - ' + input_data.value
        },
        yAxis: {
            allowDecimals: false,
            title: {
                text: 'Quantidade'
            }
        },
        plotOptions: {
            series: {
                dataLabels: {
                    enabled: true,
                }
            }
        },
        tooltip: {
            formatter: function () {
                return '<b>' + this.series.name + '</b><br/>' +
                    this.point.y + ' ' + this.point.name.toLowerCase();
            }
        }
    });
}

function geraGrafico_Bruto(dados) {
    var equipe_j = [];
    [...dados].forEach((v, i) => {
        equipe_j.push({
            data: [
                parseInt(v.qtd_venda),
                parseInt(v.JAN),
                parseInt(v.FEV),
                parseInt(v.MAR),
                parseInt(v.ABR),
                parseInt(v.MAI),
                parseInt(v.JUN),
                parseInt(v.JUL),
                parseInt(v.AGO),
                parseInt(v.SET),
                parseInt(v.OUT),
                parseInt(v.NOV),
                parseInt(v.DEZ)

            ],
            name: v.EQUIPE
        })
    })

    Highcharts.chart('container2', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Analise de vendas <b>BRUTA</b> por equipe - ' + input_data.value
        },
        subtitle: {
            text: 'Origem: SISCONP'
        },
        xAxis: {

            categories: [
                '<b>TOTAL</b>',
                'Jan',
                'Fev',
                'Mar',
                'Abr',
                'Mai',
                'Jun',
                'Jul',
                'Ago',
                'Set',
                'Out',
                'Nov',
                'Dez'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Quantidade'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0,
                borderWidth: 0
            }
        },
        series: equipe_j
    });
}
function geraGrafico_Instalado(dados) {
    var equipe_j = [];
    [...dados].forEach((v, i) => {
        equipe_j.push({
            data: [
                parseInt(v.qtd_venda),
                parseInt(v.JAN),
                parseInt(v.FEV),
                parseInt(v.MAR),
                parseInt(v.ABR),
                parseInt(v.MAI),
                parseInt(v.JUN),
                parseInt(v.JUL),
                parseInt(v.AGO),
                parseInt(v.SET),
                parseInt(v.OUT),
                parseInt(v.NOV),
                parseInt(v.DEZ)

            ],
            name: v.EQUIPE
        })
    })

    Highcharts.chart('container3', {
        chart: {
            type: 'column'
        },
        title: {
            text: 'Analise de vendas <b>INSTALADO</b> por equipe - ' + input_data.value
        },
        subtitle: {
            text: 'Origem: SISCONP'
        },
        xAxis: {

            categories: [
                '<b>TOTAL</b>',
                'Jan',
                'Fev',
                'Mar',
                'Abr',
                'Mai',
                'Jun',
                'Jul',
                'Ago',
                'Set',
                'Out',
                'Nov',
                'Dez'
            ],
            crosshair: true
        },
        yAxis: {
            min: 0,
            title: {
                text: 'Quantidade'
            }
        },
        tooltip: {
            headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
            pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y:.1f}</b></td></tr>',
            footerFormat: '</table>',
            shared: true,
            useHTML: true
        },
        plotOptions: {
            column: {
                pointPadding: 0,
                borderWidth: 0
            }
        },
        series: equipe_j
    });
}






