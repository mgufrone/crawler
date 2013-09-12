<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/bootstrap';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();
	public $path;

	public function init()
	{
		$this->runMigrationTool();
		$this->registerAssets();
		$this->registerPath();
	}
	private function runMigrationTool() 
	{
	    $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
	    $runner = new CConsoleCommandRunner();
	    $runner->addCommands($commandPath);
	    $commandPath = Yii::getFrameworkPath() . DIRECTORY_SEPARATOR . 'cli' . DIRECTORY_SEPARATOR . 'commands';
	    $runner->addCommands($commandPath);
	    $args = array('yiic', 'migrate', '--interactive=0');
	    ob_start();
	    $runner->run($args);
	    htmlentities(ob_get_clean(), null, Yii::app()->charset);
	}
	public function registerAssets()
	{
		$app = Yii::app();
		$cs = $app->clientScript;
		// registering jquery package
		$cs->addPackage('jquery',array(
			'js'=>array('jquery.min.js'),
			'baseUrl'=>$app->createAbsoluteUrl('/statics/jquery')
		));

		// registering bootstrap package
		$cs->addPackage('bootstrap',array(
			'js'=>array('js/bootstrap.min.js'),
			'css'=>array('css/bootstrap.min.css','css/bootstrap-theme.min.css'),
			'baseUrl'=>$app->createAbsoluteUrl('/statics/bootstrap/dist'),
			'depends'=>array('jquery')
		));

		// registering dataTables package
		$cs->addPackage('dataTables',array(
			'js'=>array('js/jquery.dataTables.js'),
			'css'=>array('css/jquery.dataTables.css'),
			'baseUrl'=>$app->createAbsoluteUrl('/statics/datatables/media'),
			'depends'=>array('bootstrap')
		));

		$cs->addPackage('apps',array(
			'js'=>array('js/apps.js'),
			'css'=>array('css/apps.css'),
			'baseUrl'=>$app->createAbsoluteUrl('/statics/apps'),
			'depends'=>array('dataTables')
		))->registerPackage('apps');
		return;
	}
	public function registerPath()
	{
		$this->path = array(
			'task'=>array(
				'index'=>$this->createAbsoluteUrl('/task'),
				'create'=>$this->createAbsoluteUrl('/task/create'),
			),
		);
	}
}