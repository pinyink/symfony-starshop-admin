<?php

namespace App\Form;

use App\Entity\Role;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Username',
                'required' => true,
                'attr' => ['autocomplete' => 'off']
            ])
            ->add('email', EmailType::class, ['label' => 'Email'])
            ->add('roles', EntityType::class, [
                'class' => Role::class,
                'placeholder' => 'Pilih Satu',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->where('r.parent = :parentId')
                        ->setParameter('parentId', 1)
                        ->orderBy('r.id', 'ASC');
                },
                'choice_label' => 'description',
                'label' => 'Role',
                'mapped' => false,
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options'  => ['label' => 'Password', 'hash_property_path' => 'password'],
                'second_options' => ['label' => 'Ulangi Password'],
                'mapped' => false,
                'required' => false
            ])
            ->add('fullname', TextType::class, ['label' => 'Nama Lengkap'])
            ->add('foto', FileType::class, [
                'label' => 'Foto Profil',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
                'attr' => ['accept' => '.jpg,.jpeg,.img,.png']
            ])
        ;

        // Listener untuk mengisi nilai awal 'roles' jika entity User sudah punya RBAC Role
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $user = $event->getData();
            $form = $event->getForm();

            if (!$user || null === $user->getId()) {
                return; // Jika mode create / new user, abaikan
            }

            // Ambil role pertama dari relasi RbacRoles
            if (method_exists($user, 'getRbacRoles') && !$user->getRbacRoles()->isEmpty()) {
                $currentRole = $user->getRbacRoles()->first();
                $form->get('roles')->setData($currentRole);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            // when using stateful tokens (which is the default)
            'csrf_token_id'   => 'user_item',
        ]);
    }
}
