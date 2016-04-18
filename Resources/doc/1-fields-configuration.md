Fields to render configuration
==============================

You can choose wich entity properties to show using the 'fields' option
in the form type configuration. The next form will show the properties
'name','code' and 'price' for the Product entity.

```php
  /**
    * @param FormBuilderInterface $builder
    * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('products','expanded_otm',array(
                'class'    => 'AppBundle\Entity\Product',
                'fields' => array('name','code','price'),
                // ...
            ))
        ;
    }
```

Configure headers label
-----------------------

Also you can configure custom labels for the list headers. If no label configured them will be
assumed in the 'property' option.

```php
  /**
    * @param FormBuilderInterface $builder
    * @param array $options
    */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('products','expanded_otm',array(
                'fields' => array(
                    array(
                        'label'=> 'Custom Name Label', //label for name
                        'property' => 'name'
                    ),
                    'code',
                    'price'
                ),
                // ...
            ))
        ;
    }
```

Not string properties
---------------------

There are some entity properties that can't be natively rendered as string in symfony. For those cases
we create the 'type' option. Also some of them came with useful configurations options.

### Dateable type

Set the 'type' option to 'dateable' for tell the bundle that this field should be rendered as a date.
Also you can specify some [date configuration options][1].

```php
->add('products','expanded_otm',array(
    'class'    => 'AppBundle\Entity\Product',
    'fields' => array(
        array(
            'property' => 'expiration',
            'type' => 'dateable',
            'format' => 'd/m/Y H:i:s'
        ),
    ),
))
```

### Numeric type

You can format some decimal types too according to its [format configuration][2]

```php
->add('products','expanded_otm',array(
    'class'    => 'AppBundle\Entity\Product',
    'fields' => array(
        array(
            'property' => 'price',
            'type' => 'numeric',
            'format' => '%.2f'
        ),
    ),
))
```

### Boolean type

```php
->add('products','expanded_otm',array(
    'class'    => 'AppBundle\Entity\Product',
    'fields' => array(
        array(
            'property' => 'expired',
            'type' => 'boolean',
            'options' => array(
                'true' => 'Yes',
                'false' => 'No'
            )
        ),
    ),
))
```

If not options true and false defined, then "True" and "False" strings will be assumed.

### Raw type

The raw type just apply the [twig raw filter][3] to the field.

Create your own types
---------------------

Create your own type is very easy. Depending of the defined 'type', the corresponding twig
template will be responsable for render the field property. You just need to create your
own template in app/Resources/ExpandedCollectionBundle/views/Form folder with the following
naming convention:

field_<type>.html.twig

So, if you need to create an 'image' type, them you must create yor custom
'app/Resources/ExpandedCollectionBundle/views/Form/field_image.html.twig'. In there you can
use the next variables:

   * field_property   : The name of the entity property.
   * field_type       : The 'type' option value setted by you.
   * value            : The value of the property for the target entity.
   * field_format     : The 'format' option if it's defined (optional).
   * field_options    : The 'options' option setted by you.

You can check some examples in the bundle views folder, wich can be overriden too for
be adapted to your needs.

Thanks
------

Some of the concepts used in this configuration options as other ideas, came from the
[javiereguiluz/easyadmin-bundle](https://github.com/javiereguiluz/EasyAdminBundle), so thanks to all it's collaborator for such
amazing bundle.

[1]: http://php.net/manual/en/function.date.php
[2]: http://php.net/manual/en/function.sprintf.php
[3]: http://twig.sensiolabs.org/doc/filters/raw.html