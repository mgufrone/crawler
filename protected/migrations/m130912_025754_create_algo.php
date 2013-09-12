<?php

class m130912_025754_create_algo extends CDbMigration
{
	public function up()
	{
		$this->createTable('data_pattern', array(
			'pattern_id'=>'pk',
			'site_id'=>'int(10)',
			'pattern_name'=>'varchar(50)',
			'pattern_value'=>'text',
		));
	}

	public function down()
	{
		$this->dropTable('data_pattern');
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}