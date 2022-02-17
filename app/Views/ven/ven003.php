<form id="form_planos" autocomplete="off">

    <div class="form_crud_modal">
        <input type="hidden" name="ID" id="ID" />
        <div class="row">
            <div class="col-xl-3">
                <label for="ID_CAMPANHA">Campanha</label>
                <?php echo $campanhas ?>
            </div>		
            <div class="col-xl-2">
                <label for="DESC_FIBRA">Fibra</label>
                <?php echo $fibra ?>
            </div>
            <div class="col-xl-2">
                <label for="DESC_TV">TV</label>
                <?php echo $tv ?>
            </div>

            <div class="col-xl-1">
                <label for="VALOR_FATURAMENTO">Valor</label>
                <input type="text" class="form-control form-control-sm text-center" name="VALOR_FATURAMENTO"
                    id="VALOR_FATURAMENTO" minlength="1" maxlength="6" required />
            </div>

            <div class="col-xl-2">
                <label for="DT_INI">Data inicial</label>
                <input type="date" class="form-control form-control-sm" name="DT_INI" id='DT_INI' required />
            </div>

            <div class="col-xl-2">
                <label for="DT_FIM">Data final</label>
                <input type="date" class="form-control form-control-sm" name="DT_FIM" id='DT_FIM' required />
            </div>

        </div>
        <?php echo ns_BtnFormulario(); ?>

    </div>
    <?php echo ns_BtNovo(); ?>
    <table class="table table-bordered dataTable table-sm" id="tableBasicas">
        <thead>
            <tr>
				<th>Campanha</th>
                <th>Fibra</th>
                <th>TV</th>
                <th>Vl. Faturamento</th>
                <th>Data Inicio</th>
                <th>Data Fim</th>
                <th class="text-center">A&ccedil;&atilde;o</th>
            </tr>
        </thead>
    </table>
</form>