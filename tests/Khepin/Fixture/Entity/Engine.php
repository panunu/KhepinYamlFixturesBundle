<?php

namespace Khepin\Fixture\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Engine
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $serialNumber;

    /**
     * @ORM\Column(type="datetime")
     */
    private $builtOn;

    /**
     * @param string    $serialNumber
     * @param \DateTime $builtOn
     */
    public function __construct($serialNumber, \DateTime $builtOn)
    {
        $this->serialNumber = $serialNumber;
        $this->builtOn      = $builtOn;
    }

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getSerialNumber()
    {
        return $this->serialNumber;
    }

    /**
     * @return \DateTime
     */
    public function getBuiltOn()
    {
        return $this->builtOn;
    }
}
