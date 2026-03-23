<?php

namespace App\Form;

use App\Entity\SoftwareVersion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoftwareVersionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('systemVersion')
            ->add('systemVersionAlt')
            ->add('link')
            ->add('stLink')
            ->add('gdLink')
            ->add('latest')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SoftwareVersion::class,
        ]);
    }
}
