<?php
/**
 * Created by PhpStorm.
 * User: maximebriand
 * Date: 22/04/2017
 * Time: 12:03
 */

namespace DMB\BlogBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;

class PostAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title', 'text')
            ->add('content', 'textarea')
            ->add('chapterNumber', 'integer')
            ->add('coverImage', 'file', array(
                'required' => false,
                'data_class' => null
            ))
            ->add('author', 'sonata_type_model_list', array())
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('title');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('title');
    }
}
