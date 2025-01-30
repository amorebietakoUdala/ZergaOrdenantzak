<?php

namespace App\Form;

use App\Entity\Kontzeptua;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class KontzeptuaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('kodea')
            ->add('kontzeptuaeu')
            ->add('kontzeptuaes')
            ->add('kopurua')
            ->add('unitatea')
            ->add('azpiatala')
            ->add('baldintza')
            ->add('kontzeptumota')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Kontzeptua::class
        ));
    }
}
