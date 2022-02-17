<form id="form_basicos" autocomplete="off">
	<div class="form_crud">
		<input type="hidden" name="ID" id="ID" />
		<div class="row">
			<div class="col-xl-4">
				<label for="DESCRICAO">Descrição</label>
				<input type="text" maxlength="100" class="form-control form-control-sm" placeholder="Descrição" required id="DESCRICAO" name="DESCRICAO" />
			</div>
			<?php
			if (count($extra) > 0) :
				foreach ($extra as $k => $v) :
			?>
					<div class="col-xl-2">
						<?php echo $v; ?>
					</div>
			<?php
				endforeach;
			endif;
			?>
		</div>
		<br>
		<?php echo ns_BtnFormulario(); ?>
	</div>
	<?php echo ns_BtNovo(); ?>
	<br>
	<div class="row">
		<div class="col-md-12">
			<?php echo $table; ?>
		</div>
	</div>
</form>