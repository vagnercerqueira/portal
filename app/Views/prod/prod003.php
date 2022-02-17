<form method="post" id="form_busca_mes">
    <div class="row">
        <div class="col-4 col-sm-2">
            <label>Competencia</label>
            <input type="month" class="form-control form-control-sm" value="<?php echo date('Y-m'); ?>"
                name="COMPETENCIA" id='COMPETENCIA' required pattern="[0-9]{4}-[0-9]{2}" required>
        </div>
        <div class="col-md-2">
            <label>Equipe</label>
            <?php echo $option_equipes; ?>
        </div>
        <div class="col-md-2">
            <label>Tipo</label>
            <select class="form-control form-control-sm" id="TIPO" name="TIPO">
                <option value="B" selected>BRUTO</option>
                <option value="I">INSTALADO</option>
            </select>
        </div>
        <div class="col-md-1">
            <label>Meta</label>
            <input type="text" disabled name="TPMETA" class="form-control form-control-sm text-center" />
        </div>

        <div class="col-4 col-sm-2">
            <label>&nbsp;</label>
            <div><button type="button" class="btn btn-primary btn-sm btn_submit">Buscar</button>
            </div>
        </div>
    </div>
    <br>
</form>
<div class="row">
    <div class="col-12 mtable">
        <div style="max-height: 300px;" class="table-responsive">
            <table class="table table-striped table-sm table-bordered table-head-fixed" style="font-size: 0.8em;">
                <thead>
                    <tr>
                        <th>Equipe</th>
                        <th>Vendedor</th>
                        <th class="text-center">Meta</th>
                        <th class="text-center">Acumulado</th>
                        <th class="text-center">Atingido&nbsp;%</th>
                        <th class="text-center">Tend.ABS</th>
                        <th class="text-center">Tend.REL&nbsp;%</th>
                        <th class="text-center">100%</th>
                        <th class="text-center">80%</th>
                    </tr>
                </thead>
                <tbody id="corpo_supervisor"></tbody>
            </table>
        </div>
    </div>
</div>
<br>
<div class="row">
    <div class="col-12">
        <div style="max-height: 600px;" class="table-responsive">
            <table class="table table-striped table-sm table-bordered table-head-fixed" id="table_producao"
                style="font-size: 0.8em;">
                <thead>
                    <tr></tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                </tfoot>
            </table>
        </div>
    </div>
</div>