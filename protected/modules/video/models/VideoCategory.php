<?php
/**
 * VideoCategory
 * @author Putra Sudaryanto <putra@sudaryanto.id>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link https://github.com/oMMu/Ommu-Video-Albums
 * @contact (+62)856-299-4114
 *
 * This is the template for generating the model class of a specified table.
 * - $this: the ModelCode object
 * - $tableName: the table name for this class (prefix is already removed if necessary)
 * - $modelClass: the model class name
 * - $columns: list of table columns (name=>CDbColumnSchema)
 * - $labels: list of attribute labels (name=>label)
 * - $rules: list of validation rules
 * - $relations: list of relations (name=>relation declaration)
 *
 * --------------------------------------------------------------------------------------
 *
 * This is the model class for table "ommu_video_category".
 *
 * The followings are the available columns in table 'ommu_video_category':
 * @property integer $cat_id
 * @property integer $publish
 * @property integer $dependency
 * @property integer $orders
 * @property string $name
 * @property string $desc
 * @property string $creation_date
 * @property string $creation_id
 * @property string $modified_date
 * @property string $modified_id
 *
 * The followings are the available model relations:
 * @property OmmuVideos[] $ommuVideoses
 */
class VideoCategory extends CActiveRecord
{
	public $defaultColumns = array();
	public $title;
	public $description;
	public $count_video;
	
	// Variable Search
	public $creation_search;
	public $modified_search;

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return VideoCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'ommu_video_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('
				title, description', 'required'),
			array('publish, dependency, orders, creation_id, modified_id', 'numerical', 'integerOnly'=>true),
			array('name, desc,
				count_video', 'length', 'max'=>11),
			array('
				title', 'length', 'max'=>32),
			array('
				description', 'length', 'max'=>128),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cat_id, publish, dependency, orders, name, desc, creation_date, creation_id, modified_date, modified_id,
				title, description, count_video, creation_search, modified_search', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'view_cat' => array(self::BELONGS_TO, 'ViewVideoCategory', 'cat_id'),
			'creation_relation' => array(self::BELONGS_TO, 'Users', 'creation_id'),
			'modified_relation' => array(self::BELONGS_TO, 'Users', 'modified_id'),
			'video' => array(self::HAS_MANY, 'Videos', 'cat_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cat_id' => Yii::t('attribute', 'Category'),
			'publish' => Yii::t('attribute', 'Publish'),
			'dependency' => Yii::t('attribute', 'Parent'),
			'orders' => Yii::t('attribute', 'Orders'),
			'name' => Yii::t('attribute', 'Title'),
			'desc' => Yii::t('attribute', 'Description'),
			'creation_date' => Yii::t('attribute', 'Creation Date'),
			'creation_id' => Yii::t('attribute', 'Creation'),
			'modified_date' => Yii::t('attribute', 'Modified Date'),
			'modified_id' => Yii::t('attribute', 'Modified'),
			'title' => Yii::t('attribute', 'Title'),
			'description' => Yii::t('attribute', 'Description'),
			'count_video' => Yii::t('attribute', 'Video'),
			'creation_search' => Yii::t('attribute', 'Creation'),
			'modified_search' => Yii::t('attribute', 'Modified'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.cat_id',$this->cat_id);
		if(isset($_GET['type']) && $_GET['type'] == 'publish') {
			$criteria->compare('t.publish',1);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'unpublish') {
			$criteria->compare('t.publish',0);
		} elseif(isset($_GET['type']) && $_GET['type'] == 'trash') {
			$criteria->compare('t.publish',2);
		} else {
			$criteria->addInCondition('t.publish',array(0,1));
			$criteria->compare('t.publish',$this->publish);
		}
		$criteria->compare('t.dependency',$this->dependency);
		$criteria->compare('t.orders',$this->orders);
		$criteria->compare('t.name',$this->name,true);
		$criteria->compare('t.desc',$this->desc,true);
		if($this->creation_date != null && !in_array($this->creation_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.creation_date)',date('Y-m-d', strtotime($this->creation_date)));
		$criteria->compare('t.creation_id',$this->creation_id);
		if($this->modified_date != null && !in_array($this->modified_date, array('0000-00-00 00:00:00', '0000-00-00')))
			$criteria->compare('date(t.modified_date)',date('Y-m-d', strtotime($this->modified_date)));
		$criteria->compare('t.modified_id',$this->modified_id);
		$criteria->compare('t.count_video',$this->count_video);

		// Custom Search
		$criteria->with = array(
			'view_cat' => array(
				'alias'=>'view_cat',
				'select'=>'category_name, category_desc'
			),
			'creation_relation' => array(
				'alias'=>'creation_relation',
				'select'=>'displayname'
			),
			'modified_relation' => array(
				'alias'=>'modified_relation',
				'select'=>'displayname'
			),
		);
		$criteria->compare('view_cat.category_name',strtolower($this->title), true);
		$criteria->compare('view_cat.category_desc',strtolower($this->description), true);
		$criteria->compare('creation_relation.displayname',strtolower($this->creation_search), true);
		$criteria->compare('modified_relation.displayname',strtolower($this->modified_search), true);

		if(!isset($_GET['VideoCategory_sort']))
			$criteria->order = 't.cat_id DESC';

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
			'pagination'=>array(
				'pageSize'=>30,
			),
		));
	}


	/**
	 * Get column for CGrid View
	 */
	public function getGridColumn($columns=null) {
		if($columns !== null) {
			foreach($columns as $val) {
				/*
				if(trim($val) == 'enabled') {
					$this->defaultColumns[] = array(
						'name'  => 'enabled',
						'value' => '$data->enabled == 1? "Ya": "Tidak"',
					);
				}
				*/
				$this->defaultColumns[] = $val;
			}
		} else {
			//$this->defaultColumns[] = 'cat_id';
			$this->defaultColumns[] = 'publish';
			$this->defaultColumns[] = 'dependency';
			$this->defaultColumns[] = 'orders';
			$this->defaultColumns[] = 'name';
			$this->defaultColumns[] = 'desc';
			$this->defaultColumns[] = 'creation_date';
			$this->defaultColumns[] = 'creation_id';
			$this->defaultColumns[] = 'modified_date';
			$this->defaultColumns[] = 'modified_id';
			$this->defaultColumns[] = 'count_article';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			/*
			$this->defaultColumns[] = array(
				'class' => 'CCheckBoxColumn',
				'name' => 'id',
				'selectableRows' => 2,
				'checkBoxHtmlOptions' => array('name' => 'trash_id[]')
			);
			*/
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'title',
				'value' => 'Phrase::trans($data->name, 2)',
			);
			$this->defaultColumns[] = array(
				'name' => 'description',
				'value' => 'Phrase::trans($data->desc, 2)',
			);
			$this->defaultColumns[] = array(
				'name' => 'dependency',
				'value' => '$data->dependency != 0 ? Phrase::trans(VideoCategory::model()->findByPk($data->dependency)->name, 2) : "-"',
			);
			$this->defaultColumns[] = array(
				'header' => 'count_video',
				'value' => 'CHtml::link($data->count_video." ".Yii::t(\'attribute\', \'Video\'), Yii::app()->controller->createUrl("o/admin/manage",array("category"=>$data->cat_id)))',
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_search',
				'value' => '$data->creation_relation->displayname',
			);
			$this->defaultColumns[] = array(
				'name' => 'creation_date',
				'value' => 'Utility::dateFormat($data->creation_date)',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter' => Yii::app()->controller->widget('zii.widgets.jui.CJuiDatePicker', array(
					'model'=>$this,
					'attribute'=>'creation_date',
					'language' => 'ja',
					'i18nScriptFile' => 'jquery.ui.datepicker-en.js',
					//'mode'=>'datetime',
					'htmlOptions' => array(
						'id' => 'creation_date_filter',
					),
					'options'=>array(
						'showOn' => 'focus',
						'dateFormat' => 'dd-mm-yy',
						'showOtherMonths' => true,
						'selectOtherMonths' => true,
						'changeMonth' => true,
						'changeYear' => true,
						'showButtonPanel' => true,
					),
				), true),
			);
			if(!isset($_GET['type'])) {
				$this->defaultColumns[] = array(
					'name' => 'publish',
					'value' => 'Utility::getPublish(Yii::app()->controller->createUrl("publish",array("id"=>$data->cat_id)), $data->publish, 1)',
					'htmlOptions' => array(
						'class' => 'center',
					),
					'filter'=>array(
						1=>Yii::t('phrase', 'Yes'),
						0=>Yii::t('phrase', 'No'),
					),
					'type' => 'raw',
				);
			}
		}
		parent::afterConstruct();
	}

