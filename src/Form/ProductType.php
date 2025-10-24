<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\RupiahTransformer;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Categories;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nama', TextType::class, [ 'label' => 'Nama Product', 'required' => true ])
			->add('harga', TextType::class, [ 'label' => 'Harga', 'required' => true ])
			->add('tanggal', DateType::class, [ 'widget' => 'single_text', 'label' => 'Tanggal', 'html5' => false, 'format' => 'dd-mm-yyyy', 'attr' => [ 'class' => 'form-control datepicker', 'placeholder' => 'dd-mm-yyyy']])
			->add('tahun', IntegerType::class, [ 'label' => 'Tahun', 'required' => true ])
			->add('category', EntityType::class, [ 
                'class' => Categories::class, 
                'label' => 'Categories', 
                'required' => true, 
                'placeholder' => 'Pilih Satu', 
                'choice_label' => 'name', 
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('r')
                        ->orderBy('r.name', 'ASC');
                }, 
            ])
			->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2048k',
                        'mimeTypes' => [
                            'image/jpg',
                            'image/jpeg',
                            'image/png'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid Image',
                    ])
                ],
                'attr' => [
                    'accept' => '.jpg,.jpeg,.img,.png'
                ]
            ])
        ;
        $builder->get('harga')->addModelTransformer(new RupiahTransformer());
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
            // enable/disable CSRF protection for this form
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            // an arbitrary string used to generate the value of the token
            // using a different string for each form improves its security
            // when using stateful tokens (which is the default)
            'csrf_token_id'   => 'product_item',
        ]);
    }
}
