<?php

class m130912_014538_init_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('sites',array(
			'site_id'=>'pk',
			'site_name'=>'varchar(100)',
			'site_url'=>'text',
		));

		$this->createTable('urls',array(
			'url_id'=>'pk',
			'site_id'=>'int(10)',
			'url_path'=>'text',
			'url_title'=>'text',
			'url_crawled'=>'int(1)',
		));

		$this->createTable('data',array(
			'data_id'=>'pk',
			'url_id'=>'int(10)',
			'pattern_id'=>'int(10)',
			'data_value'=>'text',
		));
	}

	public function down()
	{
		$this->dropTable('data');
		$this->dropTable('urls');
		$this->dropTable('sites');
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