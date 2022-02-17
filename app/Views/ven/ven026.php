<div class="row">
    <div class="col-12 col-sm-12">
        <form method="post" id="form_upload_mailing">
            <div class="row">
                <div class="col-md-2 col-6">
                    <label>Nome mailing</label>
                    <input class="form-control form-control-sm" name='nome_mailing' type="text" maxlength="30" required minlength="5" placeholder="Nome do mailing">
                </div>
                <div class="col-md-2 col-6 offset-md-8">
                    <label>&nbsp;</label>
                    <div>
                        <a data-colscsv="<?php echo implode(";", $option_campos); ?>" class="btn btn-success btn-sm btn_download_mailing float-right"><i class="fa fa-file-excel"></i>&nbsp;Baixar modelo</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label class='text-danger'>Arquivos aceitos: .csv</label>
                    <span class='float-right timer_upload_mailing'>
                        <span class="timer_upload_ini_mailing"></span>
                        <span class="timer_upload_fim_mailing"></span>
                    </span>
                    <input type="file" accept=".csv" required id="files_mailing" style="visibility: hidden" name="files_mailing" />
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="btn-group w-100">
                        <span class="btn btn-success col fileinput-button dz-clickable" onclick="openDialogmailing()">
                            <i class="fas fa-plus"></i>
                            <span>Add arquivos mailing</span>
                        </span>
                        <button type="submit" class="btn btn-primary col start">
                            <i class="fas fa-upload"></i>
                            <span>Iniciar upload</span>
                        </button>
                        <button type="reset" class="btn btn-warning col cancel_mailing">
                            <i class="fas fa-times-circle"></i>
                            <span>Cancelar upload</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card card-default">
                <div class="card-body">
                    <div class="row row_msg_upload_mailing"></div>
                    <div class="row">
                        <div class="col-12">
                            <table class="table table-striped table_files_mailing table-sm table-bordered">
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <form method="post" id="form_busca">
            <div class="row">
                <div class="col-md-2 col-5">
                    <label>Nome mailing</label>
                    <select name="f_nome_mailing" class="form-control form-control-sm" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($option_nome_mailing as $k=>$v) : ?>
                            <option value="<?php echo $v['id']; ?>"><?php echo $v['nome']." (data: ".$v['data'].")"; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 col-5">
                    <label>Cidade</label>
                    <select name="f_cidade_mailing" class="form-control form-control-sm">
                        <option value="">Selecione...</option>
                    </select>
                </div>
           <!--     <div class="col-md-1 col-5">
                    <label>CEP</label>
                    <select name="f_cep_mailing" class="form-control form-control-sm">
                        <option value="">Selecione...</option>
                    </select>
                </div> -->
                <div class="col-1">
                    <label>&nbsp;</label>
                    <div><a class="btn btn-success btn-sm float-right col_bt_down" href="<?php echo base_url('ven/ven020/download_mailing'); ?>" download style="color:white;"><i class="fa fa-file-excel"></i>&nbsp;gerar csv</a></div>
                </div>                
                <div class="col-1">
                    <label>&nbsp;</label>
                    <div><button type="submit" class="btn btn-info btn-sm"><i class="fas fa-table"></i>&nbsp;Gerar tabela</button></div>
                </div>
                <div class="col-1 col_bt_exclui">
                    <label>&nbsp;</label>
                    <div><a href='javascript:;' type="button" data-toggle="modal" data-target="#modal-deleta_mailing" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>&nbsp;Excluir mailing</a></div>
                </div>                
                <div class="col-2">
                    <label>&nbsp;</label>
                    <div><a class="btn btn-block btn-outline-info disabled btn-sm bt_tot_filtro">0 registros filtrados</a></div>
                </div>
                <div class="col-2">
                    <label>&nbsp;</label>
                    <div><a class="btn btn-block btn-outline-info btn_tot_reg_base disabled btn-sm"><?php echo $option_tot_reg; ?> registros na base</a></div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-12 mtable">
                    <div style=" height: 300px;" class="table-responsive">
                        <table class="table table-striped table-sm table-bordered table-head-fixed" id="table_mailing" style="font-size: 0.8em;">
                            <thead>
                                <tr>
                                    <?php
                                    foreach ($option_campos as $v) {
                                        echo "<th style='width:7%'>" . $v . "</th>";
                                    }
                                    ?>
                                    <th>Viabilidade</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<div class="modal fade" id="modal-deleta_mailing">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirma a exclusao do mailing filtrado ?</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary btn_conf_exclui">Confirmar</button>
            </div>
        </div>
        
    </div>
    
</div>