<?php

use jewish\backend\CMS;
use jewish\backend\helpers\utils;
use jewish\backend\helpers\view;

if (!defined("_VALID_PHP")) {die('Direct access to this location is not allowed.');}


// load CK Editor
view::appendJS(SITE_DIR.CMS_DIR.JS_DIR.'ckeditor/ckeditor.js');

?>


<!-- Content Header (Page header) -->
<section class="content-header">
	<h1>
		<?=CMS::t('menu_item_personalities_add');?>
		<!-- <small>Subtitile</small> -->
	</h1>

	<!-- <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Dashboard</li>
	</ol> -->
</section>

<!-- Content Header (Page header) -->
<section class="contextual-navigation">
	<nav>
		<a href="<?=utils::safeEcho($link_back, 1);?>" class="btn btn-default"><i class="fa fa-arrow-left" aria-hidden="true"></i> <?=CMS::t('back');?></a>
	</nav>
</section>


<!-- Main content -->
<section class="content">
	<?php
		if (!empty($op)) {
			if ($op['success']) {
				print view::notice($op['message'], 'success');
			} else {
				print view::notice(empty($op['errors'])? $op['message']: $op['errors']);
			}
		}
	?>

	<!-- Info boxes -->

	<div class="box">
		<!-- <div class="box-header with-border">
			<h3 class="box-title"><?=CMS::t('menu_item_personalities_add');?></h3>
		</div> -->
		<!-- /.box-header -->

		<form action="" method="post" class="form-std" role="form" enctype="multipart/form-data">
			<input type="hidden" name="CSRF_token" value="<?=$CSRF_token;?>" />

			<div class="box-body">
				<div class="row">
					<div class="col-md-12">

						<div class="form-group">
							<label><?=CMS::t('personalities_title');?> *</label>
							<input type="text" name="title" class="form-control" autocomplete="off" />
						</div>
						<div class="form-group">
							<label><?=CMS::t('personalities_img');?> *</label>
							<input type="file" name="img" class="form-control" />
						</div>
						<div class="form-group">
							<label><?=CMS::t('personalities_fio');?> *</label>
							<input type="text" name="fio" class="form-control" autocomplete="off" />
						</div>

						<div class="form-group">
							<label><?=CMS::t('personalities_birth');?> *</label>
							<input type="text" name="birth" class="form-control" autocomplete="off" />
						</div>

						<div class="form-group">
							<label><?=CMS::t('personalities_deadth');?> *</label>
							<input type="text" name="deadth" class="form-control" autocomplete="off" />
						</div>

						<div class="form-group">
							<label><?=CMS::t('personalities_content');?> *</label>
							<textarea name="content" rows="4" cols="32" class="form-input-std" id="wysiwyg_content"></textarea>
								
							<script type="text/javascript">
								// <![CDATA[
								CKEDITOR.replace('wysiwyg_content', {
									uiColor: '#f9f9f9',
									language: '<?=CMS::$default_site_lang?>',
									filebrowserBrowseUrl: '<?=SITE.CMS_DIR.JS_DIR?>ckeditor/ckfinder/ckfinder.html?hash=<?=CMS::$sess_hash?>',
									filebrowserUploadUrl: '<?=SITE.CMS_DIR.JS_DIR?>ckeditor/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files',
									height:200
								});
								// ]]>
							</script>
						</div>

						<div class="form-group">
							<input type="checkbox" name="is_published" value="1"<?=((isset($_POST['is_published']) && empty($_POST['is_published']))? '': ' checked="checked"');?> id="triggerpersonalitiesStatus" /><label for="triggerpersonalitiesStatus" style="display: inline; font-weight: normal;"> <?=CMS::t('publish');?></label>
						</div>
					</div>
				</div>
			</div>
			<!-- /.box-body -->

			<div class="box-footer">
				<button type="submit" name="add" value="1" class="btn btn-primary"><i class="fa fa-plus-circle" aria-hidden="true"></i> <?=CMS::t('add');?></button>
				<button type="reset" name="reset" value="1" class="btn btn-default"><i class="fa fa-refresh" aria-hidden="true"></i> <?=CMS::t('reset');?></button>
			</div>
		</form>
	</div>
	<!-- /.box -->

	<!-- /.info boxes -->
</section>
<!-- /.content -->