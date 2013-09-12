<?php

use Symfony\Component\DomCrawler\Crawler;
class TaskController extends Controller
{
	public function actionIndex()
	{
		$data = array();
		if(Yii::app()->request->isAjaxRequest)
		{
			$command = Yii::app()->db->createCommand();
			$resources = $command
				->select(array(
					'site_name',
					'site_url',
					'site_id',
					'(SELECT COUNT(*) FROm urls WHERE urls.site_id=sites.site_id) as totalCount ',
					'(SELECT COUNT(*) FROM urls WHERE urls.site_id=sites.site_id AND url_crawled=\'1\') as totalCrawled'))
				->from('sites')
				->queryAll();
			$countResources = count($resources);

			$resources = $command->limit($_GET['iDisplayLength'], $_GET['iDisplayStart']*$_GET['iDisplayStart'])->queryAll();
			$response = array();
			foreach($resources as $resource)
			{
				$response[] = array(
					$resource['site_name'],
					$resource['site_url'],
					$resource['totalCount'],
					$resource['totalCrawled'],
					'',
				);
			}
			return print json_encode(array(
				'sEcho'=>intval($_GET['sEcho']),
				"iTotalRecords" => $countResources,
				"iTotalDisplayRecords" => count($resources),
				"aaData" => $response,
			));
		}
		$this->render('index', $data);
	}

	public function actionCreate()
	{
		$model = new Sites;
		$model->setScenario('create');
		if(!empty($_POST['Sites']))
		{
			if(!empty($_POST['ajax']))
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			else
			{
				$model->setAttributes($_POST['Sites']);
				if($model->validate() && $model->save())
				{
					return $this->redirect('index');
				}
			}
		}
		$data = array(
			'model'=>$model
		);
		$this->render('create',$data);
	}
	public function actionTest()
	{
		$content = Yii::app()->curl->get('http://indonetwork.co.id/starindo_smt/profile/starindo-mandiri-teknik.htm');
		// print $content;
		$crawler = new Crawler;
		$crawler->addContent($content);
		$filtered = $crawler->filter('.tbc');
		print_r($filtered->children());
		/*$formula = '/(?<email>([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+)/';
		print $formula;
		
			if(preg_match_all($formula, $content, $matched))
			{
			 print 'passed <br/>';
			 print_r($matched);
			}
		*/
	}
}