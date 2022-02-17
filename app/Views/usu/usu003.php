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
		max-height: 90%;
	}

	.copia_grupo {
		display: none;
	}
</style>
<form id="form_aplicacoes" tbMod="arkivar_brum_aplicacoes" autocomplete="off">
	<div class="row">
		<div class="col-xl-3">
			<div class="row">
				<div class="col-xl-12">
					<label for="ID_GRUPO">Grupo</label>
					<?php echo $grupos; ?>
				</div>
				<div class="col-xl-12">&nbsp;</div>
				<div class="col-xl-12">&nbsp;</div>
				<!--	<div class="col-xl-12 copia_grupo">
					<label for="ID_GRUPO_CP">Copiar Permissao do Grupo</label>
					<?php echo $grupos_cp; ?>
				</div>
				<div class="col-xl-12 copia_grupo">
					<button type="button" class="btn btn-primary btn-block btn_cp_permissao">Confirmar</button>
				</div> -->
			</div>
		</div>
		<div class="col-xl-5">
			<label>Local</label>
			<div id="div_arvore" style="display: none">
				<?php echo $diretorio; ?>
			</div>
		</div>
	</div>
</form>