<?php
/**
 *
 */
class Form_Universal_Delete
{
  private $form_name;
  function __construct($form_name,$delete_btn,$column = 'id')
  {
    $this->form_name = $form_name;
    if (isset($_POST[$delete_btn])) {
      $sql = "DELETE FROM `$form_name` WHERE `$column` = '$_POST[$delete_btn]'";
      if (conn()->query($sql) === TRUE) return 1; else return 0;
    }
  }
}
