<form id="form_meta_vendedor" autocomplete="off">

    <div class="form_crud_modal">
        <input type="hidden" name="ID" id="ID" />
        <input type="hidden" name="TIPO" id="TIPO" />
        <div class="row">
            <div class="col-2">
                <label for="COMPETENCIA">Compet&ecirc;ncia</label>
                <input type="month" class="form-control form-control-sm text-center" name="COMPETENCIA" id="COMPETENCIA"
                    required />
            </div>

            <div class="col-2">
                <label for="EQUIPE">Equipe</label>
                <?php echo $equipe; ?>
            </div>

            <div class="col-xl-2">
                <label for="">Supervisor</label>
                <input type="text" name="SUPERVISOR" id='SUPERVISOR' class="form-control form-control-sm" disabled />
            </div>
            <div class="col-xl-2">
                <label for="TIPO_META">Tipo de meta</label>
                <input type="text" name="TIPO_META" id='TIPO_META' class="form-control form-control-sm" readonly />
            </div>
            <div class="col-xl-2">
                <label for="VENDA">Meta venda</label>
                <input type="text" name="VENDA" id='VENDA' class="form-control form-control-sm text-center" required />
            </div>

            <div class="col-xl-2">
                <label for="INSTALACAO">Meta instala&ccedil;&atilde;o</label>
                <input type="text" name="INSTALACAO" id='INSTALACAO' class="form-control form-control-sm text-center"
                    required />
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
                <th>Equipe</th>
                <th>Supervisor</th>
                <th>Venda</th>
                <th>Instala&ccedil;&atilde;o</th>
                <th class="text-center">A&ccedil;&atilde;o</th>
            </tr>
        </thead>
    </table>
</form>