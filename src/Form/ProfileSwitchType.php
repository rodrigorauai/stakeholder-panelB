<?php

namespace App\Form;

use App\Helper\ProfileHelper;
use App\Repository\TranslateRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

class ProfileSwitchType extends AbstractType
{
    /**
     * @var ProfileHelper
     */
    private $switcher;
    /**
     * @var RouterInterface
     */
    private $router;
    /**
     * @var TranslateRepository
     */
    private $transrepository;

    public function __construct(ProfileHelper $switcher, RouterInterface $router, TranslateRepository $transrepository)
    {
        $this->transrepository = $transrepository;
        $this->switcher = $switcher;
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $transconfig = $this->transrepository->findOneByActive();

        $disableds = $this->transrepository->findByDisabled($transconfig->getId());

        $choices = [];

        foreach ($disableds as $disable) {
            if ($disable->getTranslate() == 'BRL' && $disable->getActive() == false) {
                foreach ($this->switcher->getProfiles() as $profile) {
                    $choices[$profile['label']] = $profile['id'];
                }

                return $builder->add('activeProfile', ChoiceType::class, [
                    'label' => 'Access Profile',
                    'expanded' => true,
                    'choices' => $choices,
                ]);
            } else {
                foreach ($this->switcher->getProfiles() as $profile) {
                    $choices[$profile['label']] = $profile['id'];
                }

                return $builder->add('activeProfile', ChoiceType::class, [
                    'label' => 'Perfil de Acesso',
                    'expanded' => true,
                    'choices' => $choices,
                ]);
            }
        }


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'method' => 'POST',
            'action' => $this->router->generate('profile__switch'),
        ]);
    }
}
