	<style>
		.cores_nav {
			width: 40px;
			height: 20px;
			border-radius: 25px;
			margin-right: 10px;
			margin-bottom: 10px;
			opacity: 0.8;
			cursor: pointer;
		}
	</style>
	<form id="form_parger" autocomplete="off" enctype="multipart/form-data">
		<div class="form_crud_modal">
			<input type="hidden" name="ID" id="ID" />
			<div class="row">
				<div class="col-xl-3">
					<label for="EMAIL_SUPORTE">Email suporte</label>
					<input type="email" maxlength="100" required class="form-control form-control-sm center" placeholder="Email" id="EMAIL_SUPORTE" name="EMAIL_SUPORTE" />
				</div>
				<div class="col-xl-2">
					<label for="ENVIA_EMAIL_USUARIO">Envia Email Usuario</label>
					<div>
						<input type="radio" id="ENVIA_EMAIL_USUARIO" name="ENVIA_EMAIL_USUARIO" value='S' /> Sim
						<input type="radio" id="ENVIA_EMAIL_USUARIO" name="ENVIA_EMAIL_USUARIO" value='N' checked /> Nao
					</div>
				</div>
				<div class="col-xl-2">
					<label for="BLOQUEIA_SISTEMA">Bloqueia Sistema</label>
					<select class="form-control form-control-sm center">
						<option value="">Selecione o motivo...</option>
						<option value="1">Fatura em aberto</option>
						<option value="2">Prazo expirado</option>
					</select>
				</div>				
			</div>			

			<?php echo ns_BtnFormulario(); ?>
		</div>
		<?php echo ns_BtNovo(); ?>
		<table class="table table-bordered table-sm" id="table_empresa">
			<thead>
				<tr>
					<th>Email</th>
					<th>Acao</th>
				</tr>
			</thead>
		</table>
	</form>