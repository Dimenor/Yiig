<?php 

/**
 * @author Felipe Correia Brito
 * 
 * File that define the custom filters to use with Yiig.
 * You can overwrite this file if you needed.
 * Note: I use the Y{Class}::{staticmethod} pattern to avoid errors on duplicated names, since the files will be 
 * imported on the same container. This is not mandatory but i recommend.
 * You can use simple functions notation if you want
 */ 

class YFilters {
	public static function yiig_hello($string){
		return "Yiig Hello to var ".$string;
	}
}