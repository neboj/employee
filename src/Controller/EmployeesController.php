<?php


namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Gender;
use App\Entity\Title;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Class EmployeesController
 * @package App\Controller
 */
class EmployeesController extends Controller
{
    /**
     * @Route("/employees", name="employees")
     * @param Request $request
     */
    public function employeesAction(Request $request) {
        $employees = $this->getDoctrine()->getRepository(Employee::class)->getAllEmployees();

        $normalizer = new ObjectNormalizer();
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $finalEmployees = [];
        foreach ($employees as $employee) {
            $currentEmployee = $employee;
            $finalTitles = [];
            $employeeTitles = unserialize($employee['title']);
            foreach ($employeeTitles as $title) {
                $finalTitles[] = $serializer->deserialize($title, Title::class, 'json');
            }
            $currentEmployee['title'] = $finalTitles;
            $finalEmployees[] = $currentEmployee;
        }

        $results=$serializer->serialize($employees, 'json');


        return $this->render('employees.html.twig', [
            'serData' => $results,
            'employees' => $finalEmployees
            ]
        );
    }

    /**
     * @Route("/employees/filter",name="filter_employees")
     * @param Request $request
     * @return Response
     */
    public function filterActiveInactiveAll(Request $request){
        $filter = $request->request->get("filter");
        if ($filter !== 'active' && $filter !== 'inactive' && $filter !== 'all') {
            exit("filter is not good");
        }
        $employees = $this->getDoctrine()->getRepository(Employee::class)->getAllEmployees($filter);

        $normalizer = new ObjectNormalizer();
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);

        $finalEmployees = [];
        foreach ($employees as $employee) {
            $currentEmployee = $employee;
            $finalTitles = [];
            $employeeTitles = unserialize($employee['title']);
            foreach ($employeeTitles as $title) {
                $finalTitles[] = $serializer->deserialize($title, Title::class, 'json');
            }
            $currentEmployee['title'] = $finalTitles;
            $finalEmployees[] = $currentEmployee;
        }

        $results=$serializer->serialize($employees, 'json');
        return $this->render('tableEmployees.html.twig', [
                'serData' => $results,
                'employees' => $finalEmployees
            ]
        );
    }



//    /**
//     * @Route("/employees/save")
//     */
//    public function save() {
//        $employee1 = new Employee();
//        $employee1->setFirstName('Mira');
//        $employee1->setLastName('Miric');
//        $employee1->setGender(Gender::GENDER_FEMALE);
//        $date1 = \DateTime::createFromFormat('j-M-Y', '12-Jun-1988');
//        $employee1->setBirthday($date1);
//        $employee1->setActive(false);
//        $normalizer = new ObjectNormalizer();
//        $encoder = new JsonEncoder();
//        $serializer = new Serializer([$normalizer], [$encoder]);
//
//        $title = new Title();
//        $title->setName(Title::ROLE_HR);
//
//        $title1 = new Title();
//        $title1->setName(Title::ROLE_HR);
////        $titleSerialized = json_encode($title);
//
//        $titleser = $serializer->serialize($title, 'json');
//        $titleser1 = $serializer->serialize($title1, 'json');
//
//        $employee1->setTitle([$titleser]);
//
//
//        $entityManager = $this->getDoctrine()->getManager();
//
//        $entityManager->persist($employee1);
//
//        $entityManager->flush();
//
//        return new Response('Saves employess with data ids: ' . $employee1->getId());
//    }
}