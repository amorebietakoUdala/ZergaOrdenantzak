<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use AMREU\UserBundle\Form\UserType as BaseUserType;

class UserType extends BaseUserType
{
    protected $allowedRoles;

    /**
     * @param string $class The User class name
     */
    public function __construct(private $class, $allowedRoles)
    {
        if (empty($allowedRoles)) {
            $allowedRoles = ['ROLE_USER', 'ROLE_ADMIN'];
        }
        foreach ($allowedRoles as $role) {
            $this->allowedRoles[$role] = $role;
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $password_change = $options['password_change'];
        $readonly = $options['readonly'];
        $new = $options['new'];
        $builder
            ->add('username', null, [
                'label' => 'user.username',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(),
                ],
                'translation_domain' => 'user_bundle',
                'disabled' => $readonly,
            ])
            ->add('firstName', null, [
                'label' => 'user.firstName',
                'empty_data' => '',
                'constraints' => [
                    new NotBlank(),
                ],
                'translation_domain' => 'user_bundle',
                'disabled' => $readonly,
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.email',
                'empty_data' => '',
                'constraints' => [
                    new Email(),
                ],
                'translation_domain' => 'user_bundle',
                'disabled' => $readonly,
            ])
            ->add('activated', CheckboxType::class, [
                'label' => 'user.activated',
                'translation_domain' => 'user_bundle',
                'disabled' => $readonly,
                'required' => false,
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'user.roles',
                'choices' => $this->allowedRoles,
                'multiple' => true,
                'expanded' => false,
                'required' => true,
                'translation_domain' => 'user_bundle',
                'disabled' => $readonly,
            ]);
            $builder
            ->add('udala', null, [
                'label' => 'user.udala',
                'translation_domain' => 'user_bundle',
                'disabled' => $readonly,
            ]);

        if ($password_change) {
            $builder->add('password', RepeatedType::class, [
                'constraints' => [
                    new NotBlank(),
                ],
                'data' => '',
                'required' => $new,
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => [
                    'attr' => ['class' => 'password-field'],
                    /* Needed because it gives an error when no value entered.
                     * Needs to be checked in the controller if the password has changed.
                     */
                    'empty_data' => 'nopassword',
                    'required' => true,
                ],
                'first_options' => ['label' => 'user.new_password'],
                'second_options' => ['label' => 'user.repeat_new_password'],
                'translation_domain' => 'user_bundle',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
            'password_change' => false,
            'readonly' => false,
            'new' => false,
        ]);
    }
}
