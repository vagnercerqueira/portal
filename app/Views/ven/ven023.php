<form method="post" id="form_busca">
    <div class="row">
        <div class="col-12">
            <div class="input-group input-group-sm">
                <input type="text" autocomplete="off" title="minimo de 8 caracteres" class="form-control text-center" required minlength="8" placeholder="Buscar por CEP OU CDO" name='cep_or_cdo'>
                <span class="input-group-append">
                    <button type="submit" class="btn btn-info btn-flat"><i class="fas fa-search"></i></button>
                </span>
            </div>
        </div>
    </div>
    <br>
    <div class="row">
        <div class="col-12">
            <table class="table table-striped table-sm" id="table_dfvs">
                <thead>
                    <tr>
                        <th>UF</th>
                        <th>MUNICIPIO</th>
                        <th>LOGR</th>
                        <th>NUM FACHADA</th>
                        <th>COMPL</th>
                        <th>COMPL2</th>
                        <th>COMPL3</th>
                        <th>CEP</th>
                        <th>BAIRRO</th>
                        <th>VIABILIDADE</th>
                        <th>CDO</th>
                        <th>COD LOGR</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</form>