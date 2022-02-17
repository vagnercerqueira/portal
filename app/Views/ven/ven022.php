<form id="form_envio_emails" autocomplete="off">

    <div class="form_crud_modal">
        <input type="hidden" name="ID" id="ID" />
        <div class="row">
            <div class="col-3">
                <label for="ID_GRUPO">Grupo</label>
                <?php echo $grupo ?>
            </div>
            <div class="col-1">
                <label for="BOV_CSV">BOV CSV</label>
                <select class="form-control form-control-sm" name='BOV_CSV'>
                    <option value='S'>Sim</option>
                    <option value='N' selected>Nao</option>
                </select>
            </div>
            <div class="col-1">
                <label for="DFV_CSV">DFV CSV</label>
                <select class="form-control form-control-sm" name='DFV_CSV'>
                    <option value='S'>Sim</option>
                    <option value='N' selected>Nao</option>
                </select>
            </div>
            <div class="col-2">
                <label for="BLINDAGEM_CSV">BLINDAGEM CSV</label>
                <select class="form-control form-control-sm" name='BLINDAGEM_CSV'>
                    <option value='S'>Sim</option>
                    <option value='N' selected>Nao</option>
                </select>
            </div>
            <div class="col-2">
                <label for="LINHA_PGTO_CSV">LINHA PGTO CSV</label>
                <select class="form-control form-control-sm" name='LINHA_PGTO_CSV'>
                    <option value='S'>Sim</option>
                    <option value='N' selected>Nao</option>
                </select>
            </div>            
            <div class="col-2">
                <label for="VENDA_LOTE_CSV">VENDA LOTE CSV</label>
                <select class="form-control form-control-sm" name='VENDA_LOTE_CSV'>
                    <option value='S'>Sim</option>
                    <option value='N' selected>Nao</option>
                </select>
            </div>
            <div class="col-2">
                <label for="MAILING_CSV">MAILING CSV</label>
                <select class="form-control form-control-sm" name='MAILING_CSV'>
                    <option value='S'>Sim</option>
                    <option value='N' selected>Nao</option>
                </select>
            </div>            
        </div>
        <?php echo ns_BtnFormulario(); ?>
    </div>
    <?php echo ns_BtNovo(); ?>
    <table class="table table-bordered dataTable table-sm" id="tableBasicas">
        <thead>
            <tr>
                <th>GRUPO</th>
                <th>BOV</th>
                <th>DFV</th>
                <th>BLINDAGEM</th>
                <th>VENDA LOTE</th>
                <th>LINHA</th>
                <th>MAILING</th>
                <th class="text-center">A&ccedil;&atilde;o</th>
            </tr>
        </thead>
    </table>
</form>