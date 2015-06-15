<?php

namespace Viteloge\EstimationBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Estimation
 *
 * @ORM\Table(name="estimations")
 * @ORM\Entity()
 */
class Estimation{

    public static $TYPES_VOIE = array(
        "ALL" => "Allée",
        "AV" => "Avenue",
        "BD" => "Boulevard",
        "CAR" => "Carrefour",
        "CHE" => "Chemin",
        "CHS" => "Chaussée",
        "CITE" => "Cité",
        "COR" => "Corniche",
        "CRS" => "Cours",
        "DOM" => "Domaine",
        "DSC" => "Descente",
        "ECA" => "Ecart",
        "ESP" => "Esplanade",
        "FG" => "Faubourg",
        "GR" => "Grande",
        "HAM" => "Hameau",
        "HLE" => "Halle",
        "IMP" => "Impasse",
        "LD" => "Lieu-dit",
        "LOT" => "Lotissement",
        "MAR" => "Marché",
        "MTE" => "Montée",
        "PAS" => "Passage",
        "PL" => "Place",
        "PLN" => "Plaine",
        "PLT" => "Plateau",
        "PRO" => "Promenade",
        "PRV" => "Parvis",
        "QUA" => "Quartier",
        "QUAI" => "Quai",
        "RES" => "Résidence",
        "RLE" => "Ruelle",
        "ROC" => "Rocade",
        "RPT" => "Rond-point",
        "RTE" => "Route",
        "RUE" => "Rue",
        "SEN" => "Sente",
        "SQ" => "Square",
        "TPL" => "Terre-plein",
        "TRA" => "Traverse",
        "VLA" => "Villa",
        "VLGE" => "Village"
    );

    const EXPOSITION_NORD = 'N';
    const EXPOSITION_OUEST = 'O';
    const EXPOSITION_SUD = 'S';
    const EXPOSITION_EST = 'E';
    public static $EXPOSITIONS = array(
        self::EXPOSITION_NORD => 'estimation.exposition.N',
        self::EXPOSITION_OUEST => 'estimation.exposition.O',
        self::EXPOSITION_SUD => 'estimation.exposition.S',
        self::EXPOSITION_EST => 'estimation.exposition.E'
    );

    const TYPE_BIEN_MAISON = 'M';
    const TYPE_BIEN_APPARTEMENT = 'A';
    public static $TYPES_BIEN = array(
        self::TYPE_BIEN_MAISON      => 'estimation.type.M',
        self::TYPE_BIEN_APPARTEMENT => 'estimation.type.A',
    );


    const ETAT_NEUF = 'N';
    const ETAT_BON = 'B';
    const ETAT_TRAVAUX = 'T';
    const ETAT_REFAIRE = 'R';
    public static $ETATS = array(
        self::ETAT_NEUF => 'estimation.etat.N',
        self::ETAT_BON => 'estimation.etat.B',
        self::ETAT_TRAVAUX => 'estimation.etat.T',
        self::ETAT_REFAIRE => 'estimation.etat.R'
    );

    public static $USAGES = array(
        'H' => 'estimation.usage.H',
        'L' => 'estimation.usage.L',
        'V' => 'estimation.usage.V'
    );

    public static $TYPES_PROPRIO = array(
        'P' => 'estimation.type_proprio.P',
        'L' => 'estimation.type_proprio.L',
        'A' => 'estimation.type_proprio.A',
        'I' => 'estimation.type_proprio.I'
    );

    public static $DELAIS = array(
        0 => 'estimation.delai._0',
        1 => 'estimation.delai._1',
        2 => 'estimation.delai._2',
        6 => 'estimation.delai._6',
        -1 => 'estimation.delai._pv'
    );


    private $fields = array(
        'numero',
        'type_voie',
        'voie',
        'codepostal',

        'nb_pieces',
        'nb_sdb',
        'surface_habitable',
        'surface_terrain',
        'exposition',
        'etage',
        'nb_etages',
        'nb_niveaux',
        'annee_construction',

        'ascenseur',
        'balcon',
        'terrasse',
        'parking',
        'garage',
        'travaux',
        'vue',
        'vue_detail',

        'proprietaire',
        'etat',
        'usage',
        'delai'
    );

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    public function getId() {
        return $this->id;
    }

    /**
     * @ORM\Column(type="json_array",nullable=true)
     */
    private $data = array();

    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $nom;
    public function getNom() {
        return $this->nom;
    }
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $prenom;
    public function getPrenom() {
        return $this->prenom;
    }
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $mail;
    public function getMail() {
        return $this->mail;
    }
    /**
     * @ORM\Column(type="string",length=255,nullable=true)
     */
    private $tel;
    public function getTel() {
        return $this->tel;
    }
    /**
     * @ORM\Column(type="string",length=1)
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\Column(name="demande_agence",type="boolean")
     */
    private $demandeAgence;
    public function getDemandeAgence() {
        return $this->demandeAgence;
    }
    public function setDemandeAgence( $demande_agence ) {
        $this->demandeAgence = $demande_agence;
        return $this;
    }

    /**
     * @ORM\Column(name="code_insee",type="string",length=5)
     * @Assert\NotBlank()
     */
    private $ville;
    public function getVille(){
        return $this->ville;
    }
    public function setVille( $ville ) {
        $this->ville = $ville;
        return $this;
    }

    /**
     * @var datetime $createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(name="created_at",type="datetime")
     */
    private $createdAt;

    public function __construct() {
        $this->createdAt = new \DateTime('now');
        $this->demandeAgence = false;
    }

    public function __get( $name ) {

        if ( in_array( $name, $this->fields ) ) {
            if ( array_key_exists( $name, $this->data ) ) {
                return $this->data[$name];
            }
            return null;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }

    public function __set( $name, $value ) {

        if ( in_array( $name, $this->fields ) ) {
            $this->data[$name] = $value;
            return;
        }

        $trace = debug_backtrace();
        trigger_error(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE);
        return null;
    }



    /**
     * Set data
     *
     * @param array $data
     * @return Estimation
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set nom
     *
     * @param string $nom
     * @return Estimation
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Estimation
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Set mail
     *
     * @param string $mail
     * @return Estimation
     */
    public function setMail($mail)
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Set tel
     *
     * @param string $tel
     * @return Estimation
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return Estimation
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Estimation
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
}
