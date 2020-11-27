<?php

	namespace Wangqs\Movie\Lib;

	/**
	 *  常用方法汇总
	 * Created by Malcolm.
	 * Date: 2020/11/27  1:15 上午
	 */
	class Odin
	{

		/**
		 * @author     :  Wangqs  2020/11/27
		 * @description:  从URL内获得参数（例如ID）
		 */
		public static function urlToDbId ( string $url = '' ) {

			$url = explode( '/' , $url );

			if ( end( $url ) ) {
				return end( $url );
			}

			$count = count( $url );

			return $url[$count - 2];

		}

	}