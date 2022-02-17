//producao_supervisor();
retorna_meta();

const btn_submit = document.querySelector(".btn_submit");
btn_submit.addEventListener("click", () => {
    producao_supervisor();
})

document.querySelector("input[name='COMPETENCIA']").addEventListener("change", (e) => {
    retorna_meta();
});

function producao_supervisor() {
    let data = new FormData(document.getElementById('form_busca_mes'));
    fetch(pag_url + 'producao', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    }).then(function(response) {
        response.json().then(function(data) {
            document.querySelector("#corpo_supervisor").innerHTML = data.tendencia;
            monta_tb_dias(data.vendedor)
        });
    });
}

function monta_tb_dias(response) {
    tam = 90 / ((response.dias).length);

    document.querySelector("#table_producao thead").innerHTML = ``;
    document.querySelector("#table_producao tbody").innerHTML = ``;
    document.querySelector("#table_producao tfoot").innerHTML = ``;

    document.querySelector("#table_producao thead").innerHTML = `		
    <tr><th style='width:10%;'><span>VENDEDOR&darr;</span><span class='float-right'>DIA&rarr;</span></th><th style='width:` + tam + `%'>` + (response.dias).join(`</th><th  class='text-center' style='width:"+tam+"%'>`) + `</th><th>TOT</th></th></tr>
    `;
    document.querySelector("#table_producao tfoot").innerHTML = "<tr><th style='width=10%'>TOT</th><th style='width:" + tam + "%'>" + (response.total_geral).join("</th><th style='width:" + tam + "%'  class='text-center'>") + "</th></tr>";;
    rows = "";
    $i = 1;
    Object.entries(response.vendedores).forEach(([key, value]) => {
        rows += "<tr><td style='width:10%'>" + key + "</td><td style='width:" + tam + "%' class='text-center'>" + (value.dias_vendedor).join("</td><td  class='text-center' style='width:" + tam + "%'>") + "</td><th class='text-center'>" + (value.total) + "</th></tr>";
        if ($i == Object.entries(response.vendedores).length) {
            document.querySelector("#table_producao tbody").innerHTML = rows;
        }
        $i++;
    });
}

function retorna_meta() {
    let data = new FormData(document.getElementById('form_busca_mes'));
    fetch(pag_url + 'ajax_retorna_requipes', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    }).then(response => {
        return response.json();
    }).then(response => {
        var meta = document.querySelector("input[name='TPMETA']");
        meta.value = response.meta;
    }).
    catch(err => { console.log("Erro na resposta do servidor", err); })
}