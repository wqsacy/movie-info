<?php
	/**
	 *
	 * Created by Wangqs
	 * Date: 2020/11/28 1:59 上午
	 */

	namespace Wangqs\Movie;

	use QL\QueryList;

	/**
	 *  Imdb基类
	 * Created by Malcolm.
	 * Date: 2020/11/28  1:59 上午
	 */
	class Imdb
	{

		protected $html , $imdbId;

		public function __construct ( $imdbId ) {

			//补零
			$imdbId = str_pad( $imdbId , 7 , '0' , STR_PAD_LEFT );

			if ( !strpos( $imdbId , 'tt' ) !== false ) {
				$imdbId = 'tt' . $imdbId;
			}

			$this->html = self::getHtml("https://www.imdb.com/title/{$imdbId}/");

			$this->imdbId = $imdbId;

			return $this;
		}


		public function title () {
			return QueryList::html( $this->html )
			                ->find( '.title_wrapper>h1' )
			                ->text();
		}

		public function runtime () {
			$str = QueryList::html( $this->html )
			                ->find( '#titleDetails .txt-block time' )
			                ->text();

			preg_match_all( '/\d+/' , strval( $str ) , $arr );

			return strval( join( '' , $arr[0] ) );

		}

		public function rating () {
			return QueryList::html( $this->html )
			                ->find( '.ratingValue span[itemprop=ratingValue]' )
			                ->text();
		}

		public function storyline () {
			return QueryList::html( $this->html )
			                ->find( '#titleStoryLine .inline.canwrap:eq(0)' )
			                ->text();
		}

		public function year () {

			$time = QueryList::get( "https://www.imdb.com/title/{$this->imdbId}/releaseinfo?ref_=tt_dt_dt" )
			                 ->find( '.release-date-item__date:eq(0)' )
			                 ->text();

			return $time ? date( 'Y' , strtotime( $time ) ) : '';
		}


		public function photo () {
			$src = QueryList::html( $this->html )
			                ->find( '.poster a:eq(0) img' )
			                ->attr( 'src' );

			return $this->getFullImg( $src );
		}


		private function getFullImg ( $src ) {

			$ext = explode( '.' , $src );

			$ext = '.' . end( $ext );

			if(strpos( $src , '@' ) !== false){
				$url = explode( '@' , $src );

				$base = '';

				if ( is_array( $url ) ) {
					foreach ( $url as $key => $val ) {
						if ( !strpos( $val , $ext ) !== false ) {
							$base .= $val . '@';
						}
					}
				}
			}else{
				//如果没有@符号，则去除切图参数

				$url = trim(str_replace($ext,'',$src));

				$tmp = explode( '.' , $url );

				$del = '.' . end( $tmp );

				$base = trim(str_replace($del,'',$url));

			}

			return $base . $ext;
		}


		/**
		 * @author     :  Wangqs  2020/11/25
		 * @description:  获取国家
		 */
		public function country () {

			$range = '#titleDetails .txt-block';

			$rules = [
				'title' => [ 'h4' , 'text' ] ,
				'info'  => [ '' , 'html' ] ,
			];

			$country = QueryList::html( $this->html )
			                    ->rules( $rules )
			                    ->range( $range )
			                    ->query()
			                    ->getData()
			                    ->all();

			$list = [];

			if ( is_array( $country ) ) {
				foreach ( $country as $key => $val ) {
					if ( strpos( $val['title'] , 'Country:' ) !== false ) {

						$list = QueryList::html( $val['info'] )
						                 ->find( 'a' )
						                 ->texts()
						                 ->all();

					}
				}
			}

			return $list;
		}


		private static function getHtml ( $url ) {
			$ch = curl_init();
			curl_setopt( $ch , CURLOPT_URL , $url );
			curl_setopt( $ch , CURLOPT_FOLLOWLOCATION , true );
			curl_setopt( $ch , CURLOPT_AUTOREFERER , true );
			curl_setopt( $ch , CURLOPT_REFERER , $url );
			curl_setopt( $ch , CURLOPT_RETURNTRANSFER , true );

			//请求头
			curl_setopt($ch , CURLOPT_ENCODING , 'gzip,deflate'); //gzip解压
			curl_setopt($ch , CURLOPT_USERAGENT , "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/86.0.4240.198 Safari/537.36");

			$result = curl_exec( $ch );
			curl_close( $ch );
			return $result;
		}

	}