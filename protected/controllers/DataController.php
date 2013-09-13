<?php

use Symfony\Component\DomCrawler\Crawler;
class DataController extends Controller
{
	public function actionIndex($id)
	{
		$data = array();
		$command = Yii::app()->db->createCommand();
		$resources = $command
			->select(array(
				'pattern_name',
				'pattern_id',
			))
			->from('data_pattern')
			->where('site_id=:site_id',array(':site_id'=>$id))
			->queryAll();
		$countResources = count($resources);
		$columns = array('Location');
		foreach($resources as $data)
		{
			$columns[$data['pattern_id']] = $data['pattern_name'];
		}
		$data['columns'] = $columns;
		if(Yii::app()->request->isAjaxRequest)
		{
			$command->reset();
			$resources = $command
				->select(array(
					'urls.url_id',
					'url_path',
					'site_id',
				))
				->from('urls')
				->join('data','data.url_id=urls.url_id')
				->where('site_id=:site_id',array(':site_id'=>$id))
				->queryAll();
			$countResources = count($resources);
			$resources = $command->reset()
			->select(array(
				'urls.url_id',
				'url_path',
				'site_id',
			))
			->from('urls')
			->join('data','data.url_id=urls.url_id')
			->where('site_id=:site_id',array(':site_id'=>$id))
			->limit($_GET['iDisplayLength'], intval($_GET['iDisplayStart']*$_GET['iDisplayLength']))->queryAll();
			$response = array();
			unset($columns[0]);
			foreach($resources as $resource)
			{
				$id = $resource['site_id'];
				$data = array($resource['url_path']);
				foreach($columns as $key=>$column)
				{
					$query = $command->reset()
					->select('*')
					->from('data')
					->where('url_id=:url_id and pattern_id=:pattern_id',array(':url_id'=>$resource['url_id'],':pattern_id'=>$key))
					->queryAll();
					$value = array();
					foreach($query as $row)
					{
						$value[] = $row['data_value'];
					}
					// print_r($query);
					$data[] = implode(',<br/>', $value);
				}
				$response[] = $data;
			}
			return print json_encode(array(
				'sEcho'=>intval($_GET['sEcho']),
				"iTotalRecords" => $countResources,
				"iTotalDisplayRecords" => $countResources,
				"aaData" => $response,
			));
		}
		$this->render('index', $data);
	}
}