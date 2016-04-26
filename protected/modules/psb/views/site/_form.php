<?php
/**
 * Psb Registers (psb-registers)
 * @var $this Site1Controller * @var $model PsbRegisters * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('application.components.system.OActiveForm', array(
	'id'=>'psb-registers-form',
	'enableAjaxValidation'=>true,
	//'htmlOptions' => array('enctype' => 'multipart/form-data')
)); ?>

<?php //begin.Messages ?>
<div id="ajax-message">
	<?php echo $form->errorSummary($model); ?>
</div>
<?php //begin.Messages ?>

<?php 
$model->batch_id = $batch;
echo $form->hiddenField($model,'batch_id'); ?>

<fieldset>
	<h3>Identitas Pendaftar</h3>
	<div class="clearfix">
		<?php echo $form->labelEx($model,'register_name'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'register_name',array('maxlength'=>32)); ?>
			<?php echo $form->error($model,'register_name'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'birth_city'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'birth_city',array('maxlength'=>11)); ?>
			<?php echo $form->error($model,'birth_city'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'birth_date'); ?>
		<div class="desc">
			<?php
			!$model->isNewRecord ? ($model->birth_date != '0000-00-00' ? $model->birth_date = date('d-m-Y', strtotime($model->birth_date)) : '') : '';
			//echo $form->textField($model,'birth_date');
			$this->widget('zii.widgets.jui.CJuiDatePicker',array(
				'model'=>$model,
				'attribute'=>'birth_date',
				//'mode'=>'datetime',
				'options'=>array(
					'dateFormat' => 'dd-mm-yy',
				),
				'htmlOptions'=>array(
					'class' => 'span-4',
				 ),
			)); ?>
			<?php echo $form->error($model,'birth_date'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'address'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'address',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'address'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'address_phone'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'address_phone',array('size'=>15,'maxlength'=>15)); ?>
			<?php echo $form->error($model,'address_phone'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'address_yogya'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'address_yogya',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'address_yogya'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'address_yogya_phone'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'address_yogya_phone',array('size'=>15,'maxlength'=>15)); ?>
			<?php echo $form->error($model,'address_yogya_phone'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h3>Identitas Orangtua Pendaftar</h3>
	<div class="clearfix">
		<?php echo $form->labelEx($model,'parent_name'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'parent_name',array('maxlength'=>32)); ?>
			<?php echo $form->error($model,'parent_name'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'parent_work'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'parent_work',array('maxlength'=>32)); ?>
			<?php echo $form->error($model,'parent_work'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'parent_address'); ?>
		<div class="desc">
			<?php echo $form->textArea($model,'parent_address',array('rows'=>6, 'cols'=>50)); ?>
			<?php echo $form->error($model,'parent_address'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($model,'parent_phone'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'parent_phone',array('size'=>15,'maxlength'=>15)); ?>
			<?php echo $form->error($model,'parent_phone'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h3>Asal Sekolah</h3>
	<div class="clearfix">
		<?php echo $form->labelEx($model,'school_input'); ?>
		<div class="desc">
			<?php //echo $form->textField($model,'school_input',array('maxlength'=>64));
			$url = Yii::app()->controller->createUrl('school/ajaxget', array('type'=>'school'));
			$this->widget('zii.widgets.jui.CJuiAutoComplete', array(
				'model' => $model,
				'attribute' => 'school_input',
				'source' => Yii::app()->controller->createUrl('school/suggest'),
				'options' => array(
					//'delay '=> 50,
					'minLength' => 1,
					'showAnim' => 'fold',
					'select' => "js:function(event, ui) {
						$('form #PsbRegisters_school_input').val(ui.item.value);
						$('form #PsbRegisters_school_id').val(ui.item.id);
						$.ajax({
							type: 'post',
							url: '$url',
							data: { school_id: ui.item.id},
							dataType: 'json',
							success: function(response) {
								$('form #PsbSchools_school_address').val(response.school_address);
								$('form #PsbSchools_school_phone').val(response.school_phone);
								$('form #PsbSchools_school_status').val(response.school_status);
							}
						});
					}"
				),
				'htmlOptions' => array(
					'class'	=> 'span-8',
					'maxlength'=>64,
				),
			));
			echo $form->error($model,'school_input');
			echo $form->hiddenField($model,'school_id');	
			?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($school,'school_address'); ?>
		<div class="desc">
			<?php echo $form->textArea($school,'school_address',array('rows'=>6, 'cols'=>50, 'class'=>'span-11')); ?>
			<?php echo $form->error($school,'school_address'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($school,'school_phone'); ?>
		<div class="desc">
			<?php echo $form->textField($school,'school_phone',array('maxlength'=>15)); ?>
			<?php echo $form->error($school,'school_phone'); ?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($school,'school_status'); ?>
		<div class="desc">
			<?php echo $form->dropDownList($school,'school_status',array(
				1=>'Negeri',
				0=>'Swasta',					
			)); ?>
			<?php echo $form->error($school,'school_status'); ?>
		</div>
	</div>
</fieldset>

<fieldset>
	<h3>Nilai Ujian Nasional</h3>
	<div class="clearfix">
		<?php echo $form->labelEx($model,'school_un_rank'); ?>
		<div class="desc">
			<?php echo $form->textField($model,'school_un_rank',array('maxlength'=>32)); ?>
			<?php echo $form->error($model,'school_un_rank'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

</fieldset>

<fieldset>
	<h3>Author</h3>
	<div class="clearfix">
		<?php echo $form->labelEx($author,'name'); ?>
		<div class="desc">
			<?php echo $form->textField($author,'name',array('maxlength'=>32, 'class'=>'span-8')); ?>
			<?php echo $form->error($author,'name'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="clearfix">
		<?php echo $form->labelEx($author,'email'); ?>
		<div class="desc">
			<?php echo $form->textField($author,'email',array('maxlength'=>32, 'class'=>'span-8')); ?>
			<?php echo $form->error($author,'email'); ?>
			<?php /*<div class="small-px silent"></div>*/?>
		</div>
	</div>

	<div class="submit clearfix">
		<label>&nbsp;</label>
		<div class="desc">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('phrase', 'Create') : Yii::t('phrase', 'Save'), array('onclick' => 'setEnableSave()')); ?>
		</div>
	</div>

</fieldset>
<?php $this->endWidget(); ?>


