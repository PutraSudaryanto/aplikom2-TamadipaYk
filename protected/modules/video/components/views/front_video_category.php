<?php
/**
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Video-Albums
 * @contect (+62)856-299-4114
 *
 */
?>

<?php if($model != null) {?>
<div class="boxed">
	<h3><?php echo Yii::t('phrase', 'Video Categories');?></h3>
	<ul class="category">
	<?php 
	echo '<li><a href="'.Yii::app()->controller->createUrl('index').'" title="'.Yii::t('phrase', 'All').'">'.Yii::t('phrase', 'All').'</a></li>';
	foreach($model as $key => $val) {
		echo '<li><a href="'.Yii::app()->controller->createUrl('index', array('cat'=>$val->cat_id, 't'=>Phrase::trans($val->name,2))).'" title="'.Phrase::trans($val->name,2).'">'.Phrase::trans($val->name,2).'</a></li>';
	}?>
	</ul>
</div>
<?php }?>
