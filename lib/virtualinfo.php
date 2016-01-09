<?php

namespace Konfirm;

/**
 *  Basic functionality to use
 *  @package    VirtualInfo
 *  @namespace  Konfirm
 *  @author     Rogier Spieker <rogier@konfirm.eu>
 */
class VirtualInfo {
	/**
	 *  Return the syntax for `public` notation (no-op method, exists for a consistent interface)
	 *  @name    pub
	 *  @access  public  (static)
	 *  @param   string  variable
	 *  @return  string  variable
	 */
	static public function pub($variable) {
		return $variable;
	}

	/**
	 *  Return the syntax for `protected` notation
	 *  @name    prot
	 *  @access  public  (static)
	 *  @param   string  variable
	 *  @return  string  annotated variable
	 */
	static public function prot($variable) {
		return static::_format($variable, '*');
	}

	/**
	 *  Return the syntax for `private` notation
	 *  @name    priv
	 *  @access  public  (static)
	 *  @param   string  variable
	 *  @return  string  annotated variable
	 */
	static public function priv($variable, $scope=null) {
		$class = empty($scope) || is_object($scope) ? get_class($scope) : $scope;

		return static::_format($variable, substr($class, strrpos($class, '\\') + 1));
	}

	/**
	 *  Convert the keys of an array using special key notation
	 *  @name    info
	 *  @access  public  (static)
	 *  @param   Array   key/value pairs
	 *  @return  Array   annotated key/value pairs
	 */
	static public function info(Array $pty) {
		return array_combine(array_map('static::_map', array_keys($pty)), array_values($pty));
	}

	/**
	 *  Apply the internal PHP type annotation
	 *  @name    _format
	 *  @access  protected  (static)
	 *  @param   string  variable
	 *  @param   string  marker
	 *  @return  string  annotated variable
	 */
	static protected function _format($variable, $marker) {
		return sprintf("\0%s\0%s", $marker, $variable);
	}

	/**
	 *  Map special syntax keys to their annotated counterparts
	 *  @name    _map
	 *  @access  protected  (static)
	 *  @param   string  key
	 *  @return  string annotated key
	 */
	static protected function _map($key) {
		return preg_replace_callback('/^(\*|@|(?:(?:pub(?:lic)?|priv(?:ate)?|prot(?:ect(?:ed)?)?):))?(.*)/', function($match) {
			switch ($match[1]) {
				case '@':
				case 'priv':
				case 'private':
					return static::priv($match[2]);

				case '*':
				case 'prot':
				case 'protect':
				case 'protected':
					return static::prot($match[2]);
			}

			return $match[2];
		}, $key);
	}

}
