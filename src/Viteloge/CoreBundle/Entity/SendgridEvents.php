<?php

namespace Viteloge\CoreBundle\Entity {

    use Doctrine\ORM\Mapping as ORM;

    /**
     * SendgridEvents
     *
     * @ORM\Table(name="sendgrid_events")
     * @ORM\Entity(repositoryClass="Viteloge\CoreBundle\Repository\SendgridEventsRepository")
     */
    class SendgridEvents
    {
        /**
         * @var \DateTime
         *
         * @ORM\Column(name="submittedAt", type="datetime", nullable=false)
         */
        private $createdAt;

        /**
         * @var string
         *
         * @ORM\Column(name="data", type="text", nullable=false)
         */
        private $data;

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer")
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;



        /**
         * Set createdAt
         *
         * @param \DateTime $createdAt
         * @return SendgridEvents
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
         * @return SendgridEvents
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

