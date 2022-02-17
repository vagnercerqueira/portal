<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo $ddEmp['nome_empresa']; ?></title>
  <link rel="shortcut icon" href="<?php echo base_url('assets/img/logo_arkivar.ico'); ?>">
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('assets/adminlte504/plugins/fontawesome-free/css/all.min.css'); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="<?php echo base_url('assets/css/ionicons.min.css'); ?>">

  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('assets/adminlte504/dist/css/adminlte.min.css'); ?>">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script>
    base_url = "<?php echo base_url(); ?>";
  </script>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="javascript:;"><b><?php echo $ddEmp['nome_empresa']; ?></b></a>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg text-danger">Esqueceu sua senha ?</p>

        <form id="newpass">
          <input type="hidden" id="moment" name="moment" value="<?php echo $moment; ?>" />
          <input type="hidden" id="pwt" name="pwt" value="<?php echo $pwt; ?>" />
          <div class="input-group mb-3">
            <input type="password" required class="form-control" id="senha" name="senha" placeholder="Senha">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" required class="form-control" id="confirma_senha" name="confirma_senha" placeholder="Confirma Senha">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Mudar Senha</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
        <p class="mt-3 mb-1" style="display: none;" id='merror'></p>
        <p class="mt-3 mb-1">
          <a href="<?php echo base_url(); ?>">Login</a>
        </p>

      </div>
      <!-- /.login-card-body -->
    </div>
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="<?php echo base_url('assets/adminlte504/plugins/jquery/jquery.min.js'); ?>"></script>
  <!-- Bootstrap 4 -->
  <script src="<?php echo base_url('assets/adminlte504/plugins/bootstrap/js/bootstrap.bundle.min.js'); ?>"></script>
  <!-- AdminLTE App -->
  <script src="<?php echo base_url('assets/adminlte504/dist/js/adminlte.min.js'); ?>"></script>
  <script src="<?php echo base_url('assets/js/app/alterasenhaemail.js'); ?>"></script>

</body>

</html>