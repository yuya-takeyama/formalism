<?php
abstract class Formalism_Element implements Formalism_ElementInterface
{
    public function __construct($options = array())
    {
    }

    protected function _h($str)
    {
        return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
    }

    public function parse($value)
    {
        return $value;
    }

    public function __toString()
    {
        return $this->getHtml();
    }
}
