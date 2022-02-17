<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>arkivar | Sistema de armazenamento e gerenciamento de documentos</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="<?php echo base_url('assets/img/empresa/'. NOME_SISTEMA .'.png'); ?>">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/adminlte504/plugins/fontawesome-free/css/all.min.css'); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/ionicons.min.css'); ?>">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/adminlte504/dist/css/adminlte.min.css'); ?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>

<body class="hold-transition login-page">

<div class="login-box">
  <div class="login-logo">

  <!--	<img src="<?php echo base_url('assets/img/arkivar_login.png'); ?>"> -->

    <b><?php echo NOME_SISTEMA; ?></b>
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Registro de novo usuário</p>

      <form id="formrequestregister" action="<?php echo base_url(); ?>" onsubmit="return solicitar_registro(this);" autocomplte="off">
        <div class="input-group mb-3">
          <input type="text" maxlength="50" class="form-control" name="nome" id="nome" placeholder="Nome Completo" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="email" class="form-control" name="email" id="email" placeholder="Email" required maxlength="100">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" maxlength="10" name="senha" id="senha" placeholder="Senha" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>        
        <div class="input-group mb-3">
          <input type="text" class="form-control" maxlength="10" name="confirma_senha" id="confirma_senha" placeholder="Confirma Senha" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>               
        <div class="row">
          <div class="col-12">
            <button type="submit" class="btn btn-primary btn-block">Solicitar Registro</button>
          </div>
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="<?php echo base_url(); ?>">Login</a>
      </p>
      <p class="mt-3 mb-1">
          <a href="<?php echo base_url('/login/request_new_pass_viaEmail'); ?>">Esqueci a senha</a>
        </p>      
    </div>
    <!-- /.login-card-body -->
  </div>
</div>

  <div class="modal fade" id="modal-sucesso" data-backdrop="static">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header bg-primary">
          <h4 class="modal-title">Email enviado</h4>
          <a href="<?php echo base_url(); ?>" class="btn btn-primary"> <span aria-hidden="true">&times;</span></a>
        </div>
        <div class="modal-body">
          <p id="resposta_sucesso">Foi enviado uma senha para seu Email</p>
        </div>
        <div class="modal-footer justify-content-between">
          <a href="<?php echo base_url(); ?>" class="btn btn-primary">Login</a>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <div class="modal fade" id="modal-error" data-backdrop="static">
    <div class="modal-dialog  modal-sm">
      <div class="modal-content">
        <div class="modal-header  bg-danger">
          <h4 class="modal-title">Erro no envio do Email</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p id="resposta_erro"></p>
        </div>
        <div class="modal-footer justify-content-between">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
          <a href="<?php echo base_url(); ?>" class="btn btn-primary">Login</a>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
  <!-- /.modal -->

  <!-- jQuery -->
  <script src="<?php echo base_url('assets/adminlte504/plugins/jquery/jquery.min.js'); ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo base_url('assets/adminlte504/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url('assets/adminlte504/dist/js/adminlte.min.js'); ?>"></script>
  <script>
     function solicitar_registro(f){
      
      if( ( document.getElementById("nome").value).trim() == "" || ( (document.getElementById("senha").value).trim() !==  (document.getElementById("confirma_senha").value).trim() ) ) {
            $("#modal-error").modal("show");
             document.querySelector("#resposta_erro").innerHTML = "Verifique campos obrigatorios e senhas igual confirma senha";
      }else{      
        fetch('<?php echo base_url('login/send_request_register') ?>', { 
            method: 'POST',
            body: new FormData(document.querySelector('#formrequestregister')),
            mode: 'cors',     
          })
          .then(function (response) {
            return response.json();
          })
          .then(function (data) {
              if(data.sucesso){                
                  $("#modal-sucesso").modal("show");
                  document.querySelector("#resposta_sucesso").innerHTML = data.msg;
              }else{
                  $("#modal-error").modal("show");
                  document.querySelector("#resposta_erro").innerHTML = data.msg;
              }
          })
          .catch(function (err) {
              alert('Erro do servidor: verifique tb o se .env===> '+err);
          });
      }
        return false;
     }
</script>
</body>

</html>