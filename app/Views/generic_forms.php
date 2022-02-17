<form id="form_generic" autocomplete="off">
	<div class="form_crud_modal">
		<div class="row">
			<input type="hidden" name="ID" id="ID" />

			<?php
			if (isset($fields)) :
				$formFields = null;
				foreach ($fields as $f => $af) {
					$formFields .= "<div class='col-md-" . (array_key_exists('div_size', $af) ? $af['div_size'] : '2') . "'>";
					if (array_key_exists('label', $af)) $formFields .= "<label>" . (str_replace('_', ' ', $af['label'])) . "</label>";
					$formFields .= "<" . $af['tag'] . " ";

					foreach ($af['attrs'] as $n => $v) $formFields .= $n . "='" . $v . "'";
					$formFields .= " >";
					if ($af['tag'] == 'select') {
						foreach ($af['options'] as $n => $v) $formFields .= "<option value=" . $n . ">" . $v . "</option>";
						$formFields .= '</select> ';
					}
					$formFields .= "</div>";
				}
				echo $formFields;
			endif;
			?>
		</div>
		<?php echo ns_BtnFormulario(); ?>
	</div>

	<?php if (!isset($btn_novo) || $btn_novo !== false) echo ns_BtNovo(); ?>
	<?php if (isset($tb_colunas)) : ?>
		<table class="table table-bordered dataTable table-sm" id="tableBasicas">
			<thead>
				<tr>
					<?php foreach ($tb_colunas as $k => $v) : ?>
						<th><?php echo (str_replace('_', ' ', $v)); ?></th>
					<?php endforeach; ?>
				</tr>
			</thead>
		</table>
	<?php endif; ?>
</form>