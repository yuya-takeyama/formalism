<?php
class Formalism_Element_Date
    extends Formalism_Element
    implements Formalism_ElementInterface
{
    public function getHtml(Formalism_Field $field)
    {
        $name = $this->_getName($field);
        $time = $this->_toHash($this->_getValue($field));
        $html = $this->_toSelect("{$name}[year]", range(2000, 2040), 4, $time['year']) . '年';
        $html .= $this->_toSelect("{$name}[month]", range(1, 12), 2, $time['month']) . '月';
        $html .= $this->_toSelect("{$name}[day]", range(1, 31), 2, $time['day']) . '日';
        return $html;
    }

    public function parse($value)
    {
        if (is_array($value)) {
            return sprintf('%04d-%02d-%02d', (int)$value['year'], (int)$value['month'], (int)$value['day']);
        } else {
            return $value;
        }
    }

    protected function _getName($field)
    {
        return $field ? $field->getName() : NULL;
    }

    protected function _getValue($field)
    {
        return $field ? $field->getValue() : $this->_getDefault();
    }

    protected function _getDefault()
    {
        return date('Y-m-d');
    }

    protected function _toSelect($name, $values, $padLength, $currentValue)
    {
        $html = "<select name=\"{$name}\">";
        foreach ($values as $value) {
            if ((int)$value === (int)$currentValue) {
                $selected = ' selected="selected"';
            } else {
                $selected = '';
            }
            $html .= "<option value=\"{$value}\"{$selected}>{$this->_pad($value, $padLength)}</option>";
        }
        $html .= '</select>';
        return $html;
    }

    protected function _pad($str, $padLength)
    {
        return str_pad($str, $padLength, '0', STR_PAD_LEFT);
    }

    protected function _toHash($date)
    {
        if (preg_match('/^(\d{4})\-(\d{2})\-(\d{2})$/u', $date, $matches)) {
            return array(
                'year'   => (int)$matches[1],
                'month'  => (int)$matches[2],
                'day'    => (int)$matches[3],
            );
        }
    }
}
