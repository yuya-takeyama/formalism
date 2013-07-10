<?php
class Formalism_Element_Select extends Formalism_Element
{
    protected $_list;

    public function __construct($options = array())
    {
        $this->_list = $options['list'];
    }

    public function getHtml(Formalism_Field $field = NULL)
    {
        if ($field) {
            $name  = " name=\"{$this->_h($field->getName())}\"";
            $value = $field->getValue();
        } else {
            $name  = '';
            $value = NULL;
        }
        $html = "<select{$name}>";
        foreach ($this->_list as $optionValue => $label) {
            if ((string)$optionValue === $value) {
                $selected = ' selected="selected"';
            } else {
                $selected = '';
            }
            $html .= "<option value=\"{$this->_h($optionValue)}\"{$selected}>{$this->_h($label)}</option>";
        }
        $html .= '</select>';
        return $html;
    }
}
