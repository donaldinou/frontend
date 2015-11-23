<?php

namespace Acreat\YakazBundle\Component\Element\What {

    use JMS\Serializer\Annotation as JMS;
    use Acreat\YakazBundle\Component\Element\What;

    /**
     * @JMS\ExclusionPolicy("none")
     */
    class Job extends What {

        /**
         * @JMS\SerializedName("jobtitle")
         * @JMS\Type("string")
         */
        protected $jobtitle;

        /**
         * @JMS\SerializedName("company")
         * @JMS\Type("string")
         */
        protected $company;

        /**
         * @JMS\SerializedName("salary")
         */
        protected $salary;

        /**
         * @JMS\SerializedName("contract")
         * @JMS\Type("string")
         */
        protected $contract;

        /**
         * @JMS\SerializedName("study")
         * @JMS\Type("string")
         */
        protected $study;

    }

}
