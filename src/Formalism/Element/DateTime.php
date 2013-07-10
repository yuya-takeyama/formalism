<?php
class Formalism_Element_DateTime
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
        $html .= $this->_toSelect("{$name}[hour]", range(0, 23), 2, $time['hour']) . '時';
        $html .= $this->_toSelect("{$name}[minute]", array(0, 5, 10, 15, 20, 25, 30, 35, 40, 45, 50, 55), 2, $time['minute']) . '分';
        return $html;
    }

    public function parse($value)
    {
        if (is_array($value)) {
            return sprintf(
                '%04d-%02d-%02d %02d:%02d:00',
                (int)$value['year'], (int)$value['month'], (int)$value['day'],
                (int)$value['hour'], (int)$value['minute']
            );
        } else {
            return $value;
        }
    }

    protected function _getDefault()
    {
        return date('Y-m-d H:i:00');
    }

    protected function _toHash($datetime)
    {
        if (preg_match('/^(\d{4})\-(\d{2})\-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/u', $datetime, $matches)) {
            return array(
                'year'   => (int)$matches[1],
                'month'  => (int)$matches[2],
                'day'    => (int)$matches[3],
                'hour'   => (int)$matches[4],
                'minute' => (int)$matches[5],
                'second' => (int)$matches[6],
            );
        }
    }
}
