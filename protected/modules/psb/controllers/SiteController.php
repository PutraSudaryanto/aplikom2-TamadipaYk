<?php
/**
 * Site1Controller
 * @var $this Site1Controller
 * @var $model PsbRegisters * @var $form CActiveForm
 * Copyright (c) 2013, Ommu Platform (ommu.co). All rights reserved.
 * version: 0.0.1
 * Reference start
 *
 * TOC :
 *	Index
 *
 *	LoadModel
 *	performAjaxValidation
 *
 * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
 * @contect (+62)856-299-4114
 *
 *----------------------------------------------------------------------------------------------------------
 */

class SiteController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	//public $layout='//layouts/column2';
	public $defaultAction = 'index';

	/**
	 * Initialize admin page theme
	 */
	public function init() 
	{
		$arrThemes = Utility::getCurrentTemplate('public');
		Yii::app()->theme = $arrThemes['folder'];
		$this->layout = $arrThemes['layout'];
	}

	/**
	 * @return array action filters
	 */
	public function filters() 
	{
		return array(
			'accessControl', // perform access control for CRUD operations
			//'postOnly + delete', // we only allow deletion via POST request
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules() 
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','add'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level)',
				//'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level != 1)',
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array(),
				'users'=>array('@'),
				'expression'=>'isset(Yii::app()->user->level) && (Yii::app()->user->level == 1)',
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array(),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
	
	/**
	 * Lists all models.
	 */
	public function actionIndex() 
	{
		$criteria=new CDbCriteria;
		$criteria->condition = 'curdate() BETWEEN `batch_start` AND `batch_finish`';
		$batch = PsbYearBatch::model()->find($criteria);
		
		if($batch != null)
			$this->redirect(array('add'));
		
		else {
			$this->pageTitle = 'Psb Registers';
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('front_index');
		}
	}
	
	/**
	 * Lists all models.
	 */
	public function actionAdd()
	{
		$criteria=new CDbCriteria;
		$criteria->condition = 'curdate() BETWEEN `batch_start` AND `batch_finish`';
		$batch = PsbYearBatch::model()->find($criteria);
		
		if($batch == null)
			$this->redirect(array('index'));
		
		else {
			$model=new PsbRegisters;
			$school=new PsbSchools;
			$author=new OmmuAuthors;

			// Uncomment the following line if AJAX validation is needed
			$this->performAjaxValidation($model);

			if(isset($_POST['PsbRegisters'])) {
				$model->attributes=$_POST['PsbRegisters'];
				$school->attributes=$_POST['PsbSchools'];
				$author->attributes=$_POST['OmmuAuthors'];
				
				$authorModel = OmmuAuthors::model()->find(array(
					'select' => 'author_id, email',
					'condition' => 'publish = 1 AND email = :email',
					'params' => array(
						':email' => strtolower($author->email),
					),
				));
				if($authorModel != null) {
					$model->author_id = $authorModel->author_id;
				} else {
					if($author->save())
						$model->author_id = $author->author_id;
				}
				
				
				if($model->save()) {
					Yii::app()->user->setFlash('success', 'PsbRegisters success created.');
					//$this->redirect(array('view','id'=>$model->register_id));
					$this->redirect(array('index'));
				}
			}

			$this->pageTitle = 'Psb Registers';
			$this->pageDescription = '';
			$this->pageMeta = '';
			$this->render('front_add',array(
				'model'=>$model,
				'school'=>$school,
				'author'=>$author,
				'batch'=>$batch->batch_id,
			));
		}
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id) 
	{
		$model = PsbRegisters::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404, Yii::t('phrase', 'The requested page does not exist.'));
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model) 
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='psb-registers-form') {
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
