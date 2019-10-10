<?php

namespace App\Controller;

use App\Entity\Account;
use App\Entity\Address;
use App\Entity\Person;
use App\Entity\UploadedPersonFile;
use App\Form\AddressType;
use App\Form\AddressTypeUSN;
use App\Form\BankAccountType;
use App\Helper\ProfileHelper;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use App\Form\BankAccountTypeUSN;
use App\Form\FileUploadType;
use App\Form\FileUploadTypeUSN;
use App\Form\PersonData;
use App\Form\PersonRolesType;
use App\Form\PersonRolesTypeUSN;
use App\Form\PersonSearchType;
use App\Form\PersonType;
use App\Form\PersonTypeNew;
use App\Form\PersonTypeNewUSN;
use App\Form\PersonTypeUSN;
use App\Form\SearchTypeUSN;
use App\Helper\PasswordHelper;
use App\Helper\UploadHelper;
use App\Repository\PersonRepository;
use App\Repository\ConfigurationRepository;
use App\Repository\TranslateRepository;
use App\Validator\UniqueUserValidator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Exception;
use Psr\Log\LoggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\EmailValidator;

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
     * @param ProfileHelper $profileSwitcher
     * @param Request $request
     * @param PersonRepository $repository
     * @param TranslateRepository $transrepository
     * @return Response
     * @Route("/pessoas", name="person__index")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT", "ROLE_STAKEHOLDER"})
     */
    public function index(ProfileHelper $profileSwitcher, Request $request, PersonRepository $repository, TranslateRepository $transrepository)
    {
        $form = $this->createForm(PersonSearchType::class);
        $form->handleRequest($request);

        $people = $repository->findUsingSearchForm($form);

        $currentProfile = $profileSwitcher->getCurrentProfile();
        switch ($currentProfile['id']) {

            case ProfileHelper::PROFILE_STAKEHOLDER:
                return $this->redirectToRoute('dashboard');
        }

        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(SearchTypeUSN::class);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(PersonSearchType::class);
                $form->handleRequest($request);
            }
        }

        $people = $repository->findUsingSearchForm($form);

        return $this->render('person/index.html.twig', [
            'translates' => $disableds,
            'controller_name' => 'UserController',
            'people' => $people,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @param PasswordHelper $helper
     * @param LoggerInterface $logger
     * @return RedirectResponse|Response
     * @Route("/pessoas/adicionar", name="person_create")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function create(Request $request, PasswordHelper $helper, LoggerInterface $logger,
                           TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(PersonTypeNewUSN::class);
                $form->get('sendPasswordDefinitionEmail')->setData(true);
            } else {
                $form = $this->createForm(PersonTypeNew::class);
                $form->get('sendPasswordDefinitionEmail')->setData(true);
            }
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $person = Person::fromDataObject($form->getData());
            $person->setCpf(str_replace(['-', '.'], '', $person->getCpf()));

            $account = new Account($person);

            $userRoles = ['ROLE_USER', 'ROLE_STAKEHOLDER'];
            $person->setRoles($userRoles);

            $this->em->persist($person);
            $this->em->persist($account);
            $this->em->flush();

            if (true === $form->get('sendPasswordDefinitionEmail')->getData()) {
                try {
                    $helper->sendPasswordDefinitionEmail($person);
                } catch (TransportExceptionInterface $exception) {
                    $this->addFlash('error', 'Não foi possível enviar o e-mail de definição de senha.');
                    $logger->error($exception->getMessage());
                } catch (Exception $exception) {
                    $this->addFlash('error', 'Não foi possível enviar o e-mail de definição de senha.');
                    $logger->error($exception->getMessage());
                }
            }

            return $this->redirectToRoute('person__index', [], 303);
        }

        return $this->render('person/form.html.twig', [
            'translates' => $disableds,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Person $person
     * @return Response
     * @Route("/pessoas/{id}", name="person_show")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
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
     * @param TranslateRepository $transrepository
     * @return Response
     * @Route("/pessoas/{id}/dados-pessoais", name="person__edit")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function edit(Person $person, Request $request, EntityManagerInterface $entityManager, TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(PersonTypeUSN::class, PersonData::fromEntity($person));
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(PersonType::class, PersonData::fromEntity($person));
                $form->handleRequest($request);
            }
        }


        if ($form->isSubmitted() && $form->isValid()) {

             $person->updateFromDataObject($form->getData());

             $person->setCpf(str_replace(['-', '.'], '', $person->getCpf()));

             $entityManager->persist($person);
             $entityManager->flush();

             return $this->redirectToRoute('person__edit', ['id' => $person->getId()], 303);

        }

        return $this->render('person/edit.html.twig', [
            'translates' => $disableds,
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    public function editTranslate(TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        return $this->render('person/_tab-bar.html.twig', [
            'translates' => $disableds,
        ]);
    }

    /**
     * @param Person $person
     * @param Request $request
     * @param PasswordHelper $helper
     * @param LoggerInterface $logger
     * @return Response
     * @Route("/pessoas/{id}/acesso", name="person__authentication__form")
     */
    public function authentication(Person $person, Request $request, PasswordHelper $helper, LoggerInterface $logger, TranslateRepository $transrepository)
    {
        $form = $this->createFormBuilder()
            ->add('sendPasswordDefinitionEmail', HiddenType::class, [
                'data' => true,
            ])
            ->getForm();

        $form->handleRequest($request);

        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $helper->sendPasswordDefinitionEmail($person);
                $this->addFlash('success', 'E-mail enviado.');
            } catch (TransportExceptionInterface $exception) {
                $this->addFlash('error', 'Não foi possível enviar o e-mail de definição de senha.');
                $logger->error($exception->getMessage());
            } catch (Exception $exception) {
                $this->addFlash('error', 'Não foi possível enviar o e-mail de definição de senha.');
                $logger->error($exception->getMessage());
            }
        }

        return $this->render('person/authentication/form.html.twig', [
            'translates' => $disableds,
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
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function editAddress(Person $person, Request $request, EntityManagerInterface $entityManager, TranslateRepository $transrepository)
    {
        $address = $person->getAddress();

        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(AddressTypeUSN::class, $address);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(AddressType::class, $address);
                $form->handleRequest($request);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Address $address */
            $address = $form->getData();
            $address->setEntity($person);

            $entityManager->persist($address);
            $entityManager->flush();

            return $this->redirectToRoute('person_address__edit', ['id' => $person->getId()], 303);
        }

        return $this->render('person/edit--address.html.twig', [
            'translates' => $disableds,
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Person $person
     * @param ConfigurationRepository $repository
     * @param TranslateRepository $transrepository
     * @return Response
     * @Route("/pessoas/{id}/contas-de-patrocinio", name="person_account__index")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function showAccounts(Person $person, ConfigurationRepository $repository, TranslateRepository $transrepository)
    {
        $currency = $repository->findOneByActive();

        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        return $this->render('person/show--accounts.html.twig', [
            'translates' => $disableds,
            'currency' => $currency->getLabel(),
            'person' => $person,
        ]);
    }

    /**
     * @param Person $person
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return RedirectResponse|Response
     * @Route("/pessoas/{id}/conta-bancaria", name="person__bank_account__edit")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function editBankAccount(Person $person, Request $request, EntityManagerInterface $entityManager, TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(BankAccountTypeUSN::class, $person->getBankAccount());
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(BankAccountType::class, $person->getBankAccount());
                $form->handleRequest($request);
            }
        }



        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Account $account */
            $account = $form->getData();
            $account->setOwner($person);

            $entityManager->persist($account);
            $entityManager->flush();

            return $this->redirectToRoute('person__bank_account__edit', ['id' => $person->getId()]);
        }

        return $this->render('person/bank-account/edit.html.twig', [
            'translates' => $disableds,
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Person $person
     * @return Response
     * @Route("/pessoas/{id}/arquivos", name="person__file__index")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function uploadIndex(Person $person, TranslateRepository $transRepository)
    {
        $transconfig = $transRepository->findOneByActive();

        $disableds = $transRepository->findByDisabled($transconfig->getId());

        return $this->render('person/file/index.html.twig', [
            'translates' => $disableds,
            'person' => $person,
        ]);
    }

    /**
     * @param Person $person
     * @param Request $request
     * @param UploadHelper $helper
     * @return Response
     * @Route("/pessoas/{id}/arquivos/novo", name="person__file__form")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function uploadForm(Person $person, Request $request, UploadHelper $helper, TranslateRepository $transRepository)
    {
        $transconfig = $transRepository->findOneByActive();

        $disableds = $transRepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(FileUploadTypeUSN::class);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(FileUploadType::class);
                $form->handleRequest($request);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $helper->savePersonFile($data['name'], $data['file'], $person);

            // Flush entity created by the UploadHelper
            $this->em->flush();

            return $this->redirectToRoute('person__file__index', ['id' => $person->getId()], 303);
        }

        return $this->render('person/file/form.html.twig', [
            'translates' => $disableds,
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param UploadedPersonFile $file
     * @return BinaryFileResponse|RedirectResponse
     * @Route("/pessoas/arquivos/{id}", name="person__file__download")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function fileDownload(UploadedPersonFile $file)
    {
        return new BinaryFileResponse($file->getPath());
    }

    /**
     * @param Person $person
     * @param Request $request
     * @return Response
     * @Route("/pessoas/{id}/cargos", name="person__role__index")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function roleIndex(Person $person, Request $request, TranslateRepository $transRepository)
    {
        $transconfig = $transRepository->findOneByActive();

        $disableds = $transRepository->findByDisabled($transconfig->getId());
      
        $userRoles = array_merge(
            ['ROLE_USER', 'ROLE_STAKEHOLDER'],
            $person->getRoles()
        );

        $formData = [];

        foreach ($userRoles as $role) {
            $formData[strtolower(str_replace('ROLE_', 'IS_', $role))] = true;
        }


        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createForm(PersonRolesTypeUSN::class, $formData);
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(PersonRolesType::class, $formData);
                $form->handleRequest($request);
            }
        }


        if ($form->isSubmitted() && $form->isValid()) {
            $userRoles = ['ROLE_USER', 'ROLE_STAKEHOLDER'];

            foreach ($form->getData() as $authorization => $isGranted) {

                if (!$isGranted) continue;

                $userRoles[] = strtoupper(preg_replace('/is_/', 'role_', $authorization));
            }

            $person->setRoles($userRoles);

            $this->em->persist($person);
            $this->em->flush();
        }

        return $this->render('person/role/index.html.twig', [
            'translates' => $disableds,
            'person' => $person,
            'form' => $form->createView(),
        ]);
    }
}
