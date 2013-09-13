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
					'crawl_status',
					'(SELECT COUNT(*) FROm urls WHERE urls.site_id=sites.site_id) as totalCount ',
					'(SELECT COUNT(*) FROM urls WHERE urls.site_id=sites.site_id AND url_crawled=\'1\') as totalCrawled'))
				->from('sites')
				->queryAll();
			$countResources = count($resources);

			$resources = $command->limit($_GET['iDisplayLength'], $_GET['iDisplayStart']*$_GET['iDisplayStart'])->queryAll();
			$response = array();
			foreach($resources as $resource)
			{
				$id = $resource['site_id'];
				$active = $resource['crawl_status'];
				$templates = "<div class='btn-group'>
					<a class='btn btn-primary' href='".$this->createAbsoluteUrl('change',array('id'=>$id, 'status'=>$active==0?1:0))."'><i class='glyphicon glyphicon-cloud-".($active==0?'upload':'download')."'></i></a>
					<a class='btn btn-info' href='".$this->createAbsoluteUrl('patterns/index',array('id'=>$id))."'><i class='glyphicon glyphicon-pencil'></i></a>
					<a class='btn btn-danger' href='".$this->createAbsoluteUrl('delete',array('id'=>$id, 'status'=>$active==0?1:0))."'><i class='glyphicon glyphicon-trash'></i></a>
				</div>";
				$response[] = array(
					$resource['site_name'],
					$resource['site_url'],
					$resource['totalCount'],
					$resource['totalCrawled'],
					$templates,
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
					return $this->redirect($this->createAbsoluteUrl('index'));
				}
			}
		}
		$data = array(
			'model'=>$model
		);
		$this->render('create',$data);
	}
	public function actionChange($id, $status=0)
	{
		$model = Sites::model()->findByPk($id);
		$model->crawl_status = $status;
		$model->save();	
		return $this->redirect($this->createAbsoluteUrl('index'));
	}
	public function actionDelete($id)
	{
		$delete = Sites::model()->findByPk($id)->delete();
		return $this->redirect($this->createAbsoluteUrl('index'));
	}
	public function actionTest()
	{
		$content = Yii::app()->curl->get('http://www.rakuten.co.id/shop/rosaphora/product/200000002744787/');
		// $content = str_replace(array("\n","\r"),'',$content);
		// $newString = preg_replace( "/<([^> ]+)/", "strtolower('\\0')", $content);
		// print $content;
		$pattern = array(
			'pattern_name' => 'company_name',
			'pattern_value'=> '#ShopOverview table tbody tr:nth-of-type(1) td',
		);

		$crawler = new Crawler;
		$crawler->addContent($content);
		$filtered = $crawler->filter('a');
		$matches = array();
		$matches[$pattern['pattern_name']] = array();
		foreach($filtered as $filter)
		{
			print $filter->getAttribute('href')."<br/>";
		}
		// print_r($matches);
		// print $content;
		/*$formula = '/(\<span\>\<strong\>(.*(Telpon).*)\<\/strong\>|\<th\>(.*(Telpon).*)\<\/th\>\<td\>)(?<phone>([\s\-\d]+))\</i';
		print $formula;
		
			if(preg_match_all($formula, $content, $matched))
			{
			 print 'passed <br/>';
			 print_r($matched);
			}*/
		
	}
}