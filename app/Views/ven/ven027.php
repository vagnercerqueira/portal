<div class="row">
    <div class="col-12 col-sm-12">
        <form method="post" id="form_busca">
            <div class="row">
                <div class="col-md-2 col-5">
                    <label>Nome mailing</label>
                    <select name="f_nome_mailing" class="form-control form-control-sm" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($option_nome_mailing as $v) : ?>
                            <option value="<?php echo $v['descr']; ?>"><?php echo $v['descr']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 col-5">
                    <label>Cidade</label>
                    <select name="f_cidade_mailing" class="form-control form-control-sm">
                        <option value="">Selecione...</option>
                    </select>
                </div>
                <div class="col-md-1 col-5">
                    <label>CEP</label>
                    <select name="f_cep_mailing" class="form-control form-control-sm">
                        <option value="">Selecione...</option>
                    </select>
                </div>
                <div class="col-1">
                    <label>&nbsp;</label>
                    <div><button type="submit" class="btn btn-info btn-sm"><i class="fas fa-search"></i></button></div>
                </div>
                <div class="col-2">
                    <label>&nbsp;</label>
                    <div><a class="btn btn-block btn-outline-info disabled btn-sm bt_tot_filtro">0 registros filtrados</a></div>
                </div>
                <div class="col-2">
                    <label>&nbsp;</label>
                    <div><a class="btn btn-block btn-outline-info btn_tot_reg_base disabled btn-sm"><?php echo $option_tot_clientes; ?> registros na base</a></div>
                </div>
            <!--    <div class="col-2 col_bt_down" style="display: none;">
                    <label>&nbsp;</label>
                    <div><a class="btn btn-success btn-sm btn_download_mailings float-right" style="color:white;"><i class="fa fa-file-excel"></i>&nbsp;exportar csv</a></div>
                </div> -->
            </div>
            <br>
			<div class="row">
                <div class="col-2 ml-auto" style="display: none;">
					<input type="search" class="form-control form-control-sm input-table-filter" placeholder="Filtrar">
				</div>
			</div>
			<br>
            <div class="row">
                <div class="col-12 mtable">				
                    <div style=" height: 300px;" class="table-responsive">
                        <table class="table table-striped table-sm table-bordered table-head-fixed"  id="table_mailing" style="font-size: 0.8em;">
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
<!--
<div class="modal fade" id="modal-deleta_mailing">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirma a exclusao dos mailings filtrados ?</h4>
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
                            -->