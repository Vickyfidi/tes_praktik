<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>RS QUEEN LATIFA | MANAJEMEN DATA PASIEN</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>">
	<script type="text/javascript" src="<?php echo base_url(); ?>assets/jquery/jquery-3.3.1.min.js"></script>
</head>

<body>
	<div>

		<div class="col-md-10">
			<div>
				<h3>Manajemen <small class="text-muted">Data Pasien</small></h3>
			</div>

			<button type="button" class="btn btn-success btn-lg" onclick="add_pasien()"> Add Data</button>
			<button type="button" class="btn btn-info btn-lg" onclick="reload_table()"> Reload</button>
			<br />
			<br />
			<table id="table" class="display table table-bordered" cellspacing="0" width="100%">
				<thead>
					<tr>
						<th>No_RM</th>
						<th>Nama</th>
						<th>tgl_lahir</th>
						<th>jenis_kelamin</th>
						<th></th>
					</tr>
				</thead>
			</table>
		</div>

		<script src="<?= base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/DataTables/datatables.min.css" />
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/DataTables/datatables.min.js"></script>
		<script type="text/javascript">
			var save_method;
			var table;
			$(document).ready(function() {

				//datatables
				table = $('#table').DataTable({
					responsive: true,
					"processing": true,
					"serverSide": true,
					"order": [],
					"ajax": {
						"url": "<?php echo site_url('pasien/ajax_list') ?>",
						"type": "POST"
					},

					"columnDefs": [{
						"targets": [-1],
						"orderable": false,
					}, ],
				});

				$("input").change(function() {
					$(this).parent().parent().removeClass('has-error');
					$(this).next().empty();
				});
				$("textarea").change(function() {
					$(this).parent().parent().removeClass('has-error');
					$(this).next().empty();
				});
				$("select").change(function() {
					$(this).parent().parent().removeClass('has-error');
					$(this).next().empty();
				});

			});

			function reload_table() {
				table.ajax.reload(null, false);
			}

			function add_pasien() {
				save_method = 'add';
				$('#form')[0].reset();
				$('.form-group').removeClass('has-error');
				$('.help-block').empty();
				$('#modal_form').modal('show');
				$('.modal-title').text('Add Data');
			}

			function edit_pasien(id) {
				save_method = 'update';
				$('#form')[0].reset();
				$('.form-group').removeClass('has-error');
				$('.help-block').empty();

				$.ajax({
					url: "<?php echo site_url('pasien/ajax_edit/') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id"]').val(data.id);
						$('[name="no_rm"]').val(data.no_rm);
						$('[name="nama"]').val(data.nama);
						$('[name="tgl_lahir"]').val(data.tgl_lahir);
						$('[name="jenis_kelamin"]').val(data.jenis_kelamin);
						$('#modal_form').modal('show');
						$('.modal-title').text('Edit Data');
						tinymce.get('editor1').setContent(data.alamat);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Error get data from ajax');
					}
				});
			}

			function delete_pasien(id) {
				if (confirm('Are you sure delete this data?')) {
					// ajax delete data to database
					$.ajax({
						url: "<?php echo site_url('pasien/ajax_delete') ?>/" + id,
						type: "POST",
						dataType: "JSON",
						success: function(data) {
							$('#modal_form').modal('hide');
							reload_table();
						},
						error: function(jqXHR, textStatus, errorThrown) {
							alert('Error deleting data');
						}
					});
				}
			}



			function save() {
				$('#btnSave').text('saving...');
				$('#btnSave').attr('disabled', true);
				var url;
				if (save_method == 'add') {
					url = "<?php echo site_url('pasien/ajax_add') ?>";
				} else {
					url = "<?php echo site_url('pasien/ajax_update') ?>";
				}

				$.ajax({
					url: url,
					type: "POST",
					data: $('#form').serialize(),
					dataType: "JSON",
					success: function(data) {
						if (data.status) {
							$('#modal_form').modal('hide');
							reload_table();
						} else {
							for (var i = 0; i < data.inputerror.length; i++) {
								$('[name="' + data.inputerror[i] + '"]').parent().parent().addClass('has-error');
								$('[name="' + data.inputerror[i] + '"]').next().text(data.error_string[i]);
							}
						}
						$('#btnSave').text('save');
						$('#btnSave').attr('disabled', false);
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Error adding / update data');
						$('#btnSave').text('save');
						$('#btnSave').attr('disabled', false);
					}
				});
			}
		</script>

		<!-- Modal -->
		<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="modal_form">Tambah Data Pasien</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<form action="#" id="form" class="form-horizontal">
							<input type="hidden" value="" name="id" />
							<div class="row">
								<div class="col-md-6">
									<fieldset class="form-group">
										<label class="form-label">Nomor RM Pasien</label>
										<input name="no_rm" placeholder="Nomor RM" class="form-control form-control-blue-fill" type="text">
									</fieldset>

									<fieldset class="form-group">
										<label class="form-label">Nama Pasien</label>
										<input name="nama" placeholder="Nama Pasien" class="form-control form-control-blue-fill" type="text">
									</fieldset>

									<fieldset class="form-group">
										<label class="form-label">Tanggal Lahir</label>
										<input name="tgl_lahir" placeholder="Tanggal Lahir" class="form-control form-control-blue-fill" type="date">
									</fieldset>

									<fieldset class="form-group">
										<label class="form-label">Jenis Kelamin</label>
										<select name="jenis_kelamin" class="form-control form-control-blue-fill">
											<option value="">Pilih Jenis Kelamin</option>
											<option value="L">Laki-laki</option>
											<option value="P">Perempuan</option>
										</select>
									</fieldset>


								</div>

							</div><!--.row-->
						</form>
					</div>
					<div class="modal-footer">
						<button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			</div>
		</div>
</body>

</html>
