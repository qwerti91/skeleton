<!DOCTYPE html>
<html lang="en-US">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="stylesheet" type="text/css" href="<?= URL; ?>public/css/bootstrap/bootstrap.min.css">
		<style>
		</style>
		<script src="<?= URL; ?>public/js/jquery-3.2.1.min.js"></script>
		<script src="<?= URL; ?>public/js/bootstrap/bootstrap.min.js"></script>
	</head>
	<body>
		<div id="content" class="col-md-12">
			<?= $this->fetch('content'); ?>
		</div>
	</body>
</html>