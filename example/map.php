<?php

require('../lib/virtualinfo.php');

class Example {
	public function __debugInfo() {
		return Konfirm\VirtualInfo::info([
			'pub:foo1'       => 'public foo1',
			'public:foo2'    => 'public foo2',
			'pblc:foo3'      => 'public pblc:foo3',

			'*bar1'          => 'protected bar1',
			'prot:bar2'      => 'protected bar2',
			'protect:bar3'   => 'protected bar3',
			'protected:bar4' => 'protected bar4',
			'prtctd:bar5'    => 'public prtctd:bar4',

			'@baz1'          => 'private baz1',
			'priv:baz2'      => 'private baz2',
			'private:baz3'   => 'private baz3',
			'prvt:baz4'      => 'public prvt:baz4',
		]);
	}
}

var_dump(new Example());
