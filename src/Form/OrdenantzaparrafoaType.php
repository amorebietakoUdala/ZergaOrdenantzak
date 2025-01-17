<?php

namespace App\Form;

use App\Entity\Ordenantzaparrafoa;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdenantzaparrafoaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ordena')
            ->add('testuaeu',CKEditorType::class, array(
                'config_name' => 'my_config_1',
            ))
            ->add('testuaes', CKEditorType::class, array(
                'config_name' => 'my_config_1',
            ))
            ->add('ordenantza')
            ->add('udala')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ordenantzaparrafoa::class
        ));
    }
}
