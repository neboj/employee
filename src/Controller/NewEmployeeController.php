<?php


namespace App\Controller;

use App\Entity\Employee;
use App\Entity\Gender;
use App\Entity\Title;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Class NewEmployeeController
 * @package App\Controller
 */
class NewEmployeeController extends Controller
{
    /**
     * @Route("/new-employee", name="new_employee")
     * @param Request $request
     * @return Response
     */
    public function employeesAction(Request $request) {

        return $this->render('new_employee.html.twig', [
            'errors' => [],
            'success' => ''
            ]
        );
    }

    /**
     * @Route("/new-employee/submit", name="handle_form")
     * @param Request $request
     * @return Response
     */
    public function employeesHandleFormAction(Request $request) {
        $normalizer = new ObjectNormalizer();
        $encoder = new JsonEncoder();
        $serializer = new Serializer([$normalizer], [$encoder]);
        $validator = Validation::createValidator();

        $firstName = $request->get("first_name");
        $lastName = $request->get("last_name");

        $title = $request->get('title');
//        die($title);
        $tokens = explode(',', $title);
        $allTitles = [];
        foreach ($tokens as $token) {
            if ($token != '') {
                $newTitle = new Title();
                $newTitle->setName($token);
                $allTitles[] = $serializer->serialize($newTitle, 'json');;
            }
        }
        $birthday = new \DateTime('now');
        try {
            $birthday = new \DateTime($request->get("birthday"));
        } catch (\Exception $e) {
            exit($e->getMessage());
        }

        $gender = (int) $request->get('gender');
        if ($gender > 2 && $gender < 1) {
            $gender = 1;
        }
        $active = (int)(bool) $request->get('active');

        $input = ['first_name' => $firstName, 'last_name' => $lastName, 'birthday' => $birthday, 'gender' => $gender,
            'active' => $active, 'title' => $title];

        $constraints = new Assert\Collection([
            'first_name' => [new Assert\Length(['min' => 2, 'max' => 255]), new Assert\NotBlank],
            'last_name' =>[new Assert\Length(['min' => 2, 'max' => 255]), new Assert\NotBlank],
            'birthday' =>[new Assert\DateTime, new Assert\NotBlank],
            'gender' =>[new Assert\NotBlank],
            'active' =>[new Assert\NotBlank],
            'title' =>[new Assert\NotBlank],
        ]);
        $violations = $validator->validate($input, $constraints);
        $successMessage = '';
        $errorMessages = '';
        if (0 !== count($violations)) {
            $accessor = PropertyAccess::createPropertyAccessor();

            $errorMessages = [];

            foreach ($violations as $violation) {
                $accessor->setValue($errorMessages,
                    $violation->getPropertyPath(),
                    $violation->getMessage());
            }

        } else {
            $em = $this->getDoctrine()->getManager();
            $employee = new Employee();
            $employee->setFirstName($firstName);
            $employee->setLastName($lastName);
            $employee->setGender($gender);
            $employee->setActive($active);
//            $title = new Title();
//            $title->setName(Title::ROLE_DEVELOPER);
//            $titleSerialized = $serializer->serialize($title, 'json');

            $employee->setTitle($allTitles);
            $employee->setBirthday($birthday);

            $em->persist($employee);
            $em->flush();
            $successMessage = 'success';
        }

        return $this->render('new_employee.html.twig', [
            'errors' => $errorMessages,
            'success' => $successMessage
            ]
        );
    }

    /**
     * @Route("/new-employee/search-titles", name="search_titles")
     * @param Request $request
     * @return JsonResponse
     */
    public function searchTitles(Request $request) {
        $title = '';
        $titlesDB = [];
        if($request->isXmlHttpRequest() && $request->isMethod('POST')) {
            $title = $request->request->get('term');
//            echo $title;
            $titlesDB = $this->getDoctrine()->getRepository(Title::class)->getAllTitlesLike($title);
        }

        return new JsonResponse($titlesDB);

//        return $this->render('search_titles.html.twig', [
//            'title' => $title,
//            'titlesDB' => $titlesDB
//            ]
//        );
    }
}