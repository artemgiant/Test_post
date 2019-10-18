<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * OrderType
 *
 * @ORM\Table(name="form_helps")
 * @ORM\Entity
 */
class FormHelps
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
     * @ORM\Column(name="code", type="string", length=255, nullable=true)
     */
    private $code;



    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="text_ru", type="text", nullable=true)
     */
    private $textRu;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text", nullable=true)
     */
    private $text;



    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return string
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }



    /**
     * @param string $name
     * @return string
     */
    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param string $text
     * @return string
     */
    public function setText($text): self
    {
        $this->text = $text;

        return $this;
    }


    public function getTextRu(){
        return $this->textRu;
    }
    /**
     * @param string $nameRu
     * @return string
     */
    public function setTextRu( $textRu)
    {
        $this->textRu = $textRu;

        return $this;
    }

     /**
     * return string
     */
    public function __toString()
    {
        if (!empty($this->getName()))
            return $this->getName();

        return '';
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getText()
    {
        $locale = '';
        if(isset($GLOBALS['request']) && $GLOBALS['request']) $locale = $GLOBALS['request']->getLocale();
        if (!empty($locale) && $locale!='ua' && !empty($this->{"name".ucfirst($locale)})) return $this->{"text".ucfirst($locale)};
        else return $this->text;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getOriginText()
    {
       return $this->text;
    }
}