	/**
	 * User get information
	 */
	public static function getInfo($id, $column=null)
	{
		if($column != null) {
			$model = self::model()->findByPk($id,array(
				'select' => $column
			));
			return $model->$column;

		} else {
			$model = self::model()->findByPk($id);
			return $model;
		}
	}

	/**
	 * Get category
	 * 0 = unpublish
	 * 1 = publish
	 */
	public static function getCategory($publish=null) {
		if($publish == null) {
			$model = self::model()->findAll();
		} else {
			$model = self::model()->findAll(array(
				//'select' => 'publish, name',
				'condition' => 'publish = :publish',
				'params' => array(
					':publish' => $publish,
				),
				//'order' => 'cat_id ASC'
			));
		}

		$items = array();
		if($model != null) {
			foreach($model as $key => $val) {
				$items[$val->cat_id] = Phrase::trans($val->name, 2);
			}
			return $items;
		} else {
			return false;
		}
	}

	/**
	 * Get Video
	 */
	public static function getVideo($id, $type=null) {
		$criteria=new CDbCriteria;
		$criteria->compare('cat_id',$id);

		if($type == null) {
			//$criteria->select = '';
			$model = Videos::model()->findAll($criteria);
		} else {
			$model = Videos::model()->count($criteria);
		}

		return $model;
	}

	protected function afterFind() {
		$this->count_video = self::getVideo($this->cat_id, 'count');
		parent::afterFind();
	}

	/**
	 * before validate attributes
	 */
	protected function beforeValidate() {
		if(parent::beforeValidate()) {
			if($this->isNewRecord) {
				$this->orders = 0;
				$this->user_id = Yii::app()->user->id;
			} else
				$this->modified_id = Yii::app()->user->id;
		}
		return true;
	}

	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			if($this->isNewRecord) {
				$location = strtolower(Yii::app()->controller->module->id.'/'.Yii::app()->controller->id);
				$title=new OmmuSystemPhrase;
				$title->location = $location.'_title';
				$title->en_us = $this->title;
				if($title->save())
					$this->name = $title->phrase_id;

				$desc=new OmmuSystemPhrase;
				$desc->location = $location.'_description';
				$desc->en_us = $this->description;
				if($desc->save())
					$this->desc = $desc->phrase_id;
				
			} else {
				$title = OmmuSystemPhrase::model()->findByPk($this->name);
				$title->en_us = $this->title;
				$title->save();

				$desc = OmmuSystemPhrase::model()->findByPk($this->desc);
				$desc->en_us = $this->description;
				$desc->save();
			}
		}
		return true;
	}

}
