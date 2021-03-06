<?php

/**
 * Form class
 */
class Form_Universal
{
  public $form_name;
  private $database_fields;
  private $form_fields_types;
  public $fields;
  private $result;
  private $row;

  function __construct($form_name,$fields = array())
  {
    $this->form_name = $form_name;
    foreach ($fields as $key => $value) {
    $this->fields[$key] = $value;
    }
    $this->fields_type();
    $this->create_sql_table();
    $this->check_database_columns();
    $sql = "SELECT * FROM `$this->form_name`";
    $this->result = conn()->query($sql);
    if (@$this->result->num_rows == 0) {
      $this->row = null;
    }
  }

  public function send()
  {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
      return 0;
    }
    if (!isset($_POST[$this->form_name])) {
      return 0;
    }
    unset($_POST[$this->form_name]);
    $sql = "INSERT INTO `$this->form_name` (";
    foreach ($this->fields as $field_name) {
      if (is_array($field_name)) {
        foreach ($field_name as $name) {
          $sql .= '`'.$name.'`,';
        }
      } else {
        $sql .= '`'.$field_name.'`,';
      }
    }
    if ($sql[strlen($sql)-1] == ',') $sql[strlen($sql)-1] = ' ';
    $sql .= ") VALUES (";
    foreach ($this->fields as $type => $field_name) {
      switch (true) {
        case ($type == 'checkbox'):
          if (is_array($field_name)) {
            foreach ($field_name as $name) {
              if (isset($_POST[$name])) $value = 1; else $value = 0;
              $sql .= "'$value',";
            }
          } else {
            if (isset($_POST[$field_name])) $value = 1; else $value = 0;
            $sql .= "'$value',";
          }
          break;
        case ($type == 'special'):
          if (is_array($field_name)) {
            foreach ($field_name as $name) {
              $sql .= "'".call_user_func($name)."',";
            }
          } else {
            $sql .= "'".call_user_func($field_name)."',";
          }
          break;
        case (strpos($type,'special-') !== false):
          if (is_array($field_name)) {
            foreach ($field_name as $name) {
              $sql .= "'".call_user_func($name)."',";
            }
          } else {
            $sql .= "'".call_user_func($field_name)."',";
          }
          break;
        default:
        if (is_array($field_name)) {
          foreach ($field_name as $name) {
            if (isset($_POST[$name])) {
              $sql .= "'$_POST[$name]',";
            }
          }
        } else {
          if (isset($_POST[$field_name])) {
            $sql .= "'$_POST[$field_name]',";
          }
        }
          break;
      }
    }
    $sql .= ")";
    if ($sql[strlen($sql)-2] == ',') $sql[strlen($sql)-2] = ' ';
    if (conn()->query($sql) === FALSE) {
      echo 'Error';
    }
  }

  public function create_sql_table()
  {
    $sql = "CREATE TABLE IF NOT EXISTS `$this->form_name`(
      `id` INT(6) UNSIGNED AUTO_INCREMENT,";
    foreach ($this->fields as $key => $value) {
      switch (true) {
        case ($key =='checkbox'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $sql .= "`$one_value` BOOLEAN DEFAULT '1', ";
            }
          else
            $sql .= "`$value` BOOLEAN DEFAULT '1', ";
          break;
        case ($key =='number'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $sql .= "`$one_value` INT(6) UNSIGNED NOT NULL DEFAULT '', ";
            }
          else
            $sql .= "`$value` INT(6) UNSIGNED NOT NULL DEFAULT '', ";
          break;
        case ($key =='image'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $sql .= "`$one_value` BLOB NOT NULL DEFAULT NULL, ";
            }
          else
            $sql .= "`$value` BLOB NOT NULL DEFAULT NULL, ";
          break;
        case ($key =='date'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $sql .= "`$one_value` datetime NOT NULL DEFAULT NULL, ";
            }
          else
            $sql .= "`$value` datetime NOT NULL DEFAULT NULL, ";
          break;
        case ($key == 'special'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $sql .= "`$one_value` TEXT NOT NULL DEFAULT '', ";
            }
          else
            $sql .= "`$value` TEXT NOT NULL DEFAULT '', ";
          break;
        case (strpos($key,'special-') !== false):
          $key = str_replace('special-','',$key);
          if (is_array($value))
            foreach ($value as $one_value) {
              $sql .= "`$one_value` $key DEFAULT NULL, ";
            }
          else
            $sql .= "`$value` $key DEFAULT NULL, ";
          break;
        default:
          if (is_array($value))
            foreach ($value as $one_value) {
              $sql .= "`$one_value` TEXT NOT NULL DEFAULT '', ";
            }
          else
            $sql .= "`$value` TEXT NOT NULL DEFAULT '', ";
          break;
      }
    }
    $sql .= "PRIMARY KEY  (`id`) )";
      if(conn()->query($sql) === TRUE) return 1;
      else return 0;
  }

  private function check_database_columns()
  {
    $sql = "select column_name, data_type from information_schema.columns where table_name = '$this->form_name'";
    $result = conn()->query($sql);
    while ($row = $result->fetch_assoc()) {
      $database_fields[$row['column_name']] = $row['data_type'];
    }

    foreach ($database_fields as $column_name => $data_type) {
      if (isset($this->form_fields_types[$column_name]) && $this->form_fields_types[$column_name] == $data_type) {
        unset($database_fields[$column_name]);
        unset($this->form_fields_types[$column_name]);
      }
    }

    if (count($database_fields) > 1) {
      foreach ($database_fields as $column_name => $data_type) {
        if ($column_name != 'id') {
          $sql = "ALTER TABLE $this->form_name DROP COLUMN ";
          $sql .= "`".$column_name.'`';
          conn()->query($sql);
        }
      }
    }
    if (count($this->form_fields_types) > 0) {
      foreach ($this->form_fields_types as $name => $data_type) {
        switch ($data_type) {
          case 'int':
            $sql = "`$name` INT(6) UNSIGNED DEFAULT NULL";
            $this->sql_send($sql);
            break;
          case 'tinyint':
            $sql = "`$name` BOOLEAN NOT NULL DEFAULT 1";
            $this->sql_send($sql);
            break;
          case 'blob':
            $sql = "`$name`  BLOB DEFAULT NULL";
            $this->sql_send($sql);
            break;
          case 'datetime':
            $sql = "`$name` datetime NOT NULL DEFAULT NULL";
            $this->sql_send($sql);
            break;
          case 'text':
            $sql = "`$name` TEXT NOT NULL DEFAULT ''";
            $this->sql_send($sql);
            break;
          default:
            $sql = "`$name` $data_type DEFAULT NULL";
            $this->sql_send($sql);
            break;
        }
      }
    }
  }

  private function sql_send($value)
  {
    $sql = "ALTER TABLE $this->form_name ADD $value";    
    conn()->query($sql);
  }

  private function fields_type()
  {
    foreach ($this->fields as $key => $value) {
      switch (true) {
        case ($key == 'checkbox'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $this->form_fields_types[$one_value] = 'tinyint';
            }
          else
            $this->form_fields_types[$value] = 'tinyint';
          break;
        case ($key == 'number'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $this->form_fields_types[$one_value] = 'int';
            }
          else
            $this->form_fields_types[$value] = 'int';
          break;
        case ($key == 'image'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $this->form_fields_types[$one_value] = 'blob';
            }
          else
            $this->form_fields_types[$value] = 'blob';
          break;
        case ($key == 'date'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $this->form_fields_types[$one_value] = 'datetime';
            }
          else
            $this->form_fields_types[$value] = 'datetime';
          break;
        case ($key == 'special'):
          if (is_array($value))
            foreach ($value as $one_value) {
              $this->form_fields_types[$one_value] = 'text';
            }
          else
            $this->form_fields_types[$value] = 'text';
          break;
        case (strpos($key,'special-') !== false):
          $key = str_replace('special-','',$key);
          if (is_array($value))
            foreach ($value as $one_value) {
              $this->form_fields_types[$one_value] = $key;
            }
          else
            $this->form_fields_types[$value] = $key;
          break;
        default:
          if (is_array($value))
            foreach ($value as $one_value) {
              $this->form_fields_types[$one_value] = 'text';
            }
          else
            $this->form_fields_types[$value] = 'text';
          break;
      }
    }
  }

  public function have_results()
  {
    if ($this->row = $this->result->fetch_assoc()) {
      return 1;
    } else {
      return 0;
    }
  }

  public function get_value($name)
  {
    if (isset($this->row[$name])) {
      return $this->row[$name];
    }
  }

  public function get_value_e($name)
  {
    if (isset($this->row[$name])) {
      echo $this->row[$name];
      return $this->row[$name];
    }
  }

  public function delete_row($delete_btn,$column = 'id',$id = null)
  {
    if (isset($_POST[$delete_btn])) {
      if (!is_null($id)) {
        $sql = "DELETE FROM `$form_name` WHERE `$column` = '$id'";
      } else {
        $sql = "DELETE FROM `$form_name` WHERE `$column` = '$_POST[$delete_btn]'";
      }
      if (conn()->query($sql) === TRUE) return 1; else return 0;
    }
  }
}
