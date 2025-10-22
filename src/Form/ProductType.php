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

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nama', TextType::class, [ 'label' => 'Nama Product', 'required' => true ])
			->add('harga', TextType::class, [ 'label' => 'Harga', 'required' => true ])
			->add('tanggal', DateType::class, [ 'widget' => 'single_text', 'label' => 'Tanggal', 'html5' => false, 'format' => 'dd-mm-yyyy', 'attr' => [ 'class' => 'form-control datepicker', 'placeholder' => 'dd-mm-yyyy']])
			->add('tahun', IntegerType::class, [ 'label' => 'Tahun', 'required' => true ])
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
