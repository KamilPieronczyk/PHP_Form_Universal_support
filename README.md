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
