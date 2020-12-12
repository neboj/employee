<?php

namespace App\Entity;

use App\Repository\TitleRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TitleRepository::class)
 */
class Title
{
    const ROLE_DEVELOPER = 'ROLE_DEVELOPER';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_OWNER = 'ROLE_OWNER';
    const ROLE_CEO = 'ROLE_CEO';
    const ROLE_CTO = 'ROLE_CTO';
    const ROLE_CFO = 'ROLE_CFO';
    const ROLE_HR = 'ROLE_HR';
    const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';


    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=255)
     */
    private $name;


    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }
//
//    /**
//     * Title constructor.
//     * @param string $name
//     */
//    public function __construct(string $name)
//    {
//        $this->name = $name;
//    }


    /**
     * @param string $name
     * @return string
     */
    public static function getTranslatedTitleName (string $name) {
        switch ($name){
            case self::ROLE_DEVELOPER:
                return 'Dev';
            case self::ROLE_CTO:
                return 'CTO';
            case self::ROLE_HR:
                return 'HR';
            default:
                return 'DEFAULT';
        }
    }
}
