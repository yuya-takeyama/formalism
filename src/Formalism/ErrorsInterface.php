<?php
interface Formalism_ErrorsInterface extends Countable
{
    public function hasError($field);
}
