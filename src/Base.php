<?php

	namespace Wangqs\Movie;

	use QL\Ext\Baidu;
	use QL\QueryList;
	use Wangqs\Movie\Lib\HttpException;
	use Wangqs\Movie\Lib\Odin;
	use Wangqs\Movie\Lib\Request;
	use Wangqs\Movie\Lib\Translate;

	/**
	 *
	 * Created by Malcolm.
	 * Date: 2020/11/26  11:58 下午
	 */
	class Base
	{

		private $channel,$keyword;

		private $dbId , $dbRating;

		private $imdbId , $imdbRating;

		private $dbApiKey;

		private $dbBaseUrl;

		public function __construct ($keyword,$config) {

			$this->keyword = $keyword;

			$this->channel = $config['channel'];

			$this->dbApiKey = $config['apiKey'];

			$this->dbBaseUrl = $config['baseUrl'];

			if ( $this->channel == 2 ) {
				try {
					$this->dbId = self::byImdb( $keyword );
				} catch ( HttpException $e ) {
					throw new HttpException( $e->getMessage() , $e->getCode() , $e );
				}
			} else {
				$this->dbId = $keyword;
			}

			$Normal = self::getNormalByDb( $this->dbId );

			$this->imdbId = $Normal['imdb_id'];
			$this->imdbRating = $Normal['imdb_rating'];
			$this->dbId = $Normal['db_id'];
			$this->dbRating = $Normal['douban_rating'];
		}


		public function baseInfo () {

			//如果还是没有获取到豆瓣ID，则再次尝试
			if(!$this->dbId && $this->channel == 2){

				$this->dbId = self::getDbIdByBaidu( $this->keyword );

				if ( !$this->dbId || !is_numeric( $this->dbId ) ) {
					$this->dbId = self::getDbIdByApi( $this->keyword );
				}

			}

			if(!$this->dbId)
				return [];

			$baseInfo = Request::get( "{$this->dbBaseUrl}/movie/{$this->dbId}" , [
				'apiKey' => $this->dbApiKey
			] );

			if ( !$baseInfo || !is_array( $baseInfo ) || !isset( $baseInfo['id'] ) || !$baseInfo['id'] ) {
				throw new HttpException( 'Bad Request douban.com' , $baseInfo );
			}

			//判断如果豆瓣没有翻译或者没有对应的简介，则从imdb获取
			$isEnglish = preg_match("/^[a-zA-Z\s]+$/",$baseInfo['title']);

			$isNotNull = strlen(trim($baseInfo['intro']));

			if(!$baseInfo['title'] || $isEnglish || !$isNotNull){

				if(!$this->imdbId && $this->channel == 2){
					$this->imdbId = $this->keyword;
				}

				$imdb = new Imdb($this->imdbId);

				$baseInfo['original_title'] = $baseInfo['title'];

				$baseInfo['title'] = Translate::get($imdb->title());

				$baseInfo['intro'] = Translate::get($imdb->storyline());

			}

			$baseInfo['db_id'] = $this->dbId;
			$baseInfo['imdb_id'] = $this->imdbId;
			$baseInfo['imdb_rating'] = $this->imdbRating;
			$baseInfo['douban_rating'] = $this->dbRating;

			return $baseInfo;

		}


		/**
		 * @throws HttpException
		 * @author     :  Wangqs  2020/11/27
		 * @description:  从imdbID获取资料
		 */
		private function byImdb ( $keyword ) {

			//先查询百度，如果可以返回则直接使用
			$dbId = self::getDbIdByBaidu( $keyword );

			if ( !$dbId || !is_numeric( $dbId ) ) {
				$dbId = self::getDbIdByApi( $keyword );
			}

			if ( !$dbId ) {
				throw new HttpException( 'Failed To Get Correctly douban_id' );
			}

			$this->dbId = $dbId;

			return $dbId;
		}


		private static function getDbIdByApi ( $kw ) {
			$response = Request::get( 'https://api.rhilip.info/tool/movieinfo/gen' , [
				'search' => $kw ,
				'source' => 'douban' ,
			] );

			if ( $response && isset( $response['data'][0]['link'] ) && $response['data'][0]['link'] ) {
				return Odin::urlToDbId( $response['data'][0]['link'] );
			} else {
				return false;
			}
		}


		private static function getDbIdByBaidu ( $kw ) {
			$response = QueryList::getInstance()
			                     ->use( Baidu::class , 'baidu' )
			                     ->baidu( 1 )
			                     ->search( "site:movie.douban.com {$kw}" )
			                     ->page( 1 , true )
			                     ->all();

			if ( $response && isset( $response[0]['link'] ) && $response[0]['link'] ) {
				return Odin::urlToDbId( $response[0]['link'] );
			} else {
				return false;
			}
		}


		private static function getNormalByDb ( $dbId ) {

			$response = Request::get( 'https://movie.querydata.org/api' , [
				'id' => $dbId
			] );


			return [
				'imdb_id'       => isset( $response['imdbId'] ) ? $response['imdbId'] : '' ,
				'imdb_rating'   => isset( $response['imdbRating'] ) ? $response['imdbRating'] : 0 ,
				'db_id'         => isset( $response['doubanId'] ) ? $response['doubanId'] : '' ,
				'douban_rating' => isset( $response['doubanRating'] ) ? $response['doubanRating'] : '' ,
			];

		}

	}