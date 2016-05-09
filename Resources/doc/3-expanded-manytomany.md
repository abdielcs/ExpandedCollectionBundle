Collection of entities in a ManyToMany relationship
===================================================

For the next example let's say that the shipment will be sent to several customers,
and also every customer could have several shipments. So we have a ManyToMany relationship.

The customer entity:

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Customer
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
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var integer
     *
     * @ORM\Column(name="age", type="integer")
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="country", type="string", length=255)
     */
    private $country;

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="CustomerShipment", mappedBy="customer", cascade={"all"})
     */
    private $customerShipments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->customerShipments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    // ...

    /**
     * Add customerShipments
     *
     * @param \AppBundle\Entity\CustomerShipment $customerShipments
     * @return Customer
     */
    public function addCustomerShipment(\AppBundle\Entity\CustomerShipment $customerShipments)
    {
        $this->customerShipments[] = $customerShipments;

        return $this;
    }

    /**
     * Remove customerShipments
     *
     * @param \AppBundle\Entity\CustomerShipment $customerShipments
     */
    public function removeCustomerShipment(\AppBundle\Entity\CustomerShipment $customerShipments)
    {
        $this->customerShipments->removeElement($customerShipments);
    }

    /**
     * Get customerShipments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerShipments()
    {
        return $this->customerShipments;
    }

    public function __toString()
    {
        return $this->getFirstName();
    }
}
```

The CustomerShipment entity:

```php
<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CustomerShipment
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class CustomerShipment
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
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Customer", inversedBy="customerShipments")
     * @ORM\JoinColumn(name="id_customer", referencedColumnName="id")
     */
    private $customer;

    /**
     * @var \stdClass
     *
     * @ORM\ManyToOne(targetEntity="Shipment", inversedBy="customerShipments")
     * @ORM\JoinColumn(name="id_shipment", referencedColumnName="id")
     */
    private $shipment;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customer $customer
     * @return CustomerShipment
     */
    public function setCustomer(\AppBundle\Entity\Customer $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set shipment
     *
     * @param \AppBundle\Entity\Shipment $shipment
     * @return CustomerShipment
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
}
```

And the modified Shipment entity:

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
    // ...

    /**
     * @var array
     *
     * @ORM\OneToMany(targetEntity="CustomerShipment", mappedBy="shipment", cascade={"all"})
     */
    private $customerShipments;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->products = new \Doctrine\Common\Collections\ArrayCollection();
        $this->customerShipments = new \Doctrine\Common\Collections\ArrayCollection();
    }

    // ...

    /**
     * Add customerShipments
     *
     * @param \AppBundle\Entity\CustomerShipment $customerShipments
     * @return Shipment
     */
    public function addCustomerShipment(\AppBundle\Entity\CustomerShipment $customerShipments)
    {
        $this->customerShipments[] = $customerShipments;

        return $this;
    }

    /**
     * Remove customerShipments
     *
     * @param \AppBundle\Entity\CustomerShipment $customerShipments
     */
    public function removeCustomerShipment(\AppBundle\Entity\CustomerShipment $customerShipments)
    {
        $this->customerShipments->removeElement($customerShipments);
    }

    /**
     * Get customerShipments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCustomerShipments()
    {
        return $this->customerShipments;
    }
}
```

Now you have the option to use the 'expanded_otm' type as in the previous example, but then you
should handle the instance creation of the middle class CustomerShipment by your self. If you
want to let the bundle handle it for you, then you must use the special 'expanded_mtm' type.

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
            // ...
            ->add('customerShipments','expanded_mtm',array(
                'class'    => 'AppBundle\Entity\Customer',
                'middle_class' => 'AppBundle\Entity\CustomerShipment',
                'fields' => array('firstName','lastName','age','country'),
            ))
        ;
    }

    // ...
}
```

Important: You could consider including the next code in the Shipment class in order to handle
the customerShipments reference to shipment.

```php
/**
  * Add customerShipments
  *
  * @param \AppBundle\Entity\CustomerShipment $customerShipments
  * @return Shipment
  */
  public function addCustomerShipment(\AppBundle\Entity\CustomerShipment $customerShipments)
  {
      $customerShipments->setShipment($this);  //include this line

      $this->customerShipments[] = $customerShipments;

      return $this;
  }
```
