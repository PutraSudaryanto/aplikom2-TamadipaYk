<?php
/**
 * Psb Years (psb-years)
 * @var $this YearController * @var $model PsbYears * @var $form CActiveForm
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>
	<ul>
		<li>
			<?php echo $model->getAttributeLabel('year_id'); ?><br/>
			<?php echo $form->textField($model,'year_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('years'); ?><br/>
			<?php echo $form->textField($model,'years',array('size'=>9,'maxlength'=>9)); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('batchs'); ?><br/>
			<?php echo $form->textField($model,'batchs'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('courses'); ?><br/>
			<?php echo $form->textField($model,'courses'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('registers'); ?><br/>
			<?php echo $form->textField($model,'registers'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_date'); ?><br/>
			<?php echo $form->textField($model,'creation_date'); ?>
		</li>

		<li>
			<?php echo $model->getAttributeLabel('creation_id'); ?><br/>
			<?php echo $form->textField($model,'creation_id',array('size'=>11,'maxlength'=>11)); ?>
		</li>

		<li class="submit">
			<?php echo CHtml::submitButton(Phrase::trans(3,0)); ?>
		</li>
	</ul>
<?php $this->endWidget(); ?>
