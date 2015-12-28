<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;
    use Symfony\Component\Validator\Constraints as Assert;

    /**
     * SendgridEvent
     *
     * @ORM\Table(name="sendgrid_events")
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\SendgridEventsRepository")
     */
    class SendgridEvent
    {
        /**
         * @var \DateTime
         *
         * @ORM\Column(name="submittedAt", type="datetime", nullable=false)
         * @Assert\DateTime()
         */
        protected $createdAt;

        /**
         * @var string
         *
         * @ORM\Column(name="data", type="text", nullable=false)
         */
        protected $data;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        protected $id;


        /**
         * Constructor
         */
        public function __construct() {
            $this->createdAt = new \DateTime('now');
        }


        /**
         * Set createdAt
         *
         * @param \DateTime $createdAt
         * @return SendgridEvent
         */
        public function setCreatedAt($createdAt)
        {
            $this->createdAt = $createdAt;

            return $this;
        }

        /**
         * Get createdAt
         *
         * @return \DateTime
         */
        public function getCreatedAt()
        {
            return $this->createdAt;
        }

        /**
         * Set data
         *
         * @param string $data
         * @return SendgridEvent
         */
        public function setData($data)
        {
            $this->data = $data;

            return $this;
        }

        /**
         * Get data
         *
         * @return string
         */
        public function getData()
        {
            return $this->data;
        }

        /**
         * Get id
         *
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }
    }


}
