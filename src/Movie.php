<?php

	namespace Wangqs\Movie;

	use Wangqs\Movie\Lib\HttpException;
	use Wangqs\Movie\Lib\InvalidArgumentException;

	/**
	 *  service_facade
	 * Created by Malcolm.
	 * Date: 2020/11/26  10:00 下午
	 */
	class Movie
	{

		protected $keyword;

		protected $channel;

		protected $baseInfo , $originalInfo;

		protected $config , $returnsFormat;

		public function __construct ( $keyword , $config = [ 'channel' => 1 , 'returnsFormat' => 'array' ] ) {

			$this->keyword = $keyword;

			$this->config = $config;

			$this->channel = $config['channel'];

			$this->returnsFormat = $config['returnsFormat']?$config['returnsFormat']:'array';

			if ( !isset( $config['channel'] ) || !$config['channel'] ) {
				throw new InvalidArgumentException( 'Invalid Parameter config:channel: ' . $config );
			}

			if ( !isset( $config['baseUrl'] ) || !$config['baseUrl'] ) {
				throw new InvalidArgumentException( 'Invalid Parameter config:baseUrl: ' . $config );
			}

			if ( !isset( $config['apiKey'] ) || !$config['apiKey'] ) {
				throw new InvalidArgumentException( 'Invalid Parameter config:apiKey: ' . $config );
			}

			if ( !isset( $config['returnsFormat'] ) || !$config['returnsFormat'] ) {
				throw new InvalidArgumentException( 'Invalid Parameter config:returnsFormat: ' . $config );
			}


			try {
				$base = new Base( $keyword , $config );
				$this->originalInfo = $base->baseInfo();
				$this->baseInfo = self::format( $this->originalInfo );
			} catch ( \Exception $e ) {
				throw new HttpException( $e->getMessage() , $e->getCode() , $e );
			}

		}

		/**
		 * @author     :  Wangqs  2020/11/27
		 * @description:  原始信息
		 */
		public function original () {
			return $this->response( $this->originalInfo );
		}

		public function all () {
			return $this->response( $this->baseInfo );
		}

		public function title ( $detail = false ) {
			if ( !$detail ) {
				return $this->response( $this->baseInfo['title'] );
			}

			return $this->response( [
				'title'               => $this->baseInfo['title'] ,
				'title_card_subtitle' => $this->baseInfo['title_card_subtitle'] ,
				'original_title'      => $this->baseInfo['original_title'] ,
				'aka'                 => $this->baseInfo['aka'] ,
			] );
		}

		public function rating () {
			return $this->response( [
				'db_rating'   => $this->baseInfo['db_rating'] ,
				'imdb_rating' => $this->baseInfo['imdb_rating'] ,
			] );
		}

		public function year () {
			return $this->response( $this->baseInfo['year'] );
		}

		public function photo ( $detail = false ) {
			if ( !$detail ) {
				return $this->response( $this->originalInfo['cover_url'] );
			}

			return $this->response( $this->baseInfo['pic'] );
		}

		public function intro () {
			return $this->response( $this->baseInfo['intro'] );
		}

		public function genres () {
			return $this->response( $this->baseInfo['genres'] );
		}

		public function actors () {
			return $this->response( $this->baseInfo['actors'] );
		}

		public function directors () {
			return $this->response( $this->baseInfo['directors'] );
		}

		public function tags () {
			return $this->response( $this->baseInfo['tags'] );
		}

		public function runtime () {
			return $this->response( $this->baseInfo['runtime'] );
		}

		public function countries () {
			return $this->response( $this->baseInfo['countries'] );
		}


		private function response ( $data ) {
			if ( $this->returnsFormat == 'json' && is_array($data)) {
				return \GuzzleHttp\json_encode( $data );
			}

			return $data;
		}

		/**
		 * @author     :  Wangqs  2020/11/27
		 * @description:  数据格式化
		 */
		private static function format ( $data ) {

			$actors = [];

			if ( is_array( $data['actors'] ) ) {
				foreach ( $data['actors'] as $key => $val ) {
					$actors[] = [
						'db_actor_id' => $val['id'] ,
						'name'        => $val['name'] ,
						'roles'       => $val['roles'] ,
						'abstract'    => $val['abstract'] ,
						'cover_url'   => $val['cover_url'] ,
					];
				}
			}

			$directors = [];

			if ( is_array( $data['directors'] ) ) {
				foreach ( $data['directors'] as $key => $val ) {
					$directors[] = [
						'db_actor_id' => $val['id'] ,
						'name'        => $val['name'] ,
						'roles'       => $val['roles'] ,
						'abstract'    => $val['abstract'] ,
						'cover_url'   => $val['cover_url'] ,
					];
				}
			}

			$tags = [];

			if ( is_array( $data['tags'] ) ) {
				foreach ( $data['tags'] as $key => $val ) {
					$tags[] = $val['name'];
				}
			}

			return [
				'db_id'               => $data['db_id'] ,              //豆瓣ID
				'imdb_id'             => $data['imdb_id'] ,//imdb ID
				'title'               => $data['title'] ,//中文名称
				'title_card_subtitle' => $data['card_subtitle'] ,//中文名称关键词
				'original_title'      => $data['original_title'] ,//原名称 国产为空
				'aka'                 => $data['aka'] ,  //别名
				'db_rating'           => $data['douban_rating'] ,    //豆瓣评分
				'imdb_rating'         => $data['imdb_rating'] ,    //IMDB评分
				'pub_date'            => $data['pubdate'] ,//上映日期
				'year'                => $data['year'] ,//上映年份
				'pic'                 => $data['pic'] ,  //海报
				'intro'               => $data['intro'] , //简介
				'languages'           => $data['languages'] , //语种
				'genres'              => $data['intro'] , //分类
				'actors'              => $actors , //主演
				'directors'           => $directors , //导演
				'tags'                => $tags , //标签
				'runtime'             => $data['durations'] , //时长
				'countries'           => $data['countries'] , //国家
			];

		}


	}