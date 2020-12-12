<?php


namespace App\Twig;


use App\Entity\Gender;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Class GenderAndNameExtension
 * @package App\Twig
 */
class GenderAndNameExtension extends  AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('printGenders', [$this, 'printGendersTwig']),
            new TwigFunction('printFullName', [$this, 'printFullNameTwig']),
            new TwigFunction('printPrettyRole', [$this, 'printPrettyRoleTwig'])
        ];
    }


    protected $doctrine;

    public function __construct(RegistryInterface $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function printFullNameTwig($firstName, $lastName) {
        return $firstName . ' ' . $lastName;
    }

    public function printPrettyRoleTwig($role) {
        return substr($role,5);
    }

    public function printGendersTwig() {
        $em = $this->doctrine->getManager();

        $employees = $em->getRepository(Gender::class)->findAll();
        return $employees;
    }
}