<?php

namespace App\Controller;

use App\Helper\DashboardHelper;
use App\Form\ConfigurationType;
use App\Form\ConfigurationData;
use App\Repository\ConfigurationRepository;
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
     * @return Response
     * @param Request $request
     * @Route("/configuration", name="configuration")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function index(Request $request, ConfigurationRepository $repository)
    {
        $form = $this->createForm(ConfigurationType::class);
        $form->handleRequest($request);

        dump($request);
        $config = $repository->findOneByActive();
        dd($config);

        return $this->render('configuration/index.html.twig', [
            'config' => $config,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @Route("/configuration", name="configuration")
     * @IsGranted({"ROLE_ADMINISTRATIVE_ASSISTANT"})
     */
    public function edit(Request $request, ConfigurationRepository $repository)
    {
        $config = $repository->findOneByActive();

        $form = $this->createForm(ConfigurationType::class, ConfigurationData::fromEntity($config));
        $form->handleRequest($request);

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
            'config' => $config,
            'form' => $form->createView()
        ]);
    }
}
