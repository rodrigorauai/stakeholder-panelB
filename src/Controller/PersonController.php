<?php

namespace App\Controller;

use App\Entity\Person;
use App\Form\PersonType;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param PersonRepository $repository
     * @return Response
     * @Route("/pessoas", name="person_list")
     */
    public function index(PersonRepository $repository)
    {
        $people = $repository->findAll();

        return $this->render('person/index.html.twig', [
            'controller_name' => 'UserController',
            'people' => $people,
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/pessoas/adicionar", name="person_create")
     */
    public function create(Request $request)
    {
        $form = $this->createForm(PersonType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = Person::fromDataObject($form->getData());

            $this->em->persist($person);
            $this->em->flush();

            return $this->redirectToRoute('person_list', [], 303);
        }

        return $this->render('person/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Person $person
     * @return Response
     * @Route("/pessoas/{id}", name="person_profile")
     */
    public function profile(Person $person)
    {

        return $this->render('person/profile.html.twig', [
            'person' => $person,
        ]);
    }
}
