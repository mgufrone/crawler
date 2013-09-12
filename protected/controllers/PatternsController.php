<?php
class PatternsController extends Controller
{
	public function actionIndex($id)
	{

		$data = array('createUrl'=>$this->createAbsoluteUrl('create',array('id'=>$id)));
		if(Yii::app()->request->isAjaxRequest)
		{
			$command = Yii::app()->db->createCommand();
			$resources = $command
				->select(array(
					'pattern_name',
					'pattern_value',
					'pattern_type',
					'pattern_id',
					'site_id'))
				->from('data_pattern')
				->where('site_id=:site_id',array(':site_id'=>$id))
				->queryAll();
			$countResources = count($resources);

			$resources = $command->limit($_GET['iDisplayLength'], $_GET['iDisplayStart']*$_GET['iDisplayStart'])->queryAll();
			$response = array();
			foreach($resources as $resource)
			{
				$id = $resource['pattern_id'];
				$type = $resource['pattern_type']==''?'regex':$resource['pattern_type'];
				$templates = "<div class='btn-group'>
					<a class='btn btn-primary' href='".$this->createAbsoluteUrl('update',array('id'=>$id))."'><i class='glyphicon glyphicon-pencil'></i></a>
					<a class='btn btn-danger' href='".$this->createAbsoluteUrl('delete',array('id'=>$id))."'><i class='glyphicon glyphicon-trash'></i></a>
				</div>";
				$response[] = array(
					$resource['pattern_name'],
					$type,
					$resource['pattern_value'],
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
	public function actionCreate($id)
	{

		$model = new DataPattern;
		$model->site_id = $id;
		$class = get_class($model);
		$model->setScenario('create');
		if(!empty($_POST[$class]))
		{
			if(!empty($_POST['ajax']))
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			else
			{
				$model->setAttributes($_POST[$class]);
				if($model->validate() && $model->save())
				{
					return $this->redirect($this->createAbsoluteUrl('index',array('id'=>$id)));
				}
			}
		}
		$data = array(
			'model'=>$model
		);
		$this->render('create',$data);
	}

	public function actionUpdate($id)
	{

		$model = DataPattern::model()->findByPk($id);
		$class = get_class($model);
		$model->setScenario('create');
		if(!empty($_POST[$class]))
		{
			if(!empty($_POST['ajax']))
			{
				echo CActiveForm::validate($model);
				Yii::app()->end();
			}
			else
			{
				$model->setAttributes($_POST[$class]);
				if($model->validate() && $model->save())
				{
					return $this->redirect($this->createAbsoluteUrl('index',array('id'=>$model->site_id)));
				}
			}
		}
		$data = array(
			'model'=>$model
		);
		$this->render('create',$data);
	}
}