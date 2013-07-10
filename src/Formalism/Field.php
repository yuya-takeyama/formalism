<?php
class Formalism_Field
{
    /**
     * フィールド名.
     *
     * @var string
     */
    protected $_name;

    /**
     * フィールドが持つ値.
     *
     * @var mixed
     */
    protected $_value;

    /**
     * フィールドの表示名.
     *
     * @var string
     */
    protected $_displayName;

    /**
     * 入力必須か.
     *
     * @var bool
     */
    protected $_required;

    /**
     * デフォルト値.
     *
     * @var string
     */
    protected $_default;

    /**
     * 変更されたか.
     *
     * @var bool
     */
    protected $_changed;

    public function __construct($name, $options)
    {
        $this->_name        = $name;
        $this->_displayName = isset($options['display_name']) ? $options['display_name'] : str_replace('_', ' ', ucfirst($name));
        $this->_required    = isset($options['required'])     ? !!$options['required']   : false;
        $this->_default     = array_key_exists('default', $options) ? (string)$options['default'] : NULL;
        $this->_element     = isset($options['element'])      ? $options['element']      : NULL;
        $this->_changed     = false;
    }

    public function getName()
    {
        return $this->_name;
    }

    /**
     * 値のセット.
     *
     * @param  mixed $value
     * @return void
     */
    public function setValue($value)
    {
        $this->_value = isset($this->_element) ? $this->_element->parse($value) : $value;
    }

    public function overwriteValue($value)
    {
        $prevValue = $this->getValue();
        $this->setValue($value);
        $this->_changed = $prevValue !== $this->getValue();
    }

    public function isChanged()
    {
        return $this->_changed;
    }

    /**
     * 値の取得.
     *
     * @return mixed
     */
    public function getValue()
    {
        if (is_null($this->_value) && is_null($this->_default) === false) {
            return $this->_default;
        } else {
            return $this->_value;
        }
    }

    /**
     * 入力必須であるか.
     *
     * @return bool
     */
    public function isRequired()
    {
        return $this->_required;
    }

    /**
     * 表示名の取得.
     *
     * @return string
     */
    public function getDisplayName()
    {
        return $this->_displayName;

    }

    /**
     * フォーム要素に値を渡して HTML タグとして取得.
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->getElement()->getHtml($this);
    }

    public function getElement()
    {
        return $this->_element;
    }

    /**
     * 未入力時のエラーメッセージの取得.
     *
     * @return string
     */
    public function getNotFilledMessage()
    {
        return "{$this->getDisplayName()}が入力されていません";
    }
}
