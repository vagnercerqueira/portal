producao_supervisor();
retorna_equipes();

const btn_submit = document.querySelector(".btn_submit");
btn_submit.addEventListener("click", () => {
    producao_supervisor();
})

document.querySelector("input[name='COMPETENCIA']").addEventListener("change", (e) => {
    retorna_equipes();
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
            monta_tb_dias(data.equipe)
        });
    });
}

function monta_tb_dias(response_equipe) {
    //TABLE EQUIPE
    //response_equipe = resposta.equipe;

    tam = 90 / ((response_equipe.dias).length);

    document.querySelector("#table_equipe thead").innerHTML = ``;
    document.querySelector("#table_equipe tbody").innerHTML = ``;
    document.querySelector("#table_equipe tfoot").innerHTML = ``;

    document.querySelector("#table_equipe thead").innerHTML = `		
<tr><th style='width:10%;'><span>EQUIPE&darr;</span><span class='float-right'>DIA&rarr;</span></th><th style='width:` + tam + `%'>` + (response_equipe.dias).join(`</th><th  class='text-center' style='width:"+tam+"%'>`) + `</th><th>TOT</th></th></tr>
`;
    document.querySelector("#table_equipe tfoot").innerHTML = "<tr><th style='width=10%'>TOT</th><th style='width:" + tam + "%'>" + (response_equipe.total_geral).join("</th><th style='width:" + tam + "%'  class='text-center'>") + "</th></tr>";;
    rows = "";
    $i = 1;
    Object.entries(response_equipe.equipes).forEach(([key, value]) => {
        rows += "<tr><td style='width:10%'>" + key + "</td><td style='width:" + tam + "%' class='text-center'>" + (value.dias_equipe).join("</td><td  class='text-center' style='width:" + tam + "%'>") + "</td><th class='text-center'>" + (value.total) + "</th></tr>";
        if ($i == Object.entries(response_equipe.equipes).length) {
            document.querySelector("#table_equipe tbody").innerHTML = rows;
        }
        $i++;
    });
}

function retorna_equipes() {
    let data = new FormData(document.getElementById('form_busca_mes'));
    fetch(pag_url + 'ajax_retorna_requipes', {
        method: 'POST',
        credentials: 'same-origin',
        body: data
    }).then(response => {
        return response.json();
    }).then(response => {

        var equipes = document.querySelector("select[name='EQUIPE']");
        var meta = document.querySelector("input[name='TPMETA']");
        equipes.innerHTML = '';
        meta.value = response.meta;
        Object.entries(response.dados).forEach(([key, value]) => {
            opt = document.createElement("option");
            opt.value = key;
            opt.text = value;
            equipes.add(opt);
        })
    }).
    catch(err => { console.log("Erro na resposta do servidor", err); })
}