<?php
/**
 *
 */
class Form_Universal_Edit extends Form_Universal
{
  private $row_id;
  private $row_result;
  function __construct($form_name,$id,$fields = array())
  {
    $this->row_id = $id;
    $this->form_name = $form_name;
    if (count($fields)>0) {
      foreach ($fields as $key => $value) {
      $this->fields[$key] = $value;
      }
    }
    $sql = "SELECT * FROM `$this->form_name` WHERE id = '$this->row_id'";
    $result = conn()->query($sql);
    if (@$result->num_rows == 0) {
      echo 'Row with this id does not exist in a database';
      return 0;
    } else {
      $this->row_result = $result->fetch_assoc();
    }
  }

  public function send()
  {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      return 0;
    }
    foreach ($this->fields as $name => $value) {
      switch ($name) {
        case 'checkbox':
          if (is_array($value)) {
            foreach ($value as $one_value) {
              if (isset($_POST[$one_value])) $value1 = 1; else $value1 = 0;
              $this->update_value($one_value, $value1);
            }
          } else {
            if (isset($_POST[$value])) $value1 = 1; else $value1 = 0;
            $this->update_value($value, $value1);
          }
          break;
        case 'special':
          if (is_array($value)) {
            foreach ($value as $one_value) {
              $this->update_value($one_value, call_user_func($one_value));
            }
          } else {
            $this->update_value($value, call_user_func($value));
          }
          break;
        default:
          if (is_array($value)) {
            foreach ($value as $one_value) {
              $this->update_value($one_value);
            }
          } else {
            $this->update_value($value);
          }
          break;
      }
    }
  }

  private function update_value($name, $value = null)
  {
    if ($value == '' && isset($_POST[$name])) {
      $value = $_POST[$name];
    }

    if (is_null($value) && (!isset($_POST[$name]))) {
      return 0;
    }

    $sql = "UPDATE `$this->form_name` SET `$name` = '$value' WHERE id = '$this->row_id'";
    conn()->query($sql);
  }

  public function get_value($name)
  {
    if (isset($this->row_result[$name])) {
      return $this->row_result[$name];
    }
  }

  public function get_value_e($name)
  {
    if (isset($this->row_result[$name])) {
      echo $this->row_result[$name];
      return $this->row_result[$name];
    }
  }
}
