<?php

class m130912_120345_alter_table extends CDbMigration
{
	public function up()
	{
		$this->addColumn('data_pattern', 'pattern_type', 'varchar(100)');
	}

	public function down()
	{
		$this->dropColumn('data_pattern', 'pattern_type');
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