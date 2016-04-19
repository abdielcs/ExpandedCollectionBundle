Expanded Collection List
========================

<img src="https://raw.githubusercontent.com/abdielcs/ExpandedCollectionBundle/master/Resources/doc/images/expanded-checkbox-list.png" alt="Symfony expanded list"/>

Symfony 2 and 3 bundle for rendering a collection of entities as an expanded selectable list.
Include some usefull form types extending the native entity form field, so all entity options
could by used (like query_builder), except expanded and multiple, since all the bundle types are based in those
options setted to true. Read more about [entity type field](http://symfony.com/doc/current/reference/forms/types/entity.html).


**Features**

  * [Be able to configure wich entity properties or get method should be rendered and how.](Resources/doc/1-fields-configuration.md)
  * [Render a collection of entities as an expanded checkbox list.](Resources/doc/2-expanded-onetomany.md)
  * [Handling ManyToMany related collection as an expanded checkbox list.](Resources/doc/3-expanded-manytomany.md)
  * [Support for bootstrap 3 symfony theme.](Resources/doc/4-bootstrap-3-example.md)

Installation
------------

### Step 1: Download the Bundle

```bash
$ composer require abdielcs/expanded-collection-bundle
```

This command requires you to have Composer installed globally, as explained
in the [Composer documentation](https://getcomposer.org/doc/00-intro.md).

### Step 2: Enable the Bundle

```php
<?php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new abdielcs\ExpandedCollectionBundle\ExpandedCollectionBundle(),
        );
    }

    // ...
}
```

That's it!. Now you can start to use it.

License
-------

This software is published under the [MIT License](LICENSE.md)
