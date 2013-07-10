<?php
interface Formalism_FormInterface extends ArrayAccess, IteratorAggregate
{
    /**
     * Target action URL of the form.
     *
     * @return string
     */
    public function getAction();

    /**
     * Request method of the form.
     *
     * @return string
     */
    public function getMethod();

    /**
     * Sets the values to the form.
     *
     * @param  array|Traversable $values
     * @param  bool              $overwrite
     */
    public function setValues($values, $overwrite = false);

    /**
     * Overwrites the values of the form.
     *
     * @param  array|Traversable
     */
    public function overwriteValues($values);

    /**
     * @return array<Formalism_Form_Field>
     */
    public function getValues();

    /**
     * @return Formalism_ErrorsInterface
     */
    public function getErrors();

    /**
     * @return bool
     */
    public function isValid();
}
