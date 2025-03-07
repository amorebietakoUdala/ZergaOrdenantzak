<?php

namespace App\Form;

use App\Entity\Atalaparrafoa;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtalaparrafoaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ordena')
            ->add('testuaeu',CKEditorType::class, ['config_name' => 'my_config_1'])
            ->add('testuaes',CKEditorType::class, ['config_name' => 'my_config_1'])
            ->add('atala')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Atalaparrafoa::class
        ]);
    }
}
