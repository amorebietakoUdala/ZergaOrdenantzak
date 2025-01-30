<?php

namespace App\Form;

use App\Entity\Historikoa;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Validator\Constraints\NotBlank;

class HistorikoaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('onartzedata', DateType::class, array(
                'widget' => 'single_text',

                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,

                'format' => 'yyyy-MM-dd',

                // add a class that can eb selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ))
            ->add('bogargitaratzedata', DateType::class, array(
                'widget' => 'single_text',

                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'format' => 'yyyy-MM-dd',

                // add a class that can eb selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ))
            ->add('bogbehinbetikodata', DateType::class, array(
                'widget' => 'single_text',

                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'format' => 'yyyy-MM-dd',

                // add a class that can eb selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
            ))
            ->add('bogestekaeu')
            ->add('bogestekaes')
            ->add('indarreandata', DateType::class, array(
                'widget' => 'single_text',

                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,
                'format' => 'yyyy-MM-dd',

                // add a class that can eb selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
		'constraints' => [new NotBlank(),]
            ))
            ->add('aldaketakeu',CKEditorType::class, array(
                'config_name' => 'my_config_1',
            ))
            ->add('aldaketakes',CKEditorType::class, array(
                'config_name' => 'my_config_1',
            ))
            ->add('bogargitaratzedatatestua')
        ;
    }
    
    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(array(
            'data_class' => Historikoa::class
        ));
    }
}
