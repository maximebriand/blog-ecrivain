<?php

namespace DMB\UserBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('imageFile', VichImageType::class);
    }
    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
        // Or for Symfony < 2.8
        // return 'fos_user_registration';
    }
    public function getBlockPrefix()
    {
        return 'app_user_registration';
    }
    // For Symfony 2.x
    public function getName()
    {
        return $this->getBlockPrefix();
    }
}
