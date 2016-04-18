Using Bootstrap 3 templates
===========================

In order to use the bootstrap 3 theme included with symfony you must do some adjustments.

```php
<?php

namespace AppBundle\Form;

// ...

class ShipmentType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('created')
            ->add('products','expanded_otm',array(
                'class'    => 'AppBundle\Entity\Product',
                'fields' => array('name','code','price'),
                'attr' => array(
                    'class' => 'table table-striped'
                ),
            ))
            ->add('customerShipments','expanded_mtm',array(
                'label' => 'Customers',
                'class'    => 'AppBundle\Entity\Customer',
                'middle_class' => 'AppBundle\Entity\CustomerShipment',
                'fields' => array('firstName','lastName','age','country'),
                'attr' => array(
                    'class' => 'table table-striped'
                ),
            ))
        ;
    }

    // ...
}
```

Since in bootstrap 3 theme the checkbox and it's label are rendered together in the widget, you must set an special
theme for expanded fields. It's important to do it only for the specific field in order to not
affect any other checkbox field.

```twig
{% extends '::base.html.twig' %}

{% form_theme form 'bootstrap_3_horizontal_layout.html.twig' %}

{% form_theme form.products with '@ExpandedCollection/Form/bootstrap_3_fields.html.twig' %}
{% form_theme form.customerShipments with '@ExpandedCollection/Form/bootstrap_3_fields.html.twig' %}

{% block body -%}
    <h1>Shipment creation</h1>

    {{ form(form) }}

        <ul class="record_actions">
    <li>
        <a href="{{ path('shipment') }}">
            Back to the list
        </a>
    </li>
</ul>
{% endblock %}
```




