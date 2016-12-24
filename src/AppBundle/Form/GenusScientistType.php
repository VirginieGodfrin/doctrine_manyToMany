<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use AppBundle\Entity\User;
use AppBundle\Repository\UserRepository;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class GenusScientistType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('user', EntityType::class,[
                    'class' => User::class,
                    'choice_label' =>'email',
                    'query_builder' => function(UserRepository $repo){
                        return $repo->createIsScientistQueryBuilder();
                    }

                ])
            ->add('yearsStudied')
            ->addEventListener(
                FormEvents::POST_SET_DATA,
                array($this, 'onPostSetData')
            )     
            ;
    }

    public function onPostSetData(FormEvent $event){
        if ($event->getData() && $event->getData()->getId()){

            $form = $event->getForm();
            unset($form['user']);

        }
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\GenusScientist'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_genusscientist';
    }


}
