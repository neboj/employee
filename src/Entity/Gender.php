<?php

namespace App\Entity;

use App\Repository\GenderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GenderRepository::class)
 */
class Gender
{
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

//    /**
//     * Gender constructor.
//     * @param string $type
//     */
//    public function __construct(string $type) {
//        $this->type = $type;
//    }

    /**
     * @param int $genderID
     * @return string
     * @throws \Exception
     */
    public static function getTranslatedGender(int $genderID){
        switch ($genderID) {
            case self::GENDER_MALE:
                return 'Male';
            case self::GENDER_FEMALE:
                return 'Female';
            default:
                throw new \Exception('Gender not defined');
        }
    }
}
