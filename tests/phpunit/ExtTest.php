<?php

namespace STTLanguage\Test;

use MediaWiki\MediaWikiServices;
use STTLanguage\Ext as Ext;

/**
 * Tests for the STTLanguage\Ext class.
 *
 * @file
 * @since 0.1
 *
 * @ingroup STTLanguage
 * @ingroup Test
 *
 * @group STTLanguage
 *
 * @licence GNU GPL v2+
 * @author Daniel Werner
 * @covers \STTLanguage\Ext
 */
class ExtTest extends \MediaWikiIntegrationTestCase {
	/**
	 * @group STTLanguage
	 * @dataProvider providerGetUserLanguageCodes
	 */
	public function testGetUserLanguageCodes( $langs, $testMsg ) {
		// create dummy user for test:
		$user = new \User();

		// NOTE: have to get this BEFORE using setOption() on the user, otherwise setOption will leave all other
		//       options to null instead of getting the default options when calling getOption() afterwards!
		$userOptionsManager = MediaWikiServices::getInstance()->getUserOptionsManager();
		$defaultLang = $userOptionsManager->getOption( $user, 'language' );
		foreach( $langs as $code ) {
			$userOptionsManager->setOption( $user, "wb-languages-$code", 1 );
		}

		// users default lang expected to be returned always by getUserLanguageCodes()
		$langs[] = $defaultLang;
		$langs = array_unique( $langs );

		$result = Ext::getUserLanguageCodes( $user );
		$this->assertEquals(
			sort( $langs ),
			sort( $result ),
			$testMsg
		);
	}

	public function providerGetUserLanguageCodes() {
		return [
			[ [ 'fr', 'de', 'it' ], 'All languages the user set in his options should be returned' ],
			[ [], 'The users default language should be returned if the never touched his options' ],
		];
	}
}
