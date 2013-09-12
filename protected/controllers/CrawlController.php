<?php

use Symfony\Component\DomCrawler\Crawler;

class CrawlController extends Controller
{
	public function actionIndex()
	{
		$crawler = new Crawler;
		$model = new Sites;
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
		foreach($resources as $site)
		{
			if($site['totalCount'] == 0)
			{
				// print 'hello';
				$url = 'http://'.$site['site_url'];
				$content = Yii::app()->curl->get($url);
				$crawler->addContent($content);
				$links = $crawler->filter('a');
				$collectedLinks = array();
				$count=0;
				foreach($links as $link)
				{
					$path = strtolower($link->getAttribute('href'));
					$path = preg_replace('/\/$/','', $path);
					if(strpos($path, $site['site_url'])>0)
					{
						$countFirst = $command->reset()->select('COUNT(*) as count')
						->from('urls')
						->where('url_path=:path and site_id=:site_id',array(':site_id'=>$site['site_id'],':path'=>$path))
						->queryRow();
						if($countFirst['count'] <1)
						{
							$data = array(
								'url_path'=>$path,
								'url_title'=>'',
								'url_crawled'=>0,
								'site_id'=>$site['site_id'],
							);
							$command->reset()->insert('urls',$data);
							$count++;
						}	
					}
				}
				print 'Link Crawled for '.$site['site_name'].': '.$count;

			}
			else
			{
				$urls = $command->reset()
					->select(array('url_path','urls.site_id','url_id','site_url'))
					->from('urls')
					->leftJoin('sites','sites.site_id=urls.site_id')
					->where('urls.site_id=:site_id and url_crawled=:not_crawled',array(':site_id'=>$site['site_id'],':not_crawled'=>0))
					->limit(3)
					->queryAll();
					foreach($urls as $url)
					{
						$urlSource = $url['url_path'];

						$patterns = $command->reset()
						->select(array('pattern_name','pattern_value','pattern_id'))
						->from('data_pattern')
						->where('site_id=:site_id',array(':site_id'=>$url['site_id']))
						->queryAll();
						$registeredPatterns = array();
						$content = Yii::app()->curl->get($urlSource);
						foreach($patterns as $pattern)
						{
							preg_match_all($pattern['pattern_value'], $content, $matches);
							if(!empty($matches[$pattern['pattern_name']]))
							foreach($matches[$pattern['pattern_name']] as $match)
							{
								$countFirst = $command->reset()->select('COUNT(*) as count')
								->from('data')
								->where('data_value=:value and pattern_id=:pattern_id',array(':value'=>$match,':pattern_id'=>$pattern['pattern_id']))
								->queryRow();
								if($countFirst['count'] <1)
								{
									$data = array(
										'url_id'=>$url['url_id'],
										'data_value'=>$match,
										'pattern_id'=>$pattern['pattern_id'],
									);
									$command->reset()->insert('data',$data);
								}
							}
						}
						$crawler->addContent($content);
						$links = $crawler->filter('a');
						$collectedLinks = array();
						$count=0;
						foreach($links as $link)
						{
							$path = strtolower($link->getAttribute('href'));
							$path = preg_replace('/\/$/','', $path);
							if(strpos($path, $url['site_url'])>0)
							{
								$countFirst = $command->reset()->select('COUNT(*) as count')
								->from('urls')
								->where('url_path=:path and site_id=:site_id',array(':site_id'=>$url['site_id'],':path'=>$path))
								->queryRow();
								if($countFirst['count'] <1)
								{
									$data = array(
										'url_path'=>$path,
										'url_title'=>'',
										'url_crawled'=>0,
										'site_id'=>$url['site_id'],
									);
									$command->reset()->insert('urls',$data);
									$count++;
								}	
							}
						}

						$command->reset()->update('urls', array('url_crawled'=>1), 'url_id=:url_id', array(':url_id'=>$url['url_id']));

					}
				}
		} 
	}
}