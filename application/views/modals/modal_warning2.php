<div class="modal fade" id="myModalMapWarnings2" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">

			<div class="modal-header" style="background-color: #eee;" align="center">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">
					<img src="images/warning1.png" id="logo-alertaModal" style="height: 60px; width: 60px;">
				</h4>
				<h3 id="h3ModalMapWarning2" align="center" class="text-secondary"></h3>
			</div> <!-- modal-header -->

			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<center>
							<div class="col-sm-offset-1 col-sm-10">
								<strong id="messageModalMapWarning2" class="text-info">
								</strong>
							</div>
						</center>
					</div>
				</form>
			</div> <!-- modal-body -->

			<div id="divModalFooter" class="modal-footer">
				<div class="col-sm-12">
					<div class="col-sm-6">
						<button type="button" id="btnModalConfirm" title="CONTINUAR" class="btn btn-success btn-block" onclick="addLayerConfirm()">CONTINUAR&nbsp;&nbsp;<i class="fa fa-check" aria-hidden="true"></i>
						</button>
					</div>
					<div class="col-sm-6">
						<button type="button" id="btnModalCancel" title="CANCELAR" class="btn btn-block" onclick="addLayerCancel()">CANCELAR&nbsp;&nbsp;<i class="fa fa-close" aria-hidden="true"></i>
						</button>
					</div>
				</div>
			</div> <!-- modal-footer -->
		</div> <!-- modal-content -->
	</div> <!-- modal-dialog -->
</div> <!-- modal fade -->