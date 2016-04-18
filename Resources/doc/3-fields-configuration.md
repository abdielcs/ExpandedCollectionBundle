Fields to render
================

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

Also you can configure custom labels for the list headers:

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



