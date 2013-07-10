<?php
class Formalism_Element_TextArea
    extends Formalism_Element
    implements Formalism_ElementInterface
{
    private $_rows;
    private $_cols;

    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->_rows = isset($options['rows']) ? $options['rows'] : NULL;
        $this->_cols = isset($options['cols']) ? $options['cols'] : NULL;
    }

    public function getHtml(Formalism_Field $field)
    {
        $rows = $this->_rows ? " rows=\"{$this->_h($this->_rows)}\"" : '';
        $cols = $this->_cols ? " cols=\"{$this->_h($this->_cols)}\"" : '';
        if ($field) {
            $name  = " name=\"{$this->_h($field->getName())}\"";
            $value = $this->_h($field->getValue());
        } else {
            $name = $value = '';
        }
        $html = "<textarea{$name}{$rows}{$cols}>{$value}</textarea>";
        return $html;
    }
}
