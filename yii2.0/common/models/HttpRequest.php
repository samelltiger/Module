<?php
namespace common\models;

use Yii;


class HttpRequest 
{
	private $api = "http://";

	/**
	* 请求一个api
	* $data是以个关联数组，['key'=>'value']的格式
	*/
	public static function request( $url ,  $type = "GET", $data = [] ){
		if( !in_array($type, ['GET','POST','DELETE','PUT',])  )
			throw new Exception("参数错误！", 1);

		if( $type === "GET" ){
			$link = '/';
			$param = "?";
			if( !empty($data) ){
				$i = 0 ;
				foreach ($data as $key => $value) {
					if(!($i++))
						$link .= "$value";
					else{
						$param .= "$key="."$value&";
					}
				}
			}
			// var_dump($link);die;
			$param = strlen($param)>1 ? substr_replace($param, '', strlen($param)-1) : null;
            /*echo "http://".Yii::$app->params['apiAddress'].$url.$link.$param;exit;*/
			return file_get_contents("http://".Yii::$app->params['apiAddress'].$url.$param);
		}

		$requestOpt['method'] = $type ;
		$requestOpt['header'] =  'Content-type: application/x-www-form-urlencoded';

		$postdata = http_build_query( $data );
		$requestOpt['content'] = $postdata ;
		$opts = [ 'http'=> $requestOpt ];

		$context = stream_context_create($opts);
		return  file_get_contents( "http://".Yii::$app->params['apiAddress']. $url, false, $context );
	}	
}

?>