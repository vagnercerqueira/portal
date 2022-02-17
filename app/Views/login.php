<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?php echo NOME_SISTEMA; ?></title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="shortcut icon" href="<?php echo base_url('assets/img/empresa/' . LOGO_SISTEMA); ?>">
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
      <!-- <img class="img-circle img-thumbnail img-fluid" style="max-width: 70%" src="<?php echo base_url('assets/img/empresa/' . NOME_SISTEMA . '.png'); ?>"> -->
      <b><?php echo NOME_SISTEMA; ?></b>
    </div>
    <!-- /.login-logo -->
    <div class="card">
      <div class="card-body login-card-body">
        <p class="login-box-msg">Informe sua credencial</p>
        <?php if (session()->get('error')) : ?>
          <div class="alert alert-danger" role="alert" style="padding: 0;border:0px;">
            <span style="padding:2px;"><?= session()->get('error');
                                        $usuario = session()->get('usuario');
                                        $senha = session()->get('senha');
                                        $cliente_db = session()->get('cliente_db');

                                        ?></span>
          </div>
        <?php else :
          $usuario = "";
          $senha = "";
          $cliente_db = "";
        endif; ?>
        <form action="<?php echo base_url('/login'); ?>" method="post">
          <div class="input-group mb-3">
            <input type="text" name="usuario" autocomplete="off" autofocus maxlength="20" value="<?php echo $usuario; ?>" class="form-control" required placeholder="Usuario">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" name="senha" autocomplete="off" maxlength="30" value="<?php echo $senha; ?>" class="form-control" required placeholder="Senha">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="text" name="cliente_db" autocomplete="off" maxlength="15" value="<?php echo $cliente_db; ?>" class="form-control" required placeholder="Codigo">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fa fa-key"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-8">
              <!-- <div class="icheck-primary">
              <a href="pages/examples/forgot-password.html">Esqueci a senha</a>
              <br>
              <a href="pages/examples/forgot-password.html">Solicitar registro</a>
            </div> -->
            </div>
            <!-- /.col -->
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Entrar</button>
            </div>

            <!-- /.col -->
          </div>
        </form>
        <p class="mt-3 mb-1">
          <a href="<?php echo base_url('/login/request_new_pass_viaEmail'); ?>">Esqueci a senha</a>
        </p>
        <!--<p class="mb-0">
          <a href="<?php echo base_url('/login/request_new_register'); ?>" class="text-center">Solicitar registro</a>
        </p>-->

        <div class="social-auth-links text-center mb-3">
          <!-- <p>- Ou -</p>
         <a href="#" class="btn btn-block btn-primary">
          <i class="fab fa-facebook mr-2"></i> Sign in using Facebook
        </a> 
        <a href="#" class="btn btn-block btn-danger">
          <i class="fab fa-user mr-2"></i> Solicitar credencial
        </a> -->
        </div>
        <!-- /.social-auth-links -->
        <!-- <p class="mb-1">
        <a href="forgot-password.html">Caso não tenha credencial, solicite ao administrador</a>
      </p>
      <p class="mb-0">
        <a href="register.html" class="text-center">Solicitar credencial</a>
      </p> -->
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

</body>

</html>