<form id="form_mensagens" autocomplete="off">

    <div class="form_crud_modal">
        <input type="hidden" name="ID" id="ID" />
        <div class="row">
            <div class="col-md-12">
                <div class="row">				
                    <div class="col-md-10">					
						<div class="row">
							 <div class="col-md-12">
								<label for="ZAP_AGENDAMENTO1">Agendamento</label>
								<textarea class="form-control form-control-sm" rows="3" name="ZAP_AGENDAMENTO1" id='ZAP_AGENDAMENTO1' maxlength="500" style="resize: none;"></textarea>
								<div id="the-count">
									<span id="current_agendamento">0</span>
									<span id="maximum">/ 500</span>
								</div>
							</div>
							<div class="col-md-12">	
								<label for="ZAP_REAGENDAMENTO_1">Reagendamento</label>
								<textarea class="form-control form-control-sm" rows="3" name="ZAP_REAGENDAMENTO_1" id='ZAP_REAGENDAMENTO_1' maxlength="500" style="resize: none;"></textarea>
								<div id="the-count">
									<span id="current_reagendamento1">0</span>
									<span id="maximum">/ 500</span>
								</div>					
							</div>
						</div>
                    </div>
					<div class="col-md-2">
						<label>&nbsp;</label>
						<ul class="list-group">
							<?php foreach ($option_lista_campos as $k => $v) : ?>
								<li class="list-group-item" style="cursor:pointer;padding-top: 0.25rem;padding-right: 1.25rem;padding-bottom: 0.25rem;padding-left: 1.25rem;" data-campo='<?php echo $k; ?>'><?php echo $v; ?></li>
							<?php endforeach; ?>
						</ul>
					</div>	
                </div>
            </div>
        </div>
        <?php echo ns_BtnFormulario(); ?>

    </div>
    <?php //echo ns_BtNovo(); 
    ?>
    <table class="table table-bordered dataTable table-sm" id="tableBasicas">
        <thead>
            <tr>
                <th>Agendamento</th>
                <th>Reagendamento</th>
                <th class="text-center">A&ccedil;&atilde;o</th>
            </tr>
        </thead>
    </table>
</form>