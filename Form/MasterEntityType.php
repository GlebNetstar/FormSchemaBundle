<?php 

/*
 * This file is part of the Netstar Form Schema package.
 *
 * (c) Gleb Tiltikov <gleb@netstar.od.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Netstar\FormSchemaBundle\Form;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\DataEvent;

//use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormValidatorInterface;


use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Netstar\FormSchemaBundle\Form\EventListener\MasterEntityEventSubscriber;

class MasterEntityType extends EntityType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	$builder->setAttribute('form_relation', $options['form_relation']);
    }
    
	public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	return $resolver->setDefaults(array('form_relation' => ''));
    }
    
    
    public function buildView(FormView $view, FormInterface $form, array $options)
    {    	
    	$view->set('form_relation', $form->getAttribute('form_relation'));
    }

    public function getParent()
    {
        return 'entity';
    }

    public function getName()
    {
        return 'master_entity';
    }
}
