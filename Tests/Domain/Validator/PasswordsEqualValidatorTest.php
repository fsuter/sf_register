<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2011 Sebastian Fischer <typo3@evoweb.de>
*  All rights reserved
*
*  This class is a backport of the corresponding class of FLOW3.
*  All credits go to the v5 team.
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

class Tx_SfRegister_Domain_Model_PasswordsEqualValidatorTest extends Tx_Extbase_BaseTestCase {
	/**
	 * @var Tx_SfRegister_Domain_Validator_PasswordsEqualValidator
	 */
	protected $fixture;

	/**
	 * @var array
	 */
	protected $postBackup = null;

	public function setUp() {
		$this->postBackup = $_POST;

		$this->fixture = $this->getAccessibleMock('Tx_SfRegister_Domain_Validator_PasswordsEqualValidator', array('dummy'));
	}

	public function tearDown() {
		unset($this->fixture);

		$_POST = $this->postBackup;
	}

	/**
	 * @test
	 */
	public function setFieldnameReallySetsName() {
		$expected = 'fieldname';

		$this->fixture->setFieldname($expected);

		$this->assertSame(
			$expected,
			$this->fixture->_get('fieldname')
		);
	}

	/**
	 * @test
	 */
	public function getPasswordFromRequestReturnsStringFromPasswordField() {
		$_POST['tx_sfregister_form']['password'] = array(
			'password' => 'testPassword',
			'passwordAgain' => 'testPassword',
		);

		$this->fixture->setFieldname('passwordAgain');

		$this->assertSame(
			'testPassword',
			$this->fixture->_call('getPasswordFromRequest')
		);
	}

	/**
	 * @test
	 */
	public function getPasswordFromRequestReturnsDifferentStringFromPasswordField() {
		$_POST['tx_sfregister_form']['password'] = array(
			'password' => 'testPasswordDifferent',
			'passwordAgain' => 'testPassword',
		);

		$this->fixture->setFieldname('passwordAgain');

		$this->assertNotSame(
			'testPassword',
			$this->fixture->_call('getPasswordFromRequest')
		);
	}

	/**
	 * @test
	 */
	public function getPasswordFromRequestReturnsEmptyStringIfUnexpectedFormPresent() {
		$_POST['tx_sfregister_form']['passwort'] = array(
			'password' => 'testPasswordDifferent',
			'passwordAgain' => 'testPassword',
		);

		$this->fixture->setFieldname('passwordAgain');

		$this->assertSame(
			'',
			$this->fixture->_call('getPasswordFromRequest')
		);
	}

	/**
	 * @test
	 */
	public function isValidReturnsTrueWithBothPasswordsEqual() {
		$_POST['tx_sfregister_form']['password'] = array(
			'password' => 'testPassword',
			'passwordAgain' => 'testPassword',
		);

		$this->fixture->setFieldname('passwordAgain');

		$this->assertTrue(
			$this->fixture->isValid('testPassword')
		);
	}

	/**
	 * @test
	 */
	public function isValidReturnsFalseWithOnePasswordDifferent() {
		$_POST['tx_sfregister_form']['password'] = array(
			'password' => 'testPasswordNotSame',
			'passwordAgain' => 'testPassword',
		);

		$this->fixture->setFieldname('passwordAgain');

		$this->assertFalse(
			$this->fixture->isValid('testPassword')
		);
	}
}

?>