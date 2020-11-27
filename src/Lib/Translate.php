<?php

	namespace Wangqs\Movie\Lib;

	use Stichoza\GoogleTranslate\GoogleTranslate;

	/**
	 *  翻译基类
	 * Created by Malcolm.
	 * Date: 2020/11/28  2:15 上午
	 */
	class Translate
	{
		public static function get ( $str ) {

			if ( is_array( $str ) ) {
				$str = implode( '^^' , $str );

				$isNeed = 1;
			} else {
				$isNeed = 2;
			}

			//尝试使用谷歌翻译
			try {
				$tr = new GoogleTranslate( 'zh' , null );

				$result = $tr->setUrl( 'http://translate.google.cn/translate_a/single' )
				             ->translate( $str );
			} catch ( \Exception $e ) {
				$result = '';
			}


			if ( $isNeed == 1 ) {
				$result = explode( '^^' , $result );
			}

			return $result;
		}

	}