<style>
.desativado {
    background: #EEE;
    pointer-events: none;
    touch-action: none;
}
</style>
<div class="row">
    <div class="col-12 col-sm-12">
        <div class="card card-primary card-tabs">
            <div class="card-header p-0 pt-1">
                <ul class="nav nav-tabs" id="custom-tabs-one-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link links_uploads active" id="custom-tabs-venda_manual-profile-tab"
                            data-toggle="pill" href="#custom-tabs-venda_manual-profile" role="tab"
                            aria-controls="custom-tabs-venda_manual-profile" aria-selected="true">Venda manual</a>
                    </li>

                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="custom-tabs-one-tabContent">

                    <div class="tab-pane fade" id="custom-tabs-venda_lote-home" role="tabpanel"
                        aria-labelledby="custom-tabs-venda_lote-home-tab">
                        <form method="post" id="form_upload_venda_lote">
                            <div class="row">
                                <div class="col-12">
                                    <label class='text-danger'>Arquivos aceitos : .csv</label>
                                    <span class='float-right timer_upload_venda_lote'>
                                        <span class="timer_upload_ini_venda_lote"></span>
                                        <span class="timer_upload_fim_venda_lote"></span>
                                    </span>
                                    <input type="file" accept=".csv" required id="files_venda_lote"
                                        style="visibility: hidden" multiple name="files_venda_lote[]" />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="btn-group w-100">
                                        <span class="btn btn-success col fileinput-button dz-clickable"
                                            onclick="openDialogVenda_lote()">
                                            <i class="fas fa-plus"></i>
                                            <span>Add arquivos vendas lote</span>
                                        </span>
                                        <button type="submit" class="btn btn-primary col start">
                                            <i class="fas fa-upload"></i>
                                            <span>Iniciar upload</span>
                                        </button>
                                        <button type="reset" class="btn btn-warning col cancel_venda_lote">
                                            <i class="fas fa-times-circle"></i>
                                            <span>Cancelar upload</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-default">
                                <div class="card-body">
                                    <div class="row row_msg_upload_venda_lote"></div>
                                    <div class="row">
                                        <div class="col-12">
                                            <table
                                                class="table table-striped table_files_venda_lote table-sm table-bordered">
                                                <tbody></tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="tab-pane fade active show" id="custom-tabs-venda_manual-profile" role="tabpanel"
                        aria-labelledby="custom-tabs-venda_manual-profile-tab">
                        <form id="form_vendas" autocomplete="off" enctype="multipart/form-data">

                            <div class="form_crud_modal">
                                <input type="hidden" name="ID" id="ID" />
                                <input type="hidden" name="ID_SUPERVISOR" id="ID_SUPERVISOR" />

                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="padding: 0.2rem 1.25rem">
                                        <h3 class="card-title">
                                            <i class="fas fa-edit"></i>
                                            Dados da venda
                                        </h3>
                                    </div>
                                    <div class="card-body" style="padding: 0.2rem 1.25rem">
                                        <div class="tab-content" id="custom-content-above-tabContent">
                                            <div class="tab-pane panel-primary fade show active"
                                                id="custom-content-above-home" role="tabpanel"
                                                aria-labelledby="custom-content-above-home-tab">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <label for="DT_ATIVACAO">Data ativa&ccedil;&atilde;o</label>
                                                        <input type="date"
                                                            class="form-control form-control-sm text-center"
                                                            name="DT_ATIVACAO" id="DT_ATIVACAO" required
                                                            value="<?php echo date("Y-m-d"); ?>" />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="DT_VENDA_CSV">Data Venda</label>
                                                        <input type="date"
                                                            class="form-control form-control-sm text-center"
                                                            name="DT_VENDA_CSV" id="DT_VENDA_CSV" required
                                                            value="<?php echo date("Y-m-d"); ?>" />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="ID_BUNDLE">Id Bundle</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="ID_BUNDLE" id="ID_BUNDLE" />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="NUM_OS">Nº Pedido</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="NUM_OS" id="NUM_OS" pattern="^[0-9-]{1,25}$" />
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <label for="status_ativacao">Status
                                                            ativa&ccedil;&atilde;o</label>
                                                        <?php echo $status_ativacao; ?>
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="equipe">Equipe</label>
                                                        <?php echo $equipe; ?>
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="">Supervisor</label>
                                                        <input type="text" name="SUPERVISOR" id='SUPERVISOR'
                                                            class="form-control form-control-sm" disabled
                                                            value="<?php echo $supervisor; ?>" />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="ID_VENDEDOR">Vendedor</label>
                                                        <?php echo $vendedor; ?>
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="AUDIO_AUDIT_QUALITY_1">Auditoria 1</label>
                                                        <div class="custom-file">
                                                            <input type="file"
                                                                class="custom-file-input form-control-sm "
                                                                id="AUDIO_AUDIT_QUALITY_1" name="AUDIO_AUDIT_QUALITY_1"
                                                                accept="audio/*">
                                                            <label class="custom-file-label form-control-sm"
                                                                for="AUDIO_AUDIT_QUALITY_1">Selecionar</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="AUDIO_AUDIT_QUALITY_2">Auditoria 2</label>
                                                        <div class="custom-file">
                                                            <input type="file" class="custom-file-input form-control-sm"
                                                                id="AUDIO_AUDIT_QUALITY_2" name="AUDIO_AUDIT_QUALITY_2"
                                                                accept="audio/*">
                                                            <label class="custom-file-label"
                                                                for="AUDIO_AUDIT_QUALITY_2">Selecionar</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <label for="OBS_ATIVACAO">Obs ativa&ccedil;&atilde;o</label>
                                                    <textarea class="form-control form-control-sm" rows="2"
                                                        name="OBS_ATIVACAO" id='OBS_ATIVACAO' maxlength="500"
                                                        style="resize: none;"></textarea>
                                                    <div id="the-count">
                                                        <span id="current_ativacao">0</span>
                                                        <span id="maximum">/ 1000</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="padding: 0.2rem 1.25rem">
                                        <h3 class="card-title">
                                            <i class="fas fa-edit"></i>
                                            Dados pessoais
                                        </h3>
                                    </div>
                                    <div class="card-body" style="padding: 0.2rem 1.25rem">
                                        <div class="tab-content" id="custom-content-above-tabContent">
                                            <div class="tab-pane panel-primary fade show active"
                                                id="custom-content-above-home" role="tabpanel"
                                                aria-labelledby="custom-content-above-home-tab">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <label for="CPF_CNPJ_CSV">CPF / CNPJ</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="CPF_CNPJ_CSV" id="CPF_CNPJ_CSV"
                                                            pattern="\d{3}\.\d{3}\.\d{3}-\d{2}|\d{2}\.\d{3}\.\d{3}/\d{4}-\d{2}"
                                                            minlength="14" maxlength="18"
                                                            title="FORMATO: CPF[000.000.000-00] OU CNPJ[00.000.000/0000-00]" />
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="NOME_CLIENTE_CSV">Nome do cliente</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="NOME_CLIENTE_CSV" id="NOME_CLIENTE_CSV" minlength="3"
                                                            maxlength="100" required />
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <label for="DT_NASCIMENTO_CSV">Nascimento</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="DT_NASCIMENTO_CSV" id="DT_NASCIMENTO_CSV"
                                                            minlength="10" maxlength="10" />
                                                    </div>

                                                    <div class="col-xl-1">
                                                        <label for="GENERO_CSV">Sexo</label>
                                                        <select class="form-control form-control-sm" id="GENERO_CSV"
                                                            name="GENERO_CSV">
                                                            <option value="" selected>Selecione...</option>
                                                            <option value="F">F</option>
                                                            <option value="M">M</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <label for="RG_CSV">RG</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="RG_CSV" id="RG_CSV" minlength="3" maxlength="20" />
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <label for="ORGAO_EXPEDIDOR_CSV">Org&atilde;o expedidor</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="ORGAO_EXPEDIDOR_CSV" id="ORGAO_EXPEDIDOR_CSV"
                                                            minlength="2" maxlength="20" />
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="NOME_MAE_CSV">Nome m&atilde;e</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="NOME_MAE_CSV" id="NOME_MAE_CSV" minlength="2"
                                                            maxlength="100" />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="CONTATO_PRINCIPAL_CSV">Contato principal</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="CONTATO_PRINCIPAL_CSV" id="CONTATO_PRINCIPAL_CSV"
                                                            minlength="13" maxlength="14" />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="CONTATO_SECUNDARIO_CSV">Contato secundario</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="CONTATO_SECUNDARIO_CSV" id="CONTATO_SECUNDARIO_CSV"
                                                            minlength="13" maxlength="14" />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="EMAIL_CSV">Email</label>
                                                        <input type="email" class="form-control form-control-sm"
                                                            name="EMAIL_CSV" id="EMAIL_CSV" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="padding: 0.2rem 1.25rem">
                                        <h3 class="card-title">
                                            <i class="fas fa-edit"></i>
                                            Dados instala&ccedil;&atilde;o
                                        </h3>
                                    </div>
                                    <div class="card-body" style="padding: 0.2rem 1.25rem">

                                        <div class="tab-content" id="custom-content-above-tabContent">
                                            <div class="tab-pane fade show active" id="custom-content-above-home"
                                                role="tabpanel" aria-labelledby="custom-content-above-home-tab">
                                                <div class="row">
                                                    <div class="col-xl-2">
                                                        <label for="CEP_INSTALACAO_CSV">CEP</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="CEP_INSTALACAO_CSV" id="CEP_INSTALACAO_CSV"
                                                            minlength="10" maxlength="10" />
                                                    </div>
                                                    <div class="col-xl-3">
                                                        <label for="LOGRADOURO_INSTALACAO_CSV">Logradouro</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="LOGRADOURO_INSTALACAO_CSV"
                                                            id="LOGRADOURO_INSTALACAO_CSV" minlength="3"
                                                            maxlength="100" />
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <label for="NUM_INSTALACAO_CSV">Nº
                                                            instala&ccedil;&atilde;o</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="NUM_INSTALACAO_CSV" id="NUM_INSTALACAO_CSV"
                                                            required />
                                                    </div>


                                                    <div class="col-xl-2">
                                                        <label for="BAIRRO_INSTALACAO_CSV">Bairro</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="BAIRRO_INSTALACAO_CSV" id="BAIRRO_INSTALACAO_CSV"
                                                            minlength="3" maxlength="100" />
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <label for="CIDADE_INSTALACAO_CSV">Cidade</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="CIDADE_INSTALACAO_CSV" id="CIDADE_INSTALACAO_CSV"
                                                            minlength="3" maxlength="100" />
                                                    </div>

                                                    <div class="col-xl-1">
                                                        <label for="UF_INSTALACAO_CSV">UF</label>
                                                        <?php echo $uf_atuacao; ?>
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="REF_INSTALACAO_CSV">Refer&ecirc;ncia</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="REF_INSTALACAO_CSV" id="REF_INSTALACAO_CSV"
                                                            minlength="3" maxlength="100" required />
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="REFE_COMPLEMENTO1_TIPO_CSV">Refer&ecirc;ncia Comp1 -
                                                            tipo</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="REFE_COMPLEMENTO1_TIPO_CSV"
                                                            id="REFE_COMPLEMENTO1_TIPO_CSV" minlength="3"
                                                            maxlength="100" />
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="REFE_COMPLEMENTO1_CSV">Refer&ecirc;ncia Comp
                                                            1</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="REFE_COMPLEMENTO1_CSV" id="REFE_COMPLEMENTO1_CSV"
                                                            minlength="3" maxlength="100" />
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="REFE_COMPLEMENTO2_TIPO_CSV">Refer&ecirc;ncia Comp 2
                                                            - tipo</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="REFE_COMPLEMENTO2_TIPO_CSV"
                                                            id="REFE_COMPLEMENTO2_TIPO_CSV" minlength="3"
                                                            maxlength="100" />
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="REFE_COMPLEMENTO2_CSV">Refer&ecirc;ncia Comp
                                                            2</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="REFE_COMPLEMENTO2_CSV" id="REFE_COMPLEMENTO2_CSV"
                                                            minlength="3" maxlength="100" />
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="REFE_COMPLEMENTO3_TIPO_CSV">Refer&ecirc;ncia Comp 3
                                                            - tipo</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="REFE_COMPLEMENTO3_TIPO_CSV"
                                                            id="REFE_COMPLEMENTO3_TIPO_CSV" minlength="3"
                                                            maxlength="100" />
                                                    </div>

                                                    <div class="col-xl-3">
                                                        <label for="REFE_COMPLEMENTO3_CSV">Refer&ecirc;ncia Comp
                                                            3</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="REFE_COMPLEMENTO3_CSV" id="REFE_COMPLEMENTO3_CSV"
                                                            minlength="3" maxlength="100" />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="padding: 0.2rem 1.25rem">
                                        <h3 class="card-title">
                                            <i class="fas fa-edit"></i>
                                            Dados Pagamento
                                        </h3>
                                    </div>
                                    <div class="card-body" style="padding: 0.2rem 1.25rem">
                                        <div class="tab-content" id="custom-content-above-tabContent">
                                            <div class="tab-pane panel-primary fade show active"
                                                id="custom-content-above-home" role="tabpanel"
                                                aria-labelledby="custom-content-above-home-tab">
                                                <div class="row">

                                                    <div class="col-xl-2">
                                                        <label for="FORMA_PAGAMENTO_CSV">Forma Pagamento</label>
                                                        <?php echo $forma_pagamento; ?>
                                                    </div>

                                                    <div class="col-xl-1">
                                                        <label for="VENCIMENTO_CSV">Vencimento</label>
                                                        <?php echo $vencimentos; ?>
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="PAG_CONTA_ONLINE_CSV">Conta online</label>
                                                        <select class="form-control form-control-sm"
                                                            id="PAG_CONTA_ONLINE_CSV" required
                                                            name="PAG_CONTA_ONLINE_CSV">
                                                            <option value="" selected>Selecione...</option>
                                                            <option value="S">SIM</option>
                                                            <option value="N">N&Atilde;O</option>
                                                        </select>
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="PAG_BANCO_CSV">Banco</label>
                                                        <?php echo $bancos; ?>
                                                    </div>

                                                    <div class="col-xl-1">
                                                        <label for="PAG_AGENCIA_CSV">AG</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="PAG_AGENCIA_CSV" id="PAG_AGENCIA_CSV" minlength="3"
                                                            maxlength="4" />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="PAG_CONTA_CSV">Conta</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="PAG_CONTA_CSV" id="PAG_CONTA_CSV" minlength="4"
                                                            maxlength="11" />
                                                    </div>

                                                    <div class="col-xl-1">
                                                        <label for="PAG_AGENCIA_DIGITO_CSV">Digito</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="PAG_AGENCIA_DIGITO_CSV" id="PAG_AGENCIA_DIGITO_CSV"
                                                            minlength="1" maxlength="1" />
                                                    </div>

                                                    <div class="col-xl-1">
                                                        <label for="PAG_OPERACAO_CSV">Ope</label>
                                                        <input type="text"
                                                            class="form-control form-control-sm text-center"
                                                            name="PAG_OPERACAO_CSV" id="PAG_OPERACAO_CSV" minlength="1"
                                                            maxlength="4" />
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="padding: 0.2rem 1.25rem">
                                        <h3 class="card-title">
                                            <i class="fas fa-edit"></i>
                                            Servi&ccedil;o Contratado
                                        </h3>
                                    </div>
                                    <div class="card-body" style="padding: 0.2rem 1.25rem">
                                        <div class="tab-content" id="custom-content-above-tabContent">
                                            <div class="tab-pane panel-primary fade show active"
                                                id="custom-content-above-home" role="tabpanel"
                                                aria-labelledby="custom-content-above-home-tab">
                                                <div class="row">

                                                    <div class="col-xl-2">
                                                        <label for="COMBO_CONTRATADO_CSV">Combo</label>
                                                        <?php echo $combo_planos; ?>
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="BANDA_LARGA_VELOCIDADE_CSV">Velocidade</label>
                                                        <?php echo $planos_fibra; ?>
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="PLANO_TV_CSV">TV</label>
                                                        <?php echo $planos_tv; ?>
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="FATURAMENTO">Faturamento</label>
                                                        <input type="text" class="form-control form-control-sm"
                                                            name="FATURAMENTO" id="FATURAMENTO" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="padding: 0.2rem 1.25rem">
                                        <h3 class="card-title">
                                            <i class="fas fa-edit"></i>
                                            Agendamentos / Reagendamentos
                                        </h3>
                                    </div>
                                    <div class="card-body" style="padding: 0.2rem 1.25rem">
                                        <div class="tab-content" id="custom-content-above-tabContent">
                                            <div class="tab-pane panel-primary fade show active"
                                                id="custom-content-above-home" role="tabpanel"
                                                aria-labelledby="custom-content-above-home-tab">
                                                <div class="row">
                                                    <!--Agendamento -->
                                                    <div class="col-xl-2">
                                                        <label for="DT_AGENDAMENTO">Agendamento</label>
                                                        <input type="date"
                                                            class="form-control form-control-sm text-center"
                                                            name="DT_AGENDAMENTO" id="DT_AGENDAMENTO" />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="dt_reagendamento_1">Turno Agendamento</label>
                                                        <?php echo $turno; ?>
                                                    </div>
                                                    <!--primeiro reagendamento -->
                                                    <div class="col-xl-2">
                                                        <label for="DT_REAGENDAMENTO_1">Data Reagend 1 </label>
                                                        <input type="date"
                                                            class="form-control form-control-sm text-center"
                                                            name="DT_REAGENDAMENTO_1" id="DT_REAGENDAMENTO_1"
                                                            disabled />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="TURNO_REAGENDAMENTO_1">Turno reagend 1 </label>
                                                        <?php echo $turno1; ?>
                                                    </div>

                                                    <!--segundo reagendamento -->
                                                    <div class="col-xl-2">
                                                        <label for="DT_REAGENDAMENTO_2">Data Reagend 2 </label>
                                                        <input type="date"
                                                            class="form-control form-control-sm text-center"
                                                            name="DT_REAGENDAMENTO_2" id="DT_REAGENDAMENTO_2"
                                                            disabled />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="TURNO_REAGENDAMENTO_2">Turno reagend 2 </label>
                                                        <?php echo $turno2; ?>
                                                    </div>

                                                    <!--terceiro reagendamento -->
                                                    <div class="col-xl-2">
                                                        <label for="DT_REAGENDAMENTO_3">Data Reagend 3 </label>
                                                        <input type="date"
                                                            class="form-control form-control-sm text-center"
                                                            name="DT_REAGENDAMENTO_3" id="DT_REAGENDAMENTO_3"
                                                            disabled />
                                                    </div>

                                                    <div class="col-xl-2">
                                                        <label for="TURNO_REAGENDAMENTO_3">Turno reagend 3 </label>
                                                        <?php echo $turno3; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card card-primary card-outline">
                                    <div class="card-header" style="padding: 0.2rem 1.25rem">
                                        <h3 class="card-title">
                                            <i class="fas fa-edit"></i>
                                            Tratamento
                                        </h3>
                                    </div>
                                    <div class="card-body" style="padding: 0.2rem 1.25rem">
                                        <div class="tab-content" id="custom-content-above-tabContent">
                                            <div class="tab-pane panel-primary fade show active"
                                                id="custom-content-above-home" role="tabpanel"
                                                aria-labelledby="custom-content-above-home-tab">
                                                <div class="row">

                                                    <div class="col-xl-2">
                                                        <label for="STATUS_TRATAMENTO">Status</label>
                                                        <select class="form-control form-control-sm desativado"
                                                            id="STATUS_TRATAMENTO" name="STATUS_TRATAMENTO">
                                                            <option value="" selected>Selecione...</option>
                                                            <option value="T">TRATADO</option>
                                                            <option value="ET">EM TRATAMENTO</option>
                                                            <option value="NT">N&Atilde;O TRATADO</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-xl-2">
                                                        <label for="DT_RETORNO_TRATAMENTO">Retorno Tratamento </label>
                                                        <input type="date"
                                                            class="form-control form-control-sm text-center desativado"
                                                            name="DT_RETORNO_TRATAMENTO" id="DT_RETORNO_TRATAMENTO" />
                                                    </div>



                                                    <div class="col-xl-2">
                                                        <label for="SETOR_RESP_TRATAMENTO">Setor tratamento</label>
                                                        <?php echo $setor_tratamento; ?>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <label for="OBS_TRATAMENTO_BKO">Obs Tratamento BKO</label>
                                                        <textarea class="form-control form-control-sm desativado"
                                                            rows="3" name="OBS_TRATAMENTO_BKO" id='OBS_TRATAMENTO_BKO'
                                                            maxlength="1000" style="resize: none;"></textarea>
                                                        <div id="the-count">
                                                            <span id="current_bko">0</span>
                                                            <span id="maximum">/ 1000</span>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label for="OBS_TRATAMENTO_SUPERVISOR">Obs Tratamento
                                                            Supervisor</label>
                                                        <textarea class="form-control form-control-sm desativado"
                                                            rows="3" name="OBS_TRATAMENTO_SUPERVISOR"
                                                            id='OBS_TRATAMENTO_SUPERVISOR' maxlength="1000"
                                                            style="resize: none;"></textarea>
                                                        <div id="the-count">
                                                            <span id="current_supervisor">0</span>
                                                            <span id="maximum">/ 1000</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- /.card -->
                                </div>
                                <?php echo ns_BtnFormulario(); ?>
                            </div>

                            <div class="row">
                                <div class="col-md-10">
                                    <?php echo ns_BtNovo(); ?>
                                </div>
                            </div>

                            <table class="table table-bordered dataTable table-sm" id="tableBasicas">

                                <thead>
                                    <tr>
                                        <th>Ficha</th>
                                        <th class="text-center" data-toggle="tooltip" data-placement="top"
                                            title="Auditoria">Aud</th>
                                        <!--<th class="text-center" data-toggle="tooltip" data-placement="top"
                                            title="Agendamento">Ag</th>
                                        <th class="text-center" data-toggle="tooltip" data-placement="top"
                                            title="Reagendamento 1">R1</th>
                                        <th class="text-center" data-toggle="tooltip" data-placement="top"
                                            title="Reagendamento 2">R2</th>
                                        <th class="text-center" data-toggle="tooltip" data-placement="top"
                                            title="Reagendamento 3">R3</th> -->

                                        <th class="text-center" data-toggle="tooltip" data-placement="top"
                                            title="Agendamento">Ag</th>
                                        <th class="text-center" data-toggle="tooltip" data-placement="top"
                                            title="Reagendamento 1">R1</th>
                                        <th class="text-center" data-toggle="tooltip" data-placement="top"
                                            title="Reagendamento 2">R2</th>
                                        <th class="text-center" data-toggle="tooltip" data-placement="top"
                                            title="Reagendamento 3">R3</th>

                                        <th>BOV</th>
                                        <th>Ativa&ccedil;&atilde;o</th>
                                        <th>Venda</th>

                                        <th>Nº OS</th>
                                        <th>CPF/CNPJ</th>
                                        <th>CLIENTE</th>

                                    </tr>
                                </thead>
                            </table>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
</div>

<div class="modal fade" id="modal-exportar_ven">
    <form id="form_expo_vendas">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Per&iacute;odo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <div class="col-xl-6">
                            <label for="expo_per_ini">Inicial</label>
                            <input type="date" class="form-control form-control-sm text-center" name="expo_per_ini"
                                id="expo_per_ini" required />
                        </div>
                        <div class="col-xl-6">
                            <label for="expo_per_fim">Final</label>
                            <input type="date" class="form-control form-control-sm text-center" name="expo_per_fim"
                                id="expo_per_fim" required />
                        </div>

                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btn_expo_vendas">Confirmar</button>
                </div>
    </form>
</div>
<!-- /.modal-content -->
</div>
<!-- /.modal-dialog -->
</div>