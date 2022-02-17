<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title><?php echo NOME_SISTEMA; ?></title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/empresa/'. LOGO_SISTEMA); ?>">
	<!-- Font Awesome -->

	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte504/plugins/fontawesome-free/css/all.min.css'); ?>">
	<!-- Ionicons -->
	<link rel="stylesheet" href="<?php echo base_url('assets/css/ionicons.min.css'); ?>">
	<!-- DataTables -->
	<?php if (isset($arquivo_dataTable)) : ?>
		<link rel="stylesheet" href="<?php echo base_url('assets/adminlte504/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css'); ?>">
		<link rel="stylesheet" href="<?php echo base_url('assets/adminlte504/plugins/datatables-responsive/css/responsive.bootstrap4.min.css'); ?>">

	<?php endif; ?>
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte504/dist/css/adminlte.min.css'); ?>">
	<!-- Theme style -->
	<link rel="stylesheet" href="<?php echo base_url('assets/adminlte504/plugins/toastr/toastr.min.css'); ?>">
	<!-- Google Font: Source Sans Pro -->
	<link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<script>
	const base_url = "<?php echo base_url(); ?>";
	var pag_url = window.location.href.split('?')[0] + "/";
</script>
<style>
	.form_crud_modal,
	.form_crud {
		display: none;
	}

	.tb_crud th:last-child,
	td:last-child {
		width: 13%;
		text-align: center;
	}

	.alert-danger {
		padding: 3px;
		border: 0px;
	}

	.errors ul {
		list-style-type: none;
		margin: 0 5px;
		padding: 0;
	}

	::-webkit-scrollbar {
		width: 5px;
	}

	/* Track */
	::-webkit-scrollbar-track {
		box-shadow: inset 0 0 5px grey;
		border-radius: 10px;
	}

	/* Handle */
	::-webkit-scrollbar-thumb {
		background: red;
		border-radius: 10px;
	}

	/* Handle on hover */
	::-webkit-scrollbar-thumb:hover {
		background: #b30000;
	}

	/***********TOASTR************/
	.toast-top-center-element {
		top: 10%;
		right: 0;
		width: 100%
	}

	#toast-container.toast-top-center-element>div {
		min-width: 500px;
		margin-left: auto;
		margin-right: auto
	}

	/*
input:invalid, select:invalid {
  background-color: #FFCCCC; opacity: 0.5; 
}
input:valid {
  background-color: #ddffdd;
}
*/
</style>