<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Address;
use App\Entity\Person;
use App\Entity\UploadedPersonFile;
use App\Form\AddressType;
use App\Form\BankAccountType;
use App\Form\PersonData;
use App\Form\PersonFileType;
use App\Form\PersonType;
use App\Form\PersonSearchType;
use App\Helper\UploadHelper;
use App\Repository\PersonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
     * @Route("/pessoas", name="person__index")
     */
    public function index(Request $request, PersonRepository $repository)
    {
        $people = $repository->findAll();

        $form = $this->createForm(PersonSearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $people = $repository->findByExampleField($form->getData('index'));
        }

        return $this->render('person/index.html.twig', [
            'controller_name' => 'UserController',
            'people' => $people,
            'form' => $form->createView(),
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
            $account = new Account($person);

            $this->em->persist($person);
            $this->em->persist($account);
            $this->em->flush();

            return $this->redirectToRoute('person__index', [], 303);
        }

        return $this->render('person/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Person $person
     * @return Response
     * @Route("/pessoas/{id}", name="person_show")
     */
    public function show(Person $person)
    {
        return $this->redirectToRoute('person__edit', [
            'id' => $person->getId(),
        ]);
    }

    /**
     * @param Person $person
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @Route("/pessoas/{id}/dados-pessoais", name="person__edit")
     */
    public function edit(Person $person, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(PersonType::class, PersonData::fromEntity($person));
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isSubmitted()) {
            $person->updateFromDataObject($form->getData());

            $entityManager->persist($person);
            $entityManager->flush();

            return $this->redirectToRoute('person__edit', ['id' => $person->getId()], 303);
        }

        return $this->render('person/edit.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Person $person
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @throws Exception
     * @Route("/pessoas/{id}/endereco", name="person_address__edit")
     */
    public function editAddress(Person $person, Request $request, EntityManagerInterface $entityManager)
    {
        $address = $person->getAddress();

        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Address $address */
            $address = $form->getData();
            $address->setEntity($person);

            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('person_address__edit', ['id' => $person->getId()], 303);
        }

        return $this->render('person/edit--address.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Person $person
     * @return Response
     * @Route("/pessoas/{id}/contas-de-patrocinio", name="person_account__index")
     */
    public function showAccounts(Person $person)
    {
        return $this->render('person/show--accounts.html.twig', [
            'person' => $person,
        ]);
    }

    /**
     * @param Person $person
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @Route("/pessoas/{id}/conta-bancaria", name="person__bank_account__edit")
     */
    public function editBankAccount(Person $person, Request $request, EntityManagerInterface $entityManager)
    {
        $form = $this->createForm(BankAccountType::class, $person->getBankAccount());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->getData();
            $account->setOwner($person);

            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('person__bank_account__edit', ['id' => $person->getId()]);
        }

        return $this->render('person/bank-account/edit.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Person $person
     * @return Response
     * @Route("/pessoas/{id}/arquivos", name="person__file__index")
     */
    public function uploadIndex(Person $person)
    {
        return $this->render('person/file/index.html.twig', [
            'person' => $person,
        ]);
    }

    /**
     * @param Person $person
     * @param Request $request
     * @param UploadHelper $helper
     * @return Response
     * @Route("/pessoas/{id}/arquivos/novo", name="person__file__form")
     */
    public function uploadForm(Person $person, Request $request, UploadHelper $helper)
    {
        $form = $this->createForm(PersonFileType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $helper->savePersonFile($data['name'], $data['file'], $person);

            // Flush entity created by the UploadHelper
            $this->em->flush();

            return $this->redirectToRoute('person__file__index', ['id' => $person->getId()], 303);
        }

        return $this->render('person/file/form.html.twig', [
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param UploadedPersonFile $file
     * @return BinaryFileResponse
     * @Route("/pessoas/arquivos/{id}", name="person__file__download")
     */
    public function fileDownload(UploadedPersonFile $file)
    {
        return new BinaryFileResponse($file->getPath());
    }
}
