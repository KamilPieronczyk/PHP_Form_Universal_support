# PHP_Form_Universal_support
## Usage
---
To create a new form object create:
```php
  $form = new Form_Universal ('form_name', array());
```
In array you should write a types of inputs and their names like this:
```php
  $form = new Form_Universal_Edit ('example',$_GET['id'], array(
    'text' => array('text','nick'),    
    'checkbox' => 'check'
  ));
```
Available types of inputs:
- text (every inputs wchich date can be saved as simple text)
- number
- checkbox
- image (Blob type in a database - file input)
- date
- special (Your own function supports this field)

Example used special field:
```php
  function nick() {
    if (isset($_POST['nick'])) {
      return $_POST['nick'];
    }
  }
  $form = new Form_Universal ('example', array(
    'text' => 'text',
    'special' => 'nick',
    'checkbox' => 'check'
  ));
  $form->send();
```

Form method must be a POST

Function to describe data from database:

```php
  <?php while($form->have_results()) :?>
    <tr>
      <td><?php $form->get_value_e('text') ?></td>
      <td><?php $form->get_value_e('nick') ?></td>
      <td><?php $form->get_value_e('check') ?></td>
    </tr>
  <?php endwhile; ?>
```
function **get_value_e** - written a data

function **get_value** - returned a data

function **send** - sending a data from form to a database

To edit a data from form is created class called **"Form_Universal_Edit"**

Example
```php
  $form = new Form_Universal_Edit ('example',$_GET['id'], array(
    'text' => 'text',
    'special' => 'nick',
    'checkbox' => 'check'
  ));
  $form->send();
```
Second parameter must be a row's id in a database

To delete rows from the database use class called **"Form_Universal_Delete"**
Arguments:
1. Form name
2. Field name with the id value
3. Name of column in a database (default 'id')

Example:
```php
$form_delete = new Form_Universal_Delete('example','id');
<?php while($form->have_results()) : ?>
<td><form method="post"><input type="hidden" name='id' value="<?php $form->get_value_e('id') ?>"><button type="submit" class="btn btn-danger">Delete</button></form></td>
<?php endwhile; ?>
```
