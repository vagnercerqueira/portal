<style>
#container1,
#container2,
#container3 {
    height: 300px;

}

.highcharts-figure,
.highcharts-data-table table {
    min-width: 85%;
    max-width: 100%;
    margin: 1em auto;
}

#tableBasicas {
    font-family: Verdana, sans-serif;
    border-collapse: collapse;
    border: 1px solid #EBEBEB;
    margin: 10px auto;
    text-align: center;
    width: 100%;
    max-width: 500px;
}

#tableBasicas caption {
    padding: 1em 0;
    font-size: 1.2em;
    color: #555;
}

#tableBasicas th {
    font-weight: 600;
    padding: 0.5em;
}

#tableBasicas td,
#tableBasicas th,
#tableBasicas caption {
    padding: 0.5em;
}

#tableBasicas thead tr,
#tableBasicas tr:nth-child(even) {
    background: #f8f8f8;
}

#tableBasicas tr:hover {
    background: #f1f7ff;
}

#datatable_vendas_bruto_instalacao {
    display: none;
}
</style>

<!--TABELA PRINCIPAL-->
<div class="row">
    <div class="col-12">
        <figure class="highcharts-figure">
            <form method="post" id="form_busca_mes">
                <div class="row mb-2">
                    <div class="col-8">
                    </div>
                    <div class="col-4 float-right">
                        <span class="float-right">
                            <select name="COMPETENCIA" id="COMPETENCIA" class="form-control form-control-sm">
                                <option value="2021" selected="selected">2021</option>
                            </select>
                        </span>
                    </div>
                </div>
            </form>
            <div id="container1"></div>
            <div class="table-responsive p-0" style="height: 300px;" id="datatable_vendas_bruto_instalacao">
            </div>
        </figure>
    </div>
</div>
<!--TABELA BRUTA-->
<div class="row">
    <div class="col-6">
        <figure class="highcharts-figure">
            <div id="container2"></div>
        </figure>
    </div>
    <div class="col-6">
        <figure class="highcharts-figure">
            <div id="container3"></div>
        </figure>
    </div>

</div>