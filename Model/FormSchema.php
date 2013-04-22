<?php

/*
 * This file is part of the Netstar Form Schema package.
 *
 * (c) Gleb Tiltikov <gleb@netstar.od.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Netstar\FormSchemaBundle\Model;

use Symfony\Component\Form\FormBuilderInterface;
use Netstar\FormSchemaBundle\Model\FormSchemaInterface;
use Netstar\FormSchemaBundle\Model\FormRelation;

class FormSchema implements FormSchemaInterface
{
	protected $builder;
	protected $factory;
	protected $relations;
	protected $current_options;
	
	const html_class_name = 'related_form';
	const service_name = 'netstar_form_schema';
	
    public function __construct(FormBuilderInterface $builder)
    {
    	$this->builder = $builder;
        $this->factory = $builder->getFormFactory();
        $this->relations = array();
    }
    
    public function getFormBuilder()
    {
    	return $this->builder;
    }
    
    public function getRelations()
    {
    	return $this->relations;
    }

    public function addRelation(FormRelation $relation)
    {
    	$this->relations[] = $relation;
    }
    
    public function relationBuilder()
    {
    	return new FormRelation();
    }
    
    public function initUpdateForm($form, $data)
    {
    	$form_vars = array_keys(get_object_vars($data));
    	
    	foreach($this->getRelations() as $relation) {
    		$this->addElement($form, $relation->getMaster(), $relation->getId(), null, null, 'master_entity', $relation);
    		
    		foreach($relation->getSlaves() as $rel_entity) {
    			$this->addElement($form, $rel_entity, $relation->getId());
    		}
		}
    }
    
    public function finalUpdateForm($form, $data)
    {
    	foreach($this->getRelations() as $relation) {
        	foreach($relation->getSlaves() as $rel_entity) {
    			$this->addElement($form, $rel_entity, $relation->getId(), $relation->getQueryBuilder(), $data[$relation->getMaster()]);
    		}
        }
    }
    
    private function addElement($form, $element_name, $id, $query_builder_relation = null, $data = null, $type_name = 'entity', $relation = null)
    {
    	$options = self::getCurrentOptions($this->builder->get($element_name)->getOptions());
    	
    	if($query_builder_relation) {
    		$options['query_builder'] = function($repository) use ($data, $query_builder_relation) {
				return $repository->createQueryBuilder('relbuilder')->where('relbuilder.'.$query_builder_relation.' = :relation_param')->setParameter('relation_param', $data); 
			};
    	}
    	
    	if($relation) $options['form_relation'] = $relation;
    	
    	$options['attr']['class'] = ((isset($this->current_options[$element_name])) ? $this->current_options[$element_name].' ' : '').((isset($options['attr']['class'])) ? $options['attr']['class'].' ' : '').$element_name.'_'.self::html_class_name.'_'.$id;
    	if(!isset($this->current_options[$element_name])) $this->current_options[$element_name] = '';
    	$this->current_options[$element_name] .= $options['attr']['class'];
        $form->add($this->factory->createNamed($element_name, $type_name, null, $options));
    }
    
    private static function getCurrentOptions(array $options)
    {
    	$res = array();
    	
    	$res['label'] = $options['label'];
    	$res['required'] = $options['required'];
    	$res['class'] = $options['class'];
    	$res['query_builder'] = $options['query_builder'];
    	$res['property'] = $options['property'];
    	if(isset($options['data'])) $res['data'] = $options['data'];
    	$res['empty_data'] = $options['empty_data'];
    	$res['empty_value'] = $options['empty_value'];
    	$res['multiple'] = $options['multiple'];
    	$res['expanded'] = $options['expanded'];
    	//$res['preferred_choices'] = $options['preferred_choices'];
    	//$res['choices'] = $options['choices'];
    	$res['error_bubbling'] = $options['error_bubbling'];
    	$res['compound'] = $options['compound'];
    	$res['data_class'] = $options['data_class'];
    	$res['attr'] = $options['attr'];
    	
    	return $res;
    }
}
