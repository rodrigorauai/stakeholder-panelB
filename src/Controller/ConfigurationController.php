<?php

namespace App\Controller;

use App\Form\ConfigurationTypeUSN;
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

class ConfigurationController extends AbstractController
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
     * @param ConfigurationRepository $repository
     * @return Response
     * @Route("/configuration", name="configuration")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function index(Request $request, ConfigurationRepository $repository)
    {
//        $config = $repository->findOneByActive();
//
//        $disableds = $repository->findByDisabled($config->getId());
//
//        foreach ($disableds as $disable) {
//            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
//                $form = $this->createForm(ConfigurationType::class, ConfigurationData::fromEntity($config));
//                $form->handleRequest($request);
//            } else {
//                $form = $this->createForm(ConfigurationType::class);
//                $form->handleRequest($request);
//            }
//        }

        $form = $this->createForm(ConfigurationType::class);
        $form->handleRequest($request);

        $config = $repository->findOneByActive();

        return $this->render('configuration/index.html.twig', [
            'config' => $config,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param ConfigurationRepository $repository
     * @param TranslateRepository $transrepository
     * @return Response
     * @Route("/configuration", name="configuration")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function edit(Request $request, ConfigurationRepository $repository, TranslateRepository $transrepository)
    {
        $config = $repository->findOneByActive();

        $form = $this->createForm(ConfigurationType::class, ConfigurationData::fromEntity($config));
        $form->handleRequest($request);

        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                //      dd($disable->getActive());
                $form = $this->createForm(ConfigurationTypeUSN::class, ConfigurationData::fromEntity($config));
                $form->handleRequest($request);
            }
        }


        if ($form->isSubmitted() && $form->isValid()) {

            $currency = $form->getData()->currency;
            $config = $repository->findOneBy(['currency' => $currency]);
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

        return $this->render('configuration/index.html.twig', [
            'translates' => $disableds,
            'config' => $config,
            'form' => $form->createView()
        ]);
    }
}
