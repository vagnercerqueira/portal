<div class="row">
    <div class="col-12 col-sm-12">
        <form method="post" id="form_upload_linha_pgto">
            <div class="row">
                <div class="col-10">
                    <label class='text-danger'>Arquivos aceitos: .csv</label>
                    <span class='float-right timer_upload_linha_pgto'>
                        <span class="timer_upload_ini_linha_pgto"></span>
                        <span class="timer_upload_fim_linha_pgto"></span>
                    </span>
                    <input type="file" accept=".csv" required id="files_linha_pgto" style="visibility: hidden" multiple name="files_linha_pgto[]" />
                </div>
				<div class="col-md-2">
					<label>&nbsp;</label>
					<a class="btn btn-success btn-sm btn_download_lpgto float-right"
						data-colscsv="<?php echo implode(";", $campos_lpgto); ?>"><i
							class="fa fa-file-excel"></i>&nbsp;Baixar modelo</a>
				</div>				
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="btn-group w-100">
                        <span class="btn btn-success col fileinput-button dz-clickable" onclick="openDialoglinha_pgto()">
                            <i class="fas fa-plus"></i>
                            <span>Add arquivos linha pagamento</span>
                        </span>
                        <button type="submit" class="btn btn-primary col start">
                            <i class="fas fa-upload"></i>
                            <span>Iniciar upload</span>
                        </button>
                        <button type="reset" class="btn btn-warning col cancel_linha_pgto">
                            <i class="fas fa-times-circle"></i>
                            <span>Cancelar upload</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card card-default">
                <div class="card-body">
                    <div class="row row_msg_upload_linha_pgto"></div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table_files_linha_pgto table-sm table-bordered">
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form method="post" id="form_busca">
            <div class="row">
                <div class="col-2">
                    <label>Num os/CPF</label>
                    <input type="text" autocomplete="off" title="minimo de 5 caracteres" class="form-control text-center form-control-sm" minlength="5" placeholder="Buscar por num os ou cpf" name='num_os'>
                </div>
                <div class="col-1">
                    <label>Tipo</label>
                    <select class="form-control form-control-sm" name='tipo' id='tipo'>
                        <option value='T' selected>Todos</option>
                        <option value='C'>Comissao</option>
                        <option value='D'>Estorno</option>
                    </select>
                </div>
                <div class="col-2">
                    <label>Data instalacao ini</label>
                    <input type="date" autocomplete="off" required title="minimo de 10 caracteres" class="form-control text-center form-control-sm" minlength="10" placeholder="Buscar por num os" name='data_ini'>
                </div>
                <div class="col-2">
                    <label>Data instalacao fim</label>
                    <input type="date" autocomplete="off" required title="minimo de 10 caracteres" class="form-control text-center form-control-sm" minlength="10" placeholder="Buscar por num os" name='data_fim'>
                </div>
                <div class="col-1">
                    <label>&nbsp;</label>
                    <div><button type="submit" class="btn btn-info btn-sm"><i class="fas fa-search"></i></button></div>
                </div>
                <div class="col-lg-1 col-6">
                    <label>Comissao</label>
                    <div><button type="button" class="btn btn-block btn-outline-success disabled btn-sm btn_total">0</button></div>
                </div>
                <div class="col-lg-1 col-6">
                    <label>Estorno</label>
                    <div><button type="button" class="btn btn-block btn-outline-danger disabled btn-sm btn_total_estorno">0</button></div>
                </div>
                <div class="col-1 btn_exclui" style='display: none;'>
                    <label>Excluir</label>
                    <div><button type="button" data-toggle="modal" data-target="#modal-deleta_lin" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>&nbsp;linhas</button></div>
                </div>
                <div class="col-1 btn_exporta" style='display: none;'>
                    <label>Exportar</label>
                    <div><a href="javascript:;" class="btn btn-success btn-sm btn_download"><i class="fa fa-file-excel"></i>&nbsp;Exportar</a></div>
                </div>				
            </div>

            <br>
            <div class="row">
                <div class="col-12">
                    <table class="table table-striped table-sm" id="table_lpgto">
                        <thead>
                            <tr>
                                <th>COD SAP</th>
                                <th>VALOR</th>
                                <th>NUM OS</th>
                                <th>DT INSTALACAO</th>
                                <th>FILIAL</th>
                                <th>CICLO</th>
                                <th>QUINZENA</th>
                                <th>CPF CLIENTE</th>
                                <th>TIPO</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </form>

    </div>
</div>

<div class="modal fade" id="modal-deleta_lin">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirma a exclusao das linhas filtradas ?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn_conf_exclui">Confirmar</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>