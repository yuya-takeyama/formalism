<?php
class Formalism_Form_Fixture_Foo extends Formalism_Form
{
    public function configure()
    {
        $this->addField('required_field', array('required' => true));
        $this->addField('not_required_field', array('required' => false));
        $this->addField('named_field', array('display_name' => 'フィールド名'));
        $this->addField('select_field', array(
            'element' => new Formalism_Element_Select(array(
                'list' => array(
                    '1' => 'On',
                    '0' => 'Off',
                ),
            ))
        ));
        $this->addField('text_field', array(
            'element' => new Formalism_Element_Text,
        ));
    }
}

class Formalism_Tests_FormTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->form = new Formalism_Form_Fixture_Foo;
    }

    /**
     * @test
     */
    public function getErrors_入力必須のフィールドが空ならエラー()
    {
        $this->form->setValues(array());
        $errors = $this->form->getErrors();
        $this->assertTrue($errors->hasError('required_field'));
        $this->assertEquals(
            array('Required fieldが入力されていません'),
            $errors->getMessages('required_field')
        );
    }

    /**
     * @test
     */
    public function getErrors_入力必須のフィールドに入力されていればエラーでない()
    {
        $this->form->setValues(array('required_field' => 'foo'));
        $errors = $this->form->getErrors();
        $this->assertFalse($errors->hasError('required_field'));
    }

    /**
     * @test
     */
    public function offsetGet_Fieldオブエジェクトを取得する()
    {
        $this->assertInstanceOf('Formalism_Field', $this->form['required_field']);
    }

    /**
     * @test
     * @expectedException Formalism_Exception_UndefinedFieldException
     */
    public function offsetGet_定義していないフィールドを指定したら例外を投げる()
    {
        $foo = $this->form['undefined_field'];
    }

    /**
     * @test
     */
    public function addField_display_fieldオプションでフィールド名をセットする()
    {
        $this->assertEquals('フィールド名', $this->form['named_field']->getDisplayName());
    }

    /**
     * @test
     */
    public function getDisplayName_指定した出力エンコーディングでフィールド名を返す()
    {
        $form = new Formalism_Form_Fixture_Foo(array('output_encoding' => 'SJIS'));
        $this->assertEquals($this->_sjis('フィールド名'), $form->getDisplayName('named_field'));
    }

    /**
     * @test
     */
    public function getHtml_フォーム要素のHTMLを返す()
    {
        $expected = '<input type="text" name="text_field" value="" />';
        $form = new Formalism_Form_Fixture_Foo;
        $this->assertEquals($expected, $form->getHtml('text_field'));
    }

    /**
     * @test
     */
    public function getHtml_値をセットしているときはその値を持ったフォーム要素のHTMLを返す()
    {
        $expected = '<input type="text" name="text_field" value="あいうえお" />';
        $form = new Formalism_Form_Fixture_Foo;
        $form->setValues(array('text_field' => 'あいうえお'));
        $this->assertEquals($expected, $form->getHtml('text_field'));
    }

    /**
     * @test
     */
    public function getHtml_出力エンコーディングに変換して出力する()
    {
        $expected = $this->_sjis('<input type="text" name="text_field" value="あいうえお" />');
        $form = new Formalism_Form_Fixture_Foo(array('output_encoding' => 'sjis'));
        $form->setValues(array('text_field' => 'あいうえお'));
        $this->assertEquals($expected, $form->getHtml('text_field'));
    }

    /**
     * @test
     */
    public function getHtml_入力エンコーディングに従ってUTF8に変換して出力する()
    {
        $expected = '<input type="text" name="text_field" value="あいうえお" />';
        $form = new Formalism_Form_Fixture_Foo(array('input_encoding' => 'sjis'));
        $form->setValues(array('text_field' => $this->_sjis('あいうえお')));
        $this->assertEquals($expected, $form->getHtml('text_field'));
    }

    /**
     * @test
     */
    public function getHtml_入出力ともにエンコーディングが指定されている場合()
    {
        $expected = $this->_sjis('<input type="text" name="text_field" value="あいうえお" />');
        $form = new Formalism_Form_Fixture_Foo(array(
            'input_encoding'  => 'sjis',
            'output_encoding' => 'sjis',
        ));
        $form->setValues(array('text_field' => $this->_sjis('あいうえお')));
        $this->assertEquals($expected, $form->getHtml('text_field'));
    }

    protected function _enc($str, $encoding)
    {
        return mb_convert_encoding($str, $encoding, 'UTF-8');
    }

    protected function _sjis($str)
    {
        return $this->_enc($str, 'SJIS');
    }
}
