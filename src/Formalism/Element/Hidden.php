<?php
class Formalism_Element_Hidden
    extends Formalism_Element
    implements Formalism_ElementInterface
{
    public function getHtml(Formalism_Field $field)
    {
        if ($field) {
            $name  = " name=\"{$this->_h($field->getName())}\"";
            $value = " value=\"{$this->_h($field->getValue())}\"";
        } else {
            $name = $value = '';
        }
        return "<input type=\"hidden\"{$name}{$value} />";
    }
}
