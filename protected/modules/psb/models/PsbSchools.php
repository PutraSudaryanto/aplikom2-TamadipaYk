<?php
/**
 * PsbSchools * @author Putra Sudaryanto <putra.sudaryanto@gmail.com>
 * @copyright Copyright (c) 2014 Ommu Platform (ommu.co)
 * @link http://company.ommu.co
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
 * This is the model class for table "ommu_psb_schools".
 *
 * The followings are the available columns in table 'ommu_psb_schools':
 * @property string $school_id
 * @property string $school_name
 * @property string $school_address
 * @property string $school_phone
 * @property string $school_status
 * @property integer $registers
 *
 * The followings are the available model relations:
 * @property OmmuPsbRegisters[] $ommuPsbRegisters
 */
class PsbSchools extends CActiveRecord
{
	public $defaultColumns = array();

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PsbSchools the static model class
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
		return 'ommu_psb_schools';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('school_name', 'required', 'on'=>'schoolmaster, schoolmasterEdit'),
			array('school_address, school_phone, school_status', 'required', 'on'=>'schoolmasterEdit'),
			array('school_address, school_phone, school_status', 'required', 'on'=>'edit'),
			array('school_status, registers', 'numerical', 'integerOnly'=>true),
			array('school_name', 'length', 'max'=>64),
			array('school_phone', 'length', 'max'=>15),
			array('school_name, school_address, school_phone, school_status, registers', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('school_id, school_name, school_address, school_phone, school_status, registers', 'safe', 'on'=>'search'),
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
			'registers' => array(self::HAS_MANY, 'PsbRegisters', 'school_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'school_id' => 'School',
			'school_name' => 'School Name',
			'school_address' => 'School Address',
			'school_phone' => 'School Phone',
			'school_status' => 'School Status',
			'registers' => 'Registers',
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

		$criteria->compare('t.school_id',$this->school_id,true);
		$criteria->compare('t.school_name',$this->school_name,true);
		$criteria->compare('t.school_address',$this->school_address,true);
		$criteria->compare('t.school_phone',$this->school_phone,true);
		$criteria->compare('t.school_status',$this->school_status);
		$criteria->compare('t.registers',$this->registers);

		if(!isset($_GET['PsbSchools_sort']))
			$criteria->order = 'school_id DESC';

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
			//$this->defaultColumns[] = 'school_id';
			$this->defaultColumns[] = 'school_name';
			$this->defaultColumns[] = 'school_address';
			$this->defaultColumns[] = 'school_phone';
			$this->defaultColumns[] = 'school_status';
			$this->defaultColumns[] = 'registers';
		}

		return $this->defaultColumns;
	}

	/**
	 * Set default columns to display
	 */
	protected function afterConstruct() {
		if(count($this->defaultColumns) == 0) {
			$this->defaultColumns[] = array(
				'header' => 'No',
				'value' => '$this->grid->dataProvider->pagination->currentPage*$this->grid->dataProvider->pagination->pageSize + $row+1'
			);
			$this->defaultColumns[] = array(
				'name' => 'school_name',
				'value' => 'ucwords($data->school_name)',
			);
			$this->defaultColumns[] = 'school_address';
			$this->defaultColumns[] = 'school_phone';
			$this->defaultColumns[] = array(
				'name' => 'school_status',
				'value' => '$data->school_status == 1 ? "Negeri" : "Swasta"',
				'htmlOptions' => array(
					'class' => 'center',
				),
				'filter'=>array(
					1=>'Negeri',
					0=>'Swasta',
				),
				'type' => 'raw',
			);
			$this->defaultColumns[] = array(
				'header' => 'registers',
				'value' => 'CHtml::link($data->registers, Yii::app()->controller->createUrl("o/admin/manage",array("school"=>$data->school_id)))',
				'type' => 'raw',
			);
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
	public static function getSchool() {
		
		$criteria=new CDbCriteria;		
		$model = self::model()->findAll($criteria);

		$items = array();
		if($model != null) {
			foreach($model as $key => $val) {
				$items[$val->school_id] = $val->school_name;
			}
			return $items;
		} else {
			return false;
		}
	}
	
	/**
	 * before save attributes
	 */
	protected function beforeSave() {
		if(parent::beforeSave()) {
			$this->school_name = strtolower($this->school_name);
		}
		return true;
	}

}