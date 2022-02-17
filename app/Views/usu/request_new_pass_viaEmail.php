<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo NOME_SISTEMA; ?></title>
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

      <!-- <img src="<?php echo base_url('assets/img/arkivar_login.png'); ?>"> -->

      <b><?php echo NOME_SISTEMA; ?></b>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Informe o email do usuário</p>

        <form id="formemail" action="<?php echo base_url(); ?>" onsubmit="return envia_email(this);">
          <div class="input-group mb-3">
            <input type="email" class="form-control" name="email" id="email" placeholder="Email" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Recuperar senha</button>
            </div>
            <!-- /.col -->
          </div>
        </form>

        <p class="mt-3 mb-1">
          <a href="<?php echo base_url(); ?>">Login</a>
        </p>
        <!--  <p class="mb-0">
        <a href="<?php echo base_url('/login/request_new_register'); ?>" class="text-center">Solicitar registro</a>
      </p>-->
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
          <p class='resposta_sucesso'>Foi enviado uma senha para seu Email</p>
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
          <p class='resposta_erro'></p>
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
    function envia_email(f) {
      fetch('<?php echo base_url('login/send_pass') ?>', {
          method: 'POST',
          body: new FormData(document.querySelector('#formemail')),
          mode: 'cors',
        })
        .then(function(response) {
          return response.json();
        })
        .then(function(data) {
          if (data.sucesso) {
            $("#modal-sucesso").modal("show");
          } else {
            $("#modal-error").modal("show");
            document.querySelector(".resposta_erro").innerHTML = data.msg;
          }
        })
        .catch(function(err) {
          alert('Erro do servidor: verifique tb o se .env===> '.err);
        });
      return false;
    }
  </script>
</body>

</html>