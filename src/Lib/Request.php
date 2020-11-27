<?php


	namespace Wangqs\Movie\Lib;

	use GuzzleHttp\Client;

	/**
	 *  请求基类
	 * Created by Malcolm.
	 * Date: 2020/11/27  12:26 上午
	 */
	class Request
	{

		public static function doRequest ( $url , $data , $method = 'POST' , $isShow = 2 ) {

			try {

				$client = new Client();

				$config = [
					'connect_timeout' => 5 ,
					'headers'         => [
						'user-agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36'
					] ,
					'timeout'         => 10
				];

				if ( $method == 'GET' ) {

					$response = $client->request( $method , $url , [
						'query' => $data ,
						$config
					] );

				} else {

					$response = $client->request( $method , $url , [ 'form_params' => $data , $config ] );

				}

				if ( $isShow == 1 ) {
					echo $response->getBody();

					exit();
				}

				$data = \GuzzleHttp\json_decode( $response->getBody() , true );

				if ( ($data && is_object( $data )) || (is_array( $data ) && !empty( $data )) ) {
					return $data;
				}

				return $response->getBody();

			} catch ( \Exception $e ) {
				throw new HttpException( $e->getMessage() , $e->getCode() , $e );
			}

		}

		/**
		 * @param string $url
		 * @param array  $data
		 * @param int    $isNeedBody
		 * @return void|string
		 * @throws InvalidArgumentException
		 * @throws HttpException
		 * @author     :  Wangqs  2020/11/27
		 * @description:  get方法
		 */
		public static function get ( string $url , $data = [] , $isNeedBody = 2 ) {

			if ( !$url ) {
				throw new InvalidArgumentException( 'Invalid Parameter url: ' . $url );
			}

			if ( !is_numeric( $isNeedBody ) ) {
				throw new InvalidArgumentException( 'Invalid Parameter isNeedBody: ' . $isNeedBody );
			}

			try {
				return self::doRequest( $url , $data , 'GET' , $isNeedBody );
			} catch ( \Exception $e ) {
				throw new HttpException( $e->getMessage() , $e->getCode() , $e );
			}
		}

		public static function post ( $url , $data = [] , $isNeedBody = 2 ) {

			if ( !$url ) {
				throw new InvalidArgumentException( 'Invalid Parameter url: ' . $url );
			}

			if ( !is_array( $data ) ) {
				throw new InvalidArgumentException( 'Invalid Parameter data: ' . $data );
			}

			if ( !is_numeric( $isNeedBody ) ) {
				throw new InvalidArgumentException( 'Invalid Parameter isNeedBody: ' . $isNeedBody );
			}

			try {
				return self::doRequest( $url , $data , 'POST' , $isNeedBody );
			} catch ( \Exception $e ) {
				throw new HttpException( $e->getMessage() , $e->getCode() , $e );
			}
		}

	}