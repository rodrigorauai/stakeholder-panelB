<?php

namespace App\Controller;

use App\Form\TranslateData;
use App\Form\TranslateType;
use App\Form\TranslateTypeUSN;
use App\Helper\DashboardHelper;
use App\Form\ConfigurationType;
use App\Form\ConfigurationData;
use App\Repository\ConfigurationRepository;
use App\Repository\TranslateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TranslateController extends AbstractController
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
     * @param Request $request
     * @param TranslateRepository $repository
     * @return Response
     * @Route("/translate", name="translate")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function index(Request $request, TranslateRepository $repository)
    {
        $config = $repository->findOneByActive();

        $disableds = $repository->findByDisabled($config->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                //      dd($disable->getActive());
                $form = $this->createForm(TranslateTypeUSN::class, TranslateData::fromEntity($config));
                $form->handleRequest($request);
            } else {
                $form = $this->createForm(TranslateType::class, TranslateData::fromEntity($config));
                $form->handleRequest($request);
            }
        }
//
//        $form = $this->createForm(TranslateType::class);
//        $form->handleRequest($request);

        $config = $repository->findOneByActive();

        return $this->render('translate/index.html.twig', [
            'config' => $config,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param TranslateRepository $repository
     * @return Response
     * @Route("/translate", name="translate")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function edit(Request $request, TranslateRepository $repository)
    {
        $config = $repository->findOneByActive();

        $disableds = $repository->findByDisabled($config->getId());

        $form = $this->createForm(TranslateType::class, TranslateData::fromEntity($config));
        $form->handleRequest($request);

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
          //      dd($disable->getActive());
                $form = $this->createForm(TranslateTypeUSN::class, TranslateData::fromEntity($config));
                $form->handleRequest($request);
            } else {

            }
    }

        if ($form->isSubmitted() && $form->isValid()) {

            $translate = $form->getData()->translate;
            $config = $repository->findOneBy(['translate' => $translate]);
            $config->setActive(1);

            $this->em->persist($config);
            $this->em->flush();

            $disableds = $repository->findByDisabled($config->getId());

            foreach ($disableds as $disable) {
                $disable->setActive(0);
                $this->em->persist($disable);
                $this->em->flush();
            }
        }

        return $this->render('translate/index.html.twig', [
            'translates' => $disableds,
            'config' => $config,
            'form' => $form->createView()
        ]);
    }
}
