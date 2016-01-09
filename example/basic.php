<?php

require('../lib/virtualinfo.php');

class Example {
	//  we only demonstrate manipulated dump results
	public function __debugInfo() {
		return [
			//  foo remains public
			'foo' => 'bar',
			//  an elaborate way to say 'public'
			Konfirm\VirtualInfo::pub('foo2') => 'bar',
			//  bar will be protected
			Konfirm\VirtualInfo::prot('bar') => 'baz',
			//  baz will be private
			Konfirm\VirtualInfo::priv('baz') => 'qux'
		];
	}
}

var_dump(new Example());
