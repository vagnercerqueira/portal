            <style type="text/css">
				.box_venda_lote, .box_bov {
					cursor: move;
				}
			</style>
            <div id="accordion">
                <form method="post" name="venda_lote">
                    <div class="card card-primary">
                    <div class="card-header">
                        <h4 class="card-title w-100">
                            <a class="d-block w-100 collapsed" data-toggle="collapse" href="#collapseOne" aria-expanded="false">
                            VENDA LOTE
                            </a>
                        </h4>
                        </div>
                        <div id="collapseOne" class="collapse" data-parent="#accordion" style="">
                        <div class="card-body">
                            <div class="container_venda_lote row">                    
                                <?php foreach($campos_venda_lote as $k=>$v): ?>                    
                                    <a href="javascript:;" style="margin:3px;" draggable="true"  class="btn-xs btn btn-primary box box_venda_lote"><span data-value="<?php echo $k; ?>"><?php echo $v; ?></span></a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
				<form method="post" name="bov">
					  <div class="card card-danger">
						<div class="card-header">
						  <h4 class="card-title w-100">
							<a class="d-block w-100" data-toggle="collapse" href="#collapseTwo">
							 BOV
							</a>
						  </h4>
						</div>
						<div id="collapseTwo" class="collapse" data-parent="#accordion">
						  <div class="card-body">
							<div class="container_bov row">                    
                                <?php foreach($campos_bov as $k=>$v): ?>                    
                                    <a href="javascript:;" style="margin:3px;" draggable="true"  class="btn-xs btn btn-primary box box_bov"><span data-value="<?php echo $k; ?>"><?php echo $v; ?></span></a>
                                <?php endforeach; ?>
							</div>
						  </div>
						</div>
					  </div>
				</form>
				<form method="post" name="maling">
					  <div class="card card-success">
						<div class="card-header">
						  <h4 class="card-title w-100">
							<a class="d-block w-100" data-toggle="collapse" href="#collapseThree">
							 MALING
							</a>
						  </h4>
						</div>
						<div id="collapseThree" class="collapse" data-parent="#accordion">
						  <div class="card-body">
							<div class="container_maling row">                    
                                <?php foreach($campos_mailing as $k=>$v): $kv=explode("=>",$v); ?>                    
                                    <a href="javascript:;" style="margin:3px;" draggable="true"  class="btn-xs btn btn-primary box box_maling"><span data-value="<?php echo $k; ?>"><?php echo $v; ?></span></a>
                                <?php endforeach; ?>
							</div>
						  </div>
						</div>
					  </div>
				</form>
                </div>
            </div>