<style>
 .msg_new_pw {
	 color: red;
 }
</style>

<form id="form_usuarios" autocomplete="off" enctype="multipart/form-data">
	<div class="form_crud_modal">
		<input type="hidden" name="ID" id="ID" />
		<div class="row">
			<div class="col-md-3">
				<label for="NOME">Nome</label>
				<input type="text" id="NOME" title="Nome" name="NOME" class=" form-control-sm form-control" required maxlength="50">
			</div>		
			<div class="col-md-2">
				<label for="USUARIO">Usuario</label>
				<input type="text" minlength="3" maxlength="20" id="USUARIO" name="USUARIO" class=" form-control-sm form-control" maxlength="20" required>
			</div>
			<div class="col-xs-6 col-md-2">
				<label for="">Grupo</label>
				<select id="GRUPO" name="GRUPO" class="form-control form-control-sm" required><?php echo $grupo; ?></select>
			</div>
			<div class="col-xs-6 col-md-2" style="display: none;">
				<label for="EQUIPE_ID">Equipe</label>
				<select id="EQUIPE_ID" name="EQUIPE_ID" class="form-control form-control-sm"><?php echo $equipe; ?></select>
			</div>
			<div class="col-xs-6 col-md-2">
				<label for="STATUS">Status</label>
				<?php echo $status; ?>
			</div>
			<div class="col-md-3 col-sm-12">
				<label for="EMAIL">E-mail</label>
				<input type="email" id="EMAIL" name="EMAIL" required class="form-control form-control-sm" required maxlength="50" required>
			</div>
			<div class="col-lg-4 col-xs-12 col-md-12">
				<label for="imagem">Foto</label>
				<!--	<input type="file" accept="image/*" id="DOC" class="form-control form-control-sm" name="DOC"> -->
				<div class="custom-file">
					<input type="file" id="DOC" accept=".png, .jpg, .JPEG, .ico, .pdf" class="custom-file-input form-control-sm" name="DOC" />
					<label class="custom-file-label" for="customFile"></label>
				</div>
			</div>
			<div class="col-lg-12 col-time-line" style="display: none;">
				<div class="timeline">
					<div class="time-label">
						<span class="bg-green">3 Jan. 2014</span>
					</div>
					<!-- /.timeline-label -->
					<!-- timeline item -->
					<div>
						<i class="fa fa-camera bg-purple"></i>
						<div class="timeline-item">
							<span class="time"><i class="fas fa-clock"></i> 2 days ago</span>
							<h3 class="timeline-header"><a href="#">Mina Lee</a> uploaded new photos</h3>
							<div class="timeline-body">
								<!--<img src="http://placehold.it/150x100" alt="...">
								<img src="http://placehold.it/150x100" alt="...">
								<img src="http://placehold.it/150x100" alt="...">
								<img src="http://placehold.it/150x100" alt="...">
								<img src="http://placehold.it/150x100" alt="...">-->
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-md-6">
				<label for="">&nbsp;</label>
				<div class='msg_new_pw'><small>Ao cadastrar usuario, a senha sera gerada automatica: usuario@1234      |       Ex.: joao@1234</small></div>
			</div>
		</div>
		<?php echo ns_BtnFormulario(); ?>
	</div>
	<?php echo ns_BtNovo(); ?>
	<table class="table table-bordered dataTable table-sm" id="tableBasicas">
		<thead>
			<tr>
				<th>Nome</th>
				<th>Usuario</th>
				<th>Status</th>
				<th>Grupo</th>
				<th>Equipe</th>
				<th>Foto</th>
				<th class="text-center">A&ccedil;&atilde;o</th>
			</tr>
		</thead>
	</table>
</form>

<!-- Modal -->
<div class="modal fade" id="modal_reset_senha" data-backdrop="static">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">

			<!-- Modal Header -->
			<div class="modal-header">
				<h4 class="modal-title">Resetar Senha</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>

			<!-- Modal body -->
			<div class="modal-body">
				Confirmar o reset de senha ?
				<div class='msg_new_pw'><small>Ao confirmar resetar senha, a senha sera gerada automatica: usuario@1234      |       Ex.: joao@1234</small></div>
			</div>

			<!-- Modal footer -->
			<div class="modal-footer">
				<button type="button" id="btn_conf_senha" onclick="conf_resetar_senha(this)" class="btn btn-success">Confirmar</button>&nbsp;
				<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
			</div>

		</div>
	</div>
</div>