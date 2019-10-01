<?php

namespace App\Controller;

use App\Entity\Person;
use App\Helper\PasswordHelper;
use App\Repository\AuthenticationTokenRepository;
use App\Repository\PersonRepository;
use App\Repository\TranslateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @var MailerInterface
     */
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error]);
    }

    /**
     * @param Request $request
     * @param PersonRepository $repository
     * @param PasswordHelper $helper
     * @return RedirectResponse|Response
     * @Route("/esqueci-minha-senha", name="password_recovery")
     */
    public function passwordRecovery(Request $request, PersonRepository $repository,
                                     PasswordHelper $helper,
                                     TranslateRepository $transrepository)
    {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createFormBuilder()
                    ->add('email', EmailType::class, ['label' => 'Your email', 'required' => true,])
                    ->setMethod('post')
                    ->getForm();

                $form->handleRequest($request);
            } else {
                $form = $this->createFormBuilder()
                    ->add('email', EmailType::class, ['label' => 'Seu e-mail', 'required' => true,])
                    ->setMethod('post')
                    ->getForm();

                $form->handleRequest($request);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var null|Person $user */
            $user = $repository->findOneBy(['email' => $form->get('email')->getData()]);

            if (!$user) {
                $this->addFlash('error', 'Não foi possível encontrar uma conta com o seu e-mail.');
            } else {

                try {
                    $helper->sendPasswordDefinitionEmail($user);
                    $this->addFlash('success', 'Em breve você receberá em seu e-mail um link para redefinir sua senha.');
                } catch (TransportExceptionInterface $exception) {
                    $this->addFlash('error', 'Não foi possível enviar o e-mail de redefinição de senha.');
                } catch (Exception $exception) {
                    $this->addFlash('error', 'Não foi possível enviar o e-mail de redefinição de senha.');
                }

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/password-recovery.html.twig', [
            'translates' => $disableds,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     * @param AuthenticationTokenRepository $tokenRepository
     * @param UserPasswordEncoderInterface $encoder
     * @param EntityManagerInterface $entityManager
     * @param TranslateRepository $transrepository
     * @return RedirectResponse|Response
     * @Route("/definir-senha", name="password_definition")
     */
    public function passwordReset(
        Request $request,
        AuthenticationTokenRepository
        $tokenRepository,
        UserPasswordEncoderInterface $encoder,
        EntityManagerInterface $entityManager,
        TranslateRepository $transrepository
    ) {
        $transconfig = $transrepository->findOneByActive();

        $disableds = $transrepository->findByDisabled($transconfig->getId());

        $tokenString = $request->query->get('token');
        $userId = $request->query->get('user');

        $token = $tokenRepository->findOneBy([
            'string' => $tokenString,
        ]);

        if (!$token || $token->getUser()->getId() !== (int) $userId) {
            throw new AccessDeniedHttpException();
        }

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                $form = $this->createFormBuilder()
                    ->add('password', PasswordType::class, ['label' => 'Create a password', 'required' => true])
                    ->add('password_confirmation', PasswordType::class, ['label' => 'Confirm your password', 'required' => true])
                    ->getForm();

                $form->handleRequest($request);
            } else {
                $form = $this->createFormBuilder()
                    ->add('password', PasswordType::class, ['label' => 'Crie uma senha', 'required' => true])
                    ->add('password_confirmation', PasswordType::class, ['label' => 'Confirme sua senha', 'required' => true])
                    ->getForm();

                $form->handleRequest($request);
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form->get('password')->getData();

            if ($password !== $form->get('password_confirmation')->getData()) {
                $this->addFlash('error', 'É necessário que a senha no campo de confirmação seja idêntica à primeira senha informada.');
            } else {
                $token->getUser()->setPassword($encoder->encodePassword($token->getUser(), $password));

                $entityManager->persist($token->getUser());
                $entityManager->remove($token);
                $entityManager->flush();

                $this->addFlash('success', 'Sua senha foi definida com sucesso!');

                return $this->redirectToRoute('app_login');
            }
        }

        return $this->render('security/password-definition.html.twig', [
            'translates' => $disableds,
            'form' => $form->createView(),
        ]);
    }
}
