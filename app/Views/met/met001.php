<form id="form_tipo_meta" autocomplete="off">

    <div class="form_crud_modal">
        <input type="hidden" name="ID" id="ID" />
        <div class="row">
            <div class="col-3">
                <label for="COMPETENCIA">Compet&ecirc;ncia</label>
                <input type="month" class="form-control form-control-sm text-center" name="COMPETENCIA" id="COMPETENCIA"
                    required />
            </div>

            <div class="col-2">
                <label for="TIPO">Tipo</label>
                <select class="form-control form-control-sm" name='TIPO' id="TIPO" required>
                    <option value=''>Selecione...</option>
                    <option value='1'>Unit&aacute;rio</option>
                    <option value='2'>Faturamento</option>
                </select>
            </div>
        </div>
        <?php echo ns_BtnFormulario(); ?>
    </div>
    <?php echo ns_BtNovo(); ?>
    <table class="table table-bordered dataTable table-sm" id="tableBasicas">
        <thead>
            <tr>
                <th>Compet&ecirc;ncia</th>
                <th>Tipo</th>
                <th class="text-center">A&ccedil;&atilde;o</th>
            </tr>
        </thead>
    </table>
</form>