<?php

namespace Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class.
 *
 * @ORM\Table(name="semester")
 * @ORM\Entity(repositoryClass="Repository\SemesterRepository")
 */
class Semester
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_spring", type="boolean")
     * @Assert\NotNull()
     */
    private $isSpring;

    /**
     * @var string
     *
     * @ORM\Column(name="year", type="integer", length=4)
     * @Assert\NotBlank()
     */
    private $year;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isSpring()
    {
        return $this->isSpring;
    }

    /**
     * @param bool $isSpring
     */
    public function setSpring($isSpring)
    {
        $this->isSpring = $isSpring;
    }

    /**
     * @return string
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * @param string $year
     */
    public function setYear($year)
    {
        $this->year = $year;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime()
    {
        $month = $this->isSpring ? '01' : '08';

        return new \DateTime($this->year . $month . '01 00:00:00');
    }

    /**
     * @return \DateTime
     */
    public function getEndTime()
    {
        $month = $this->isSpring ? '07' : '12';

        return new \DateTime($this->year . $month . '31 23:59:59');
    }

    public function __toString()
    {
        return ($this->isSpring ? 'Vår ' : 'Høst ') . $this->year;
    }

    public function isEqualTo(Semester $semester)
    {
        if ($semester->getYear() == $this->year) {
            if ($semester->isSpring() && $this->isSpring() || !$semester->isSpring() && !$this->isSpring()) {
                return true;
            }
        }

        return false;
    }
}
