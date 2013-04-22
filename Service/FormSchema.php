<?php

/*
 * This file is part of the Netstar Form Schema package.
 *
 * (c) Gleb Tiltikov <gleb@netstar.od.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Netstar\FormSchemaBundle\Service;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Form\FormBuilderInterface;

use Netstar\FormSchemaBundle\Form\EventListener\RelatedFormEntityEventSubscriber;
use Netstar\FormSchemaBundle\Model\FormSchema as FormSchemaModel;
use Netstar\FormSchemaBundle\Model\FormSchemaSonataAdminFilter as FormSchemaSonataAdminFilterModel;

class FormSchema
{
	private $container;
	
	public function __construct($container)
	{
		$this->container = $container;
	}
	
	public function newSchema(FormBuilderInterface $builder)
	{
		return new FormSchemaModel($builder);
	}
	
	public function newSonataFilter()
	{
		return new FormSchemaSonataAdminFilterModel();
	}
	
	public function set($schema, $mode = 'form')
	{
		switch($mode) {
			case 'form':
				foreach($schema->getRelations() as $relation) {
					$this->container->get('session')->set('netstar_form_schema_'.$relation->getId(), $relation);
				}
				
				$event_subscriber = new RelatedFormEntityEventSubscriber($schema->getFormBuilder(), $schema);
				
				$schema->getFormBuilder()->addEventSubscriber($event_subscriber);	
			break;
			case 'sonata_filter':
				$this->container->get('twig')->addGlobal('netstar_form_schema_sonata_filters', $this->buildViewData($schema->getRelations()));
			break;
		}
	}
	
	private function buildViewData($relations)
	{
		$filters = array();
		
		foreach($relations as $relation) $filters[] = $this->processRelationTree($relation);
		
		return $filters;
	}
	
	private function processRelationTree($relation, $parent_id = null)
	{
		$filter_relation = array();
		
		$entities = array_merge(array(null), $this->container->get('doctrine.orm.entity_manager')->getRepository($relation->getEntity())->findBy($relation->getParentRelation($parent_id), $relation->getOrderByParams()));
		
		foreach($entities as $entity) $filter_relation[] = $this->processFilter($relation, $entity);
		
		return $filter_relation;
	}
	
	private function processFilter($relation, $entity = null)
	{
		$filter_item['id'] =  $relation->getFilterName();
		$filter_item['value'] = (($entity) ? $entity->getId() : '');
		$filter_item['title'] = (($entity) ? $entity->__toString() : '');
		
		if($relation->getSlave()) $filter_item['items'] = $this->processRelationTree($relation->getSlave(), (($entity) ? $entity->getId() : -1));
		else $filter_item['items'] = array();
		
		return $filter_item;
	}
}
