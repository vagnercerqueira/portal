<style>
th,
td {
    font-size: 12px;
}
</style>
<section class="content">
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

        <div class="row">
            <div class="alert alert-warning" role="alert">
                Atualizações -
                Vendas: <span id="ultima_venda">01/01/2000</span>
                BOV:<span id="ultima_bov">01/01/2000</span>
            </div>
        </div>
        <form method="post" id="form_busca_mes">
            <div class="row mb-4">
                <div class="col-10">

                </div>
                <div class="col-2 float-right">
                    <span class="float-right">
                        <input type="month" class="form-control form-control-sm text-center" name="COMPETENCIA"
                            id="COMPETENCIA" value="<?php echo date("Y-m") ?>" />
                    </span>
                </div>
            </div>
        </form>
        <div class="row">

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3 id="venda_bruta">0</h3>
                        <p>Vendas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-cubes"></i>
                    </div>
                    <a href="#" class="small-box-footer" data-toggle="modal" data-target="#modal-exportar_ven"
                        data-titulo="Vendas Brutas">Detalhes
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3 id="venda_instaladas">0</h3>
                        <p>Vendas instaladas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <a href="#" class="small-box-footer" data-toggle="modal" data-target="#modal-exportar_ven"
                        data-titulo="Vendas Instaladas">Detalhes
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="venda_aprovisionamento">0</h3>

                        <p>Aprovisionamentos</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-calendar-alt"></i>
                    </div>
                    <a href="#" class="small-box-footer" data-toggle="modal" data-target="#modal-exportar_ven"
                        data-titulo="Em Aprovisionamento">Detalhes
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3 id="venda_emtratamento">0</h3>

                        <p>Em tratamento</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <a href="#" class="small-box-footer" data-toggle="modal" data-target="#modal-exportar_ven"
                        data-titulo="Em Tratamento">Detalhes
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="venda_cancelados_bov">0</h3>

                        <p>Cancelados BOV</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-ban"></i>
                    </div>
                    <a href="#" class="small-box-footer" data-toggle="modal" data-target="#modal-exportar_ven"
                        data-titulo="Cancelados BOV">Detalhes
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="vendas_migracoes">0</h3>

                        <p>Migrações VOIP ou FIBRA</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-hand-paper"></i>
                    </div>
                    <a href="#" class="small-box-footer" data-toggle="modal" data-target="#modal-exportar_ven"
                        data-titulo="Migrações VOIP ou FIBRA">Detalhes
                        <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="vendas_dados_incompletos">0</h3>

                        <p>Dados incompletos</p>
                    </div>
                    <div class="icon">
                        <i class="far fa-angry"></i>
                    </div>
                    <a href="#" class="small-box-footer" data-toggle="modal" data-target="#modal-exportar_ven"
                        data-titulo="Dados incompletos">Detalhes

                        <i class=" fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->

            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3 id="vendas_instalacoes_atrasadas">0</h3>

                        <p>Instalações atrasadas</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="#" class="small-box-footer" data-toggle="modal" data-target="#modal-exportar_ven"
                        data-titulo="Instalações atrasadas">Detalhes
                        <i class=" fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
        </div>
    </div>
</section>

<div class="modal fade" id="modal-exportar_ven">
    <form id="form_expo_vendas">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Per&iacute;odo</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <div class="row">
                        <button type="button" class="btn btn-success btn-sm btn_download" onclick="tableToCSV()"><i
                                class="fa fa-file-excel"></i>&nbsp;Exportar</button>
                        <div class="col-12">
                            <div class="table-responsive p-0" style="height: 300px;" id="tabela">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                </div>
    </form>
</div>