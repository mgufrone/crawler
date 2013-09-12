<?php

/**
 * This is the model class for table "data_pattern".
 *
 * The followings are the available columns in table 'data_pattern':
 * @property integer $pattern_id
 * @property integer $site_id
 * @property string $pattern_name
 * @property string $pattern_value
 */
class DataPattern extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	const TYPE_REGEX='regex';
	const TYPE_SELECTOR='selector';
	public function tableName()
	{
		return 'data_pattern';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('site_id', 'numerical', 'integerOnly'=>true),
			array('pattern_name', 'length', 'max'=>50),
			array('pattern_value, pattern_type', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('pattern_id, site_id, pattern_name, pattern_value', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'pattern_id' => 'Pattern',
			'site_id' => 'Site',
			'pattern_name' => 'Pattern Name',
			'pattern_value' => 'Pattern Value',
		);
	}
	public function getTypes()
	{
		return array(
			self::TYPE_REGEX=>'regex',
			self::TYPE_SELECTOR=>'selector',
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

		$criteria->compare('pattern_id',$this->pattern_id);
		$criteria->compare('site_id',$this->site_id);
		$criteria->compare('pattern_name',$this->pattern_name,true);
		$criteria->compare('pattern_value',$this->pattern_value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * @return CDbConnection the database connection used for this class
	 */
	public function getDbConnection()
	{
		return Yii::app()->dbTest;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DataPattern the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
