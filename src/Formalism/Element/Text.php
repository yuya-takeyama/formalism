<?php
class Formalism_Element_Text extends Formalism_Element
{
    private $_size;

    public function __construct($options = array())
    {
        parent::__construct($options);
        $this->_size = isset($options['size']) ? $options['size'] : NULL;
    }

    public function getHtml(Formalism_Field $field = NULL)
    {
        $size = $this->_size ? " size=\"{$this->_h($this->_size)}\"" : '';
        if ($field) {
            $name  = " name=\"{$this->_h($field->getName())}\"";
            $value = " value=\"{$this->_h($field->getValue())}\"";
        } else {
            $name = $value = '';
        }
        return "<input type=\"text\"{$name}{$value}{$size} />";
    }
}

