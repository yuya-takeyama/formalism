<?php
abstract class Formalism_Form implements Formalism_FormInterface
{
    const DEFAULT_ENCODING = 'UTF-8';

    /**
     * form タグの action 属性.
     *
     * @var string
     */
    private $_action;

    /**
     * リクエストメソッド.
     *
     * @var string
     */
    private $_method = 'post';

    /**
     * フォームが持つ入力フィールドの配列.
     *
     * @var array<Formalism_Field>
     */
    private $_fields = array();

    /**
     * エラーオブジェクト.
     *
     * @var Formalism_Errors
     */
    private $_errors = NULL;

    /**
     * 出力エンコーディング.
     *
     * @var string
     */
    private $_outputEncoding;

    public function __construct($options = array())
    {
        $this->_outputEncoding = isset($options['output_encoding']) ? $options['output_encoding'] :
                                                                      self::DEFAULT_ENCODING;
        $this->_inputEncoding = isset($options['input_encoding']) ? $options['input_encoding'] :
                                                                    self::DEFAULT_ENCODING;
        $this->configure();
    }

    abstract public function configure();

    public function setAction($action)
    {
        $this->_action = $action;
    }

    public function getAction()
    {
        return $this->_action;
    }

    public function getActionAsHtml()
    {
        return $this->encodeOutput($this->h($this->getAction()));
    }

    public function setMethod($method)
    {
        $this->_method = $method;
    }

    public function getMethod()
    {
        return $this->_method;
    }

    public function addField($name, $options = array())
    {
        $this->_fields[$name] = new Formalism_Field($name, $options);
    }

    public function getFields()
    {
        return $this->_fields;
    }

    public function getIterator()
    {
        return new ArrayIterator($this->_fields);
    }

    public function setValues($values, $overwrite = false)
    {
        foreach ($this->_fields as $name => $field) {
            if (array_key_exists($name, $values)) {
                if ($overwrite) {
                    $this->_fields[$name]->overwriteValue($this->encodeInput($values[$name]));
                } else {
                    $this->_fields[$name]->setValue($this->encodeInput($values[$name]));
                }
            }
        }
    }

    public function overwriteValues($values)
    {
        $this->setValues($values, true);
    }

    public function getValues()
    {
        $result = array();
        foreach ($this->_fields as $name => $field) {
            $result[$name] = $this->encodeOutput($field->getValue());
        }
        return $result;
    }

    public function getValue($name)
    {
        return $this->encodeOutput($this->_fields[$name]->getValue());
    }

    private function encodeInput($str)
    {
        if ($this->_inputEncoding !== self::DEFAULT_ENCODING) {
            return $this->encode($str, self::DEFAULT_ENCODING, $this->_inputEncoding);
        } else {
            return $str;
        }
    }

    private function encodeOutput($str)
    {
        if ($this->_outputEncoding !== self::DEFAULT_ENCODING) {
            return $this->encode($str, $this->_outputEncoding, self::DEFAULT_ENCODING);
        } else {
            return $str;
        }
    }

    private function encode($input, $to, $from)
    {
        if (is_array($input)) {
            foreach ($input as $key => $value) {
                $input[$key] = $this->encode($value, $to, $from);
            }
        } else if (is_string($input)) {
            $input = mb_convert_encoding($input, $to, $from);
        }
        return $input;
    }

    public function getErrors()
    {
        $this->validateOnce();
        return $this->_errors;
    }

    public function isValid()
    {
        $this->validateOnce();
        return count($this->_errors) === 0;
    }

    public function hasError()
    {
        return !$this->isValid();
    }

    public function offsetSet($key, $value)
    {
        throw new RuntimeException(__METHOD__ . ' is not allowed.');
    }

    public function offsetGet($key)
    {
        if (array_key_exists($key, $this->_fields) === false) {
            throw new Formalism_Exception_UndefinedFieldException("Field '{$key}' is not defined.");
        }
        return $this->_fields[$key];
    }

    public function offsetExists($key)
    {
        return isset($this->_fields[$key]);
    }

    public function offsetUnset($key)
    {
        throw new RuntimeException(__METHOD__ . ' is not allowed.');
    }

    public function getOutputEncoding()
    {
        return $this->_outputEncoding;
    }

    /**
     * 指定したフォーム要素の表示名を取得する.
     *
     * @param  string $name フォーム要素識別子.
     * @return string
     */
    public function getDisplayName($name)
    {
        return $this->encodeOutput($this[$name]->getDisplayName());
    }

    /**
     * 指定したフォーム要素を HTML タグとして取得する.
     *
     * @param  string $name フォーム要素識別子.
     * @return string
     */
    public function getHtml($name)
    {
        return $this->encodeOutput($this[$name]->getHtml());
    }

    public function getHtmlAsHidden($name)
    {
        return $this->encodeOutput("<input type=\"hidden\" name=\"{$name}\" value=\"{$this->h($this->_fields[$name]->getValue())}\" />");
    }

    private function validate()
    {
        $this->_errors = new Formalism_Errors;
        foreach ($this->_fields as $name => $field) {
            if ($field->isRequired()) {
                if ((string)$this->_fields[$name]->getValue() === '') {
                    $this->_errors->add($name, $field->getNotFilledMessage());
                }
            }
        }
    }

    private function validateOnce()
    {
        if (is_null($this->_errors)) {
            $this->validate();
        }
    }

    private function h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, self::DEFAULT_ENCODING);
    }
}
