<?php
/**
 * Videoses (videos)
 * @var $this AdminController
 * @var $model Videos
 * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Video-Albums
 * @contect (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'videos-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>
<div class="dialog-content">
	<fieldset>
		<?php if(VideoCategory::getCategory(1) != null) {?>
		<div class="clearfix">
			<?php echo $form->labelEx($model,'cat_id'); ?>
			<div class="desc">
				<?php echo $form->dropDownList($model,'cat_id', VideoCategory::getCategory(1)); ?>
				<?php echo $form->error($model,'cat_id'); ?>
			</div>
		</div>
		<?php }?>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'title'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'title',array('maxlength'=>128,'class'=>'span-8')); ?>
				<?php echo $form->error($model,'title'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'body'); ?>
			<div class="desc">
				<?php 
				//echo $form->textArea($model,'body',array('rows'=>6, 'cols'=>50, 'class'=>'span-11 smaller'));
				$this->widget('application.extensions.imperavi.ImperaviRedactorWidget', array(
					'model'=>$model,
					'attribute'=>body,
					// Redactor options
					'options'=>array(
						//'lang'=>'fi',
						'buttons'=>array(
							'html', 'formatting', '|', 
							'bold', 'italic', 'deleted', '|',
							'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
							'link', '|',
						),
					),
					'plugins' => array(
						'fontcolor' => array('js' => array('fontcolor.js')),
						'table' => array('js' => array('table.js')),
						'fullscreen' => array('js' => array('fullscreen.js')),
					),
				)); ?>
				<?php echo $form->error($model,'body'); ?>
			</div>
		</div>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'media'); ?>
			<div class="desc">
				<?php echo $form->textField($model,'media',array('maxlength'=>32,'class'=>'span-6')); ?>
				<?php echo $form->error($model,'media'); ?>
				<span class="small-px">http://www.youtube.com/watch?v=<strong>HOAqSoDZSho</strong></span>
			</div>
		</div>

		<?php if(OmmuSettings::getInfo(site_type) == '1') {?>
		<div class="clearfix">
			<?php echo $form->labelEx($model,'comment_code'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'comment_code'); ?>
				<?php echo $form->error($model,'comment_code'); ?>
			</div>
		</div>
		<?php } else {
			$model->comment_code = 0;
			echo $form->hiddenField($model,'comment_code');
		}?>

		<?php if(OmmuSettings::getInfo(site_headline) == '1') {?>
		<div class="clearfix">
			<?php echo $form->labelEx($model,'headline'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'headline'); ?>
				<?php echo $form->error($model,'headline'); ?>
			</div>
		</div>
		<?php } else {
			$model->headline = 0;
			echo $form->hiddenField($model,'headline');
		}?>

		<div class="clearfix">
			<?php echo $form->labelEx($model,'publish'); ?>
			<div class="desc">
				<?php echo $form->checkBox($model,'publish'); ?>
				<?php echo $form->error($model,'publish'); ?>
			</div>
		</div>
		
	</fieldset>
</div>
<div class="dialog-submit">
	<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
	<?php echo CHtml::button('Closed', array('id'=>'closed')); ?>
</div>
<fieldset>
<?php $this->endWidget(); ?>


