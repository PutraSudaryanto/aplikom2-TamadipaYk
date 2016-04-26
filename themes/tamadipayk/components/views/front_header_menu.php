<?php 
	$module = strtolower(Yii::app()->controller->module->id);
	$controller = strtolower(Yii::app()->controller->id);
	$action = strtolower(Yii::app()->controller->action->id);
	$currentAction = strtolower(Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
	$currentModule = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
	$currentModuleAction = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id.'/'.Yii::app()->controller->action->id);
?>
<ul class="clearfix">
	<?php if($this->type == true) {?>
		<li class="responsive-lx">
			<a href="javascript:void(0);" title="Menu">Menu</a>
			<?php $this->widget('FrontHeaderMenu', array(
				'type'=>false,
			)); ?>	
		</li>
	<?php }?>	
	<li class="<?php echo ($this->type == true ? (($module == null && $currentAction == 'site/index') ? 'responsive-ls active' : 'responsive-ls') : '');?>"><a href="<?php echo Yii::app()->createUrl('site/index');?>" title="Home">Home</a></li>
	<li class="<?php echo ($this->type == true ? (($module != null && ($module == 'article')) ? 'responsive-ls active' : 'responsive-ls') : '');?>"><a href="<?php echo Yii::app()->createUrl('article/site/index');?>" title="Berita">Berita</a>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>1,'t'=>Utility::getUrlTitle(Phrase::trans(1531, 2))));?>" title="<?php echo Phrase::trans(1531, 2)?>"><?php echo Phrase::trans(1531, 2)?></a></li>
			<li><a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>2,'t'=>Utility::getUrlTitle(Phrase::trans(1533, 2))));?>" title="<?php echo Phrase::trans(1533, 2)?>"><?php echo Phrase::trans(1533, 2)?></a></li>
			<li><a href="<?php echo Yii::app()->createUrl('article/site/index', array('category'=>3,'t'=>Utility::getUrlTitle(Phrase::trans(1535, 2))));?>" title="<?php echo Phrase::trans(1535, 2)?>"><?php echo Phrase::trans(1535, 2)?></a></li>
		</ul>
	</li>
	<li class="<?php echo ($this->type == true ? (($module != null && (in_array($currentModule, array('album/site','video/site')))) ? 'responsive-ls active' : 'responsive-ls') : '');?>"><a href="<?php echo Yii::app()->createUrl('album/site/index');?>" title="Galeri">Galeri</a>
		<ul>
			<li><a href="<?php echo Yii::app()->createUrl('album/site/index', array('type'=>'photo'));?>" title="PTMIP Photo Albums">TMIP Photo</a></li>
			<li><a href="<?php echo Yii::app()->createUrl('video/site/index');?>" title="TMIP Video Albums">TMIP Video</a></li>
		</ul>
	</li>
	<li class="<?php echo ($this->type == true ? (($module != null && $module == 'psb') ? 'responsive-ls active' : 'responsive-ls') : '');?>"><a href="<?php echo Yii::app()->createUrl('psb/site/index');?>" title="Penerimaan Siswa Baru">Penerimaan Siswa Baru</a></li>
</ul>