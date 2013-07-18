<?php
class Formalism_Errors implements Formalism_ErrorsInterface
{
    protected $_count = 0;

    protected $_messages = array();

    public function add($field, $message)
    {
        $this->_count++;
        if (array_key_exists($field, $this->_messages) === false) {
            $this->_messages[$field] = array();
        }
        $this->_messages[$field][] = $message;
    }

    public function count()
    {
        return $this->_count;
    }

    public function hasError($field)
    {
        return array_key_exists($field, $this->_messages) && count($this->_messages[$field]) > 0;
    }

    public function getMessages($field = NULL)
    {
        if ($field) {
            return $this->_messages[$field];
        } else {
            $result = array();
            foreach ($this->_messages as $key => $messages) {
                foreach ($messages as $message) {
                    $result[] = $message;
                }
            }
            return $result;
        }
    }
}
