<body class="layout-top-nav">
	<div class="wrapper">

		<!-- Navbar -->
		<nav class="main-header navbar navbar-expand-md <?php echo COR_NAVBAR; ?>">
			<div class="container-fluid">
				<a href="javascript:;" class="navbar-brand">
					<img src="<?php echo base_url('assets/img/empresa/' .  LOGO_SISTEMA); ?>" alt="<?php echo  NOME_SISTEMA; ?>" class="brand-image elevation-2">
					<!-- <span class="brand-text font-weight-light"><?php echo  session()->get('nome_empresa'); ?></span> -->
				</a>
				<button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<div class="collapse navbar-collapse order-3" id="navbarCollapse">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a href="<?php echo base_url("home/" .  session()->get('home')); ?>" class="nav-link" style="color: floralwhite;">Home</a>
						</li>
						<?php echo mapa_diretorio(session()->get('appUser'), BARRA_NAVEGACAO); ?>
					</ul>
					<?php if (session()->get('formsearch') == 'S') : ?>
						<form class="form-inline ml-0 ml-md-3" onsubmit="return formSearchGeral(this); return false;" action="" method="get" data-pag='<?php echo base_url("home/" .  session()->get('home') . "/formsearchgeral"); ?>' autocomplete="off">
							<div class="input-group input-group-sm">
								<input class="form-control form-control-navbar" minlength="3" required name="inputformsearchgeral" type="search" placeholder="Consulta rapida" aria-label="Search">
								<div class="input-group-append">
									<button class="btn btn-navbar" type="submit">
										<i class="fas fa-search"></i>
									</button>
								</div>
							</div>
						</form>
					<?php endif; ?>
				</div>
				<!-- Right navbar links -->
				<ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">
					<li class="nav-item dropdown">
						<a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" style="color: floralwhite;">
							<i class="fas fa-user"></i>
							<span class="badge badge-danger navbar-badge"></span>
						</a>
						<!--	<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
							<a href="javascript:;" class="dropdown-item alterar_senha">
								<i class="fas fa-key mr-2"></i> Alterar Senha 22
							</a>
						</div> -->

						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" >
							<span class="dropdown-header" title="<?php echo strtoupper(session()->get('nome_grupo')); ?>"><?php echo strtoupper(session()->get('cliente_db')); ?> - <?php echo strtoupper(session()->get('usuario')); ?></span>
							<div class="dropdown-divider"></div>
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
							<b>sair</b> <i class="fas fa-sign-out-alt mr-2"></i></a>
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
			</div>
		</nav>
		<!-- /.navbar -->

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="titulo_pag m-0 text-dark"><?php echo $titulo; ?></h1>
						</div>
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">

								<?php $ses = session()->get();
								if ($ses['IndPag'] === null) : ?>
									<li class="breadcrumb-item"><a href="javascript:;">Home</a></li>
								<?php else : ?>
									<li class="breadcrumb-item"><a href="javascript:;"><?php echo $ses['appUser'][$ses['appUser'][$ses['IndPag']]['id_pai']]['nome']; ?></a></li>
									<li class="breadcrumb-item active"><?php echo $ses['appUser'][$ses['IndPag']]['nome']; ?></li>
								<?php endif; ?>
							</ol>
						</div>
					</div>
				</div><!-- /.container-fluid -->
			</div>

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">