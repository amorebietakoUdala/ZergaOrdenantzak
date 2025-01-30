<?php

namespace App\Form;

use App\Entity\Azpiatalaparrafoaondoren;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AzpiatalaparrafoaondorenType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('ordena')
            ->add('testuaeu',CKEditorType::class, ['config_name' => 'my_config_1'])
            ->add('testuaes',CKEditorType::class, ['config_name' => 'my_config_1'])
            ->add('azpiatala')
            ->add('udala')        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Azpiatalaparrafoaondoren::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'appbundle_azpiatalaparrafoaondoren';
    }


}
