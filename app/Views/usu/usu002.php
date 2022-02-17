<style>
	ul,
	#myUL {
		list-style-type: none;
	}

	#myUL {
		margin: 0;
		padding: 0;
	}

	.caret {
		cursor: pointer;
		-webkit-user-select: none;
		/* Safari 3.1+ */
		-moz-user-select: none;
		/* Firefox 2+ */
		-ms-user-select: none;
		/* IE 10+ */
		user-select: none;
	}

	.caret::before {
		content: "\2296";
		color: black;
		display: inline-block;
		color: red;
	}

	.caret-down::before {
		/*-ms-transform: rotate(90deg); /* IE 9 */
		/* -webkit-transform: rotate(90deg); /* Safari */
		/* transform: rotate(90deg);  */
		content: "\2A01";
	}

	.nested {
		display: none;

	}

	.active {
		display: block;
	}

	#div_arvore {
		background-color: #f3fcfc;
		padding: 5px;
		overflow-y: auto;
		max-height: 200px;
	}
</style>
<form id="form_aplicacoes" autocomplete="off">
	<div class="form_crud_modal">
		<input type="hidden" name="IDS_PAIS" id="IDS_PAIS" />
		<input type="hidden" name="ID" id="ID" />
		<div class="card">
			<div class="card-header">
				<h5 class="card-title text-info ">Cadastrar Programas</h5>
			</div>
			<!-- /.box-header -->
			<div class="card-body">
				<div class="row">
					<div class="col-xl-3">
						<label for="NOME">Nome</label>
						<input type="text" maxlength="50" class="form-control form-control-sm" placeholder="Nome" required id="NOME" name="NOME" />
					</div>
					<div class="col-xl-2">
						<label for="CAMINHO">Caminho</label>
						<?php echo $caminho; ?>
					</div>
					<div class="col-xl-4">
						<label>Local</label>
						<div id="div_arvore">
							<?php echo $diretorio; ?>
						</div>
					</div>
					<div class="col-xl-1">
						<label for="ORDEM">Ordem</label>
						<input type="number" maxlength="3" min=1 class="form-control form-control-sm text-center" placeholder="Ordem" id="ORDEM" name="ORDEM" />
					</div>
					<div class="col-xl-2">
						<label for="ICONE">Icone</label>
						<input type="text" maxlength="20" class="form-control form-control-sm" placeholder="Icone" id="ICONE" name="ICONE" />
					</div>
				</div>
			</div>
		</div>
		<?php echo ns_BtnFormulario(); ?>
	</div>
	<?php echo ns_BtNovo(); ?>
	<table class="table table-bordered table-sm" id="tableAplicacoes">
		<thead>
			<tr>
				<th>NOME</th>
				<th>PAI</th>
				<th>CAMINHO</th>
				<th>ICONE</th>
				<th>ORDEM</th>
				<th>AÇAO</th>
			</tr>
		</thead>
	</table>
</form>