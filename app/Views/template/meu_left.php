<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed">
  <!-- Site wrapper -->
  <div class="wrapper">
    <!-- Navbar -->
    <nav class=" main-header navbar navbar-expand <?php echo COR_NAVBAR; ?>">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
        </li>
      </ul>
	  
	<?php if(session()->get('formsearch') == 'S'): ?>
	<form class="form-inline ml-0 ml-md-3" onsubmit="return formSearchGeral(this); return false;" action="" method="get" data-pag='<?php echo base_url("home/" .  session()->get('home')."/formsearchgeral"); ?>' autocomplete="off">
		<div class="input-group input-group-sm">
			<input class="form-control form-control-navbar" minlength="3" required name="inputformsearchgeral" type="search" placeholder="Pesquisar" aria-label="Search">
			<div class="input-group-append" >
				<button class="btn btn-navbar" type="submit">
					<i class="fas fa-search"></i>
				</button>
			</div>
		</div>
	</form>
	<?php endif; ?>
	
      <!-- Right navbar links -->
      <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" style="color: floralwhite;">
							<i class="fas fa-user"></i>
							<span class="badge badge-danger navbar-badge"></span>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">						
							<a href="javascript:;" class="dropdown-item alterar_senha">
								<i class="fas fa-key mr-2"></i> Alterar Senha
							</a>
						</div>	
					</li>
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#" style="color: floralwhite;">
							<i class="far fa-bell"></i>
							<span class="badge badge-warning navbar-badge"></span>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<span class="dropdown-header">Notificacoes</span>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								<i class="fas fa-envelope mr-2"></i> Aviso do suporte
								
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								<i class="fas fa-users mr-2"></i> Pedido de registro
								
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item">
								<i class="fas fa-file mr-2"></i> Boeleto disponivel
								
							</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item dropdown-footer">Todos</a>
						</div>
					</li>
					<li class="nav-item">
						<a class="nav-link" style="color: floralwhite;" href="<?php echo base_url('login/logout'); ?>" role="button">
							<i class="fas fa-sign-out-alt mr-2"></i></a>
					</li>
					<!--	<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
							<i class="fa fa-cogs"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
							<a href="javascript:;" class="dropdown-item alterar_senha">
								<i class="fas fa-key mr-2"></i> Alterar Senha
							</a>
						</div>
					</li>-->
				</ul>
	  
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <!--<a href="javascript:;" class="brand-link">
        <img src="<?php echo base_url('assets/img/empresa/' . LOGO_SISTEMA); ?>" alt="<?php echo  NOME_SISTEMA; ?>" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light"><?php echo  NOME_SISTEMA; ?></span>
      </a>-->

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
          <div class="image">
            <img src="<?php $img = session()->get('foto') == "" ? "user.png" : session()->get('foto');
                      echo base_url('assets/img/usuarios/' . $img); ?>" class="img-circle elevation-2" alt="<?php echo $img; ?>">
          </div>
          <div class="info">
            <a href="javascript:;" class="d-block"><?php echo session()->get('nome'); ?></a>
          </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
			<ul class="nav nav-pills nav-sidebar flex-column text-sm" data-widget="treeview" role="menu" data-accordion="false">
				<!-- Add icons to the links using the .nav-icon class
				   with font-awesome or any other icon font library -->
				<li class="nav-item">
				  <a href="<?php echo base_url("home/" . session()->get('home')); ?>" class="nav-link">
					<i class="nav-icon fas fa-tachometer-alt"></i>
					<p>
					  Home
					</p>
				  </a>
				</li>
            <?php echo mapa_diretorio(session()->get('appUser'), 'L'); ?>
          </ul>
	  
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="titulo_pag"><?php echo $titulo; ?></h1>
            </div>
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					
					<?php $ses = session()->get();  if( $ses['IndPag'] === null): ?>
						<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
					<?php else: ?>
					<li class="breadcrumb-item"><a href="javascript:;"><?php echo $ses['appUser'][$ses['appUser'][$ses['IndPag']]['id_pai']]['nome']; ?></a></li>
					<li class="breadcrumb-item active"><?php echo $ses['appUser'][$ses['IndPag']]['nome']; ?></li>
					<?php endif; ?>
				</ol>
			</div>
          </div>
        </div><!-- /.container-fluid -->
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">