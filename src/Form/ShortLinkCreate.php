<?php


namespace App\Form;


use App\Controller\ShortLinkCrudController;
use App\Entity\ShortLink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShortLinkCreate extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $shortCode = $options['data']['code'];
        $builder
            ->add('fullUrl', TextType::class)
            ->add('shortCode', TextType::class, [
                'data' => $shortCode,
                'disabled' => true,
            ])
            ->add('code', HiddenType::class, [
                'data' => $shortCode,
            ])
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    }
}