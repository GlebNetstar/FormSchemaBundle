<?php 

/*
 * This file is part of the Netstar Form Schema package.
 *
 * (c) Gleb Tiltikov <gleb@netstar.od.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Netstar\FormSchemaBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Netstar\FormSchemaBundle\Model\FormSchemaInterface;

class RelatedFormEntityEventSubscriber implements EventSubscriberInterface
{
    protected $factory;
    protected $builder;
    protected $schema;
	
    public function __construct(FormBuilderInterface $builder, FormSchemaInterface $schema)
    {
        $this->builder = $builder;
        $this->factory = $builder->getFormFactory();
        $this->schema = $schema;
    }
	
    public static function getSubscribedEvents()
    {
        return array(FormEvents::PRE_BIND => 'preBind', FormEvents::POST_SET_DATA => 'postSetData');
    }
	
    public function postSetData(FormEvent $event)
    {
        if (null === $event->getData()) return;
        
    	$this->schema->initUpdateForm($event->getForm(), $event->getData());
	
    }
	
    public function preBind(FormEvent $event)
    {
        if (null === $event->getData()) return;
        
        $this->schema->finalUpdateForm($event->getForm(), $event->getData());
    }
}
