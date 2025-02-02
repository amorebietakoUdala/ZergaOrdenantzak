<?php

namespace App\Form;

use App\Entity\Udala;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UdalaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('izenaeu')
            ->add('izenaes')
            ->add('kodea')
            ->add('logoa')
            ->add('ifk')
            ->add('izendapenaeu')
            ->add('izendapenaes')
//            ->add('lopdeu')
//            ->add('lopdes')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Udala::class
        ));
    }
}
