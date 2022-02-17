<form method="post" id="form_busca_dias">
    <div class="row">
        <div class="col-6 col-sm-2">
            <label>Competencia</label>
            <input type="month" class="form-control form-control-sm" value="<?php echo date('Y-m'); ?>" name="COMPETENCIA" id='COMPETENCIA' required pattern="[0-9]{4}-[0-9]{2}">
        </div>
        <div class="col-6 col-sm-2 offset-md-2">
            <label>&nbsp;</label>
            <div><button type="button" disabled class="btn btn-success btn-sm btn_submit float-right">Salvar</button></div>
        </div>
    </div>
    <br>
</form>
<form method="post" id="form_peso_dias">
    <div class="row">
        <div class="col-6 col-sm-6">
            <div class="table-responsive p-0" style="height: 300px;">
                <table id="tableCalendario" class='table table-sm table-bordered table table-head-fixed'>
                    <thead>
                        <tr>
                            <th>DIA SEMANA</th>
                            <th>DIA MES</th>
                            <th style='width: 15%'>PESO</th>
                            <th>TRABALHADO</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</form>