<?PHP if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<?php
	error_reporting(0);
	$selectedlang = $this->session->language;
	$this->lang->load('labels', strtolower($selectedlang));
	//header of template
	$this->load->view('layout/header');
?>
<?php
//sidebar of the template
$this->load->view('layout/sidebar');
?>
<!-- Content Wrapper. Contains page content -->
<div class="main-content">
	<div class="page-content">
        <div class="container-fluid">
<?php
$this->load->view('layout/breadcrumbs');
?>
	<?php
		echo $content;
	?>
<?php
	//footer of template
	$this->load->view('layout/footer')
?>
