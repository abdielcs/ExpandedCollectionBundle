Collection of entities as an expanded list
==========================================

Let's say that we need to create some shipment entity, and in the same step
choose wich product to send. If we also need to show for each product more
than the name, then the expanded native option of symfony form would'n be
enougth. For that situation you can use the 'expanded_otm' form.

An example of product entity:

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Product
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Product
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="decimal")
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expiration", type="datetime")
     */
    private $expiration;

    /**
     * @var boolean
     *
     * @ORM\Column(name="expired", type="boolean")
     */
    protected $expired;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Shipment", inversedBy="products")
     * @ORM\JoinColumn(name="id_shipment", referencedColumnName="id")
     */
    private $shipment;


    // ...

    /**
     * Set shipment
     *
     * @param \AppBundle\Entity\Shipment $shipment
     * @return Product
     */
    public function setShipment(\AppBundle\Entity\Shipment $shipment = null)
    {
        $this->shipment = $shipment;

        return $this;
    }

    /**
     * Get shipment
     *
     * @return \AppBundle\Entity\Shipment
     */
    public function getShipment()
    {
        return $this->shipment;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
```

And the shippment entity:

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Shipment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Shipment
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="Product", mappedBy="shipment", cascade={"all"})
     */
    private $products;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
    }

    // ...

    /**
     * Add products
     *
     * @param \AppBundle\Entity\Product $products
     * @return Shipment
     */
    public function addProduct(\AppBundle\Entity\Product $products)
    {
        $this->products[] = $products;

        return $this;
    }

    /**
     * Remove products
     *
     * @param \AppBundle\Entity\Product $products
     */
    public function removeProduct(\AppBundle\Entity\Product $products)
    {
        $this->products->removeElement($products);
    }

    /**
     * Get products
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }
}
```

With that in place them we can create the form type.

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
            ))
        ;
    }

    // ...
}
```

For get the expected view also we need to set a form theme in the show and edit views:

```twig
{% extends '::base.html.twig' %}

{% form_theme form '@ExpandedCollection/Form/fields.html.twig' %}

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

And that's all, the collection will be rendered as a checkbox list.

Important: You could consider including the option 'by_reference' => false in the form
and the next code in the Shipment class in order to handle the product's shipment reference.

```php
/**
  * Add products
  *
  * @param \AppBundle\Entity\Product $products
  * @return Shipment
  */
  public function addProduct(\AppBundle\Entity\Product $products)
  {
      $products->setShipment($this);  //include this line

      $this->products[] = $products;

      return $this;
  }
```