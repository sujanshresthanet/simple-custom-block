# Create a custom block for drupal 9


Blocks in Drupal are instances of the block [plugin](https://www.drupal.org/developing/api/8/plugins "plugin api").

The Drupal [block manager](https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Block%21BlockManager.php/class/BlockManager/8 "BlockManger") scans your modules for any classes that contain a @Block [Annotation](https://www.drupal.org/node/1882526).

### [](#s-defining-a-new-block-plugin "Permalink to this headline")Defining a new block plugin

The example snippet below makes use of the @Block annotation along with the properties "id" and "admin\_label" to define a custom block.

Create the file `src/Plugin/Block/SimpleCustomBlock.php` within the [module skeleton created earlier](https://www.drupal.org/docs/creating-custom-modules/prepare-a-module-skeleton) and add the code below.

```php
<?php

namespace Drupal\simple_custom_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a Provides a simple custom block.
 *
 * @Block(
 *   id = "simple_custom_block",
 *   admin_label = @Translation("Simple Custom Block"),
 *   category = @Translation("Custom Block"),
 * )
 */
class SimpleCustomBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#markup' => $this->t('Hello, World!'),
    ];
  }

}
```

Note: After creating this file, clear the Drupal site cache in order to recognize this new class.

To add 'Hello block' you can go to Structure -> Block Layout (`admin/structure/block`) and click on 'Place Block' button associated with each available region.

Clicking on 'Place Block' button for any given region a "Place Block" dialogue pop-up will appear, with a listing of all available blocks. To quickly find your block, simply use 'Filter by block name' option or use mouse-scroll to locate 'Hello block'. This way you can add any number of instances of the custom block anywhere on your site.

### [](#s-troubleshooting "Permalink to this headline")Troubleshooting 

*   The class name and the file name must be the same (`class SimpleCustomBlock` and `src/Plugin/Block/SimpleCustomBlock.php`). If the class name is different, the block will appear in the list of available blocks, however, you will not be able to add it.

*   Be sure to double check all paths and filenames. Your .php file must be in the correctly labeled directory (`src/Plugin/Block/`), otherwise it won't be discovered by Drupal.

*   If your block fails to place in a region with no error on screen or in watchdog, check the PHP / Apache error logs.
*   If your block is not on the list, be sure to rebuild the Drupal caches (e.g. `drush cr`).

*   Make sure your module's naming convention is all lowercase/underscores. Some users report blocks not showing for modules with camelCase naming convention. For example, myModule will never show defined blocks, while my\_module will. This was last verified against Drupal 8.8.1


### [](#s-note-using-twig-templates-with-custom-blocks "Permalink to this headline")Note: Using Twig templates with custom blocks

1.  Add a **hook\_theme** in your **.module** file. 
    Note: **Do not name the theming function like 'block\_\_...'. This will not pass any variables down to the twig templates.** Instead, you might use the module name as prefix.
2.  Use `'#theme'` in the render array in the build method and pass the variables on the same level as the `'#theme'` - `'#varname'`.
3.  Example of build() function in SimpleCustomBlock.php to use variables and print them into a twig file.

```php
  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'simple_custom_block',
      '#data' => ['age' => '31', 'DOB' => '2 May 2000'],
    ];
  }
```

_Module file: simple\_custom\_block.module_

```php
/**
 * Implements hook_theme().
 */
function simple_custom_block_theme() {
  return [
    'simple_custom_block' => [
      'variables' => [
        'data' => [],
      ],
    ],
  ];
}
```

_Template file: templates/custom-block.html.twig_

```php
{% for key,value in data %}
  <strong>{{ key }}:</strong> {{ value }}<br>
{% endfor%}
```

#### [](#s-an-additional-example "Permalink to this headline")An additional example:

_Example of build() function in SimpleCustomBlock._

```php
  /**
   * {@inheritdoc}
   */
  public function build() {
    $some_array = [
      0 => [
        'is_active' => 'active',
        'label' => 'lorem ipsum',
        'url' => 'http://google.com',
      ],
      1 => [
        'is_active' => 'inactive',
        'label' => 'lorem ipsum',
        'url' => 'http://amazon.com',
      ],
    ];

    return [
      '#theme' => 'simple_custom_block',
      '#active_tab' => 'some_string',
      '#body_text' => [
        '#markup' => 'some_html_string',
      ],
      '#tabs' => $some_array,
    ];
  }
```

_Module file: simple\_custom\_block.module_

```php
/**
 * Implements hook_theme().
 */
function simple_custom_block_theme($existing, $type, $theme, $path): array {
  return [
    'simple_custom_block' => [
      'variables' => [
        'active_tab' => NULL,
        'body_text' => NULL,
        'tabs' => [],
      ],
    ],
  ];
}
```

_Template file: templates/custom-block.html.twig_

```php
<div{{ attributes }}>

  <!-- tabs -->
  {% for key,value in tabs %}
    <a class="{{ value.is_active }}" href="{{ value.url }}">{{ value.label }}</a>
  {% endfor%}

  <!-- body -->
  {{ body_text }}
</div>
```
