<?php

namespace App\Controller;

use App\Entity\Person;
use App\Repository\PersonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
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
