<div class="tab-pane fade active show" id="custom-tabs-venda_manual-profile" role="tabpanel"
    aria-labelledby="custom-tabs-venda_manual-profile-tab">
    <form id="form_vendas" autocomplete="off" enctype="multipart/form-data">
    </form>
</div>


<div class="row">
    <div class="col-md-10">
        <?php //echo ns_BtNovo(); ?>
    </div>
    <div class="col-2">
        <span class="float-right">
            <button type="button" data-toggle="modal" data-target="#modal-exportar_ven"
                class="btn btn-success btn-sm btn_download"><i class="fas fa-file-export"></i>&nbsp;Exportar</button>
        </span>
    </div>
</div>

<div class="row mt-2">
    <div class="col-12 col-sm-12">
        <form id="form_acompanhamento_cliente" autocomplete="off">
            <div class="form_crud_modal">
                <input type="hidden" name="ID" id="ID" />
                <div class="col-md-12">
                    <label for="OBS_ACOMPANHAMENTO">Obs Acompanhamento</label>
                    <textarea class="form-control form-control-sm" rows="5" name="OBS_ACOMPANHAMENTO"
                        id='OBS_ACOMPANHAMENTO' maxlength="1000" style="resize: none;"></textarea>
                    <div id="the-count">
                        <span id="current_acompanhamento">0</span>
                        <span id="maximum">/ 1000</span>
                    </div>
                </div>
                <?php echo ns_BtnFormulario(); ?>
            </div>

            <table class="table table-bordered dataTable table-sm" id="tableBasicas">
                <thead>
                    <tr>
                        <th>Ficha</th>
                        <th>Supervisor</th>
                        <th>Vendedor</th>
                        <th>CPF/CNPJ</th>
                        <th>Cliente</th>
                        <th>Contato</th>
                        <th>Num OS</th>
                        <th>Instala&ccedil;&atilde;o</th>
                        <th>Venc</th>
                        <th data-toggle="tooltip" data-placement="top" title="Venc mes 1">V.1</th>
                        <th data-toggle="tooltip" data-placement="top" title="Venc mes 2">V.2</th>
                        <th data-toggle="tooltip" data-placement="top" title="Venc mes 3">V.3</th>
                        <th data-toggle="tooltip" data-placement="top" title="Venc mes 4">V.4</th>
                        <th>Ativo</th>
                        <th>Adiplente</th>
                        <th class="text-center">A&ccedil;&atilde;o</th>

                    </tr>
                </thead>
            </table>
        </form>
    </div>
</div>

<div class="modal fade" id="modal-exportar_ven">
    <form id="form_expo_vendas">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">M&ecirc;s instala&ccedil;&atilde;o</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-xl-12">
                            <input type="month" class="form-control form-control-sm text-center" name="expo_per_fim"
                                id="expo_per_fim" required />
                        </div>
                    </div>

                    <div class="row mt-3">

                        <div class="col-xl-6">
                            <div class="custom-control custom-radio">
                                <input
                                    class="custom-control-input custom-control-input-danger custom-control-input-outline"
                                    type="radio" id="customRadio6" name="tipo" value='1' required>
                                <label for="customRadio6" class="custom-control-label">Detalhado</label>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <div class="custom-control custom-radio">
                                <input
                                    class="custom-control-input custom-control-input-danger custom-control-input-outline"
                                    type="radio" id="customRadio5" name="tipo" value='2' required>
                                <label for="customRadio5" class="custom-control-label">Resumido</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn_expo_vendas">Confirmar</button>
                </div>
    </form>
</div>