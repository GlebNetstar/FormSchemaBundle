<?php

/*
 * This file is part of the Netstar Form Schema package.
 *
 * (c) Gleb Tiltikov <gleb@netstar.od.ua>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Netstar\FormSchemaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Session\Session;

use Netstar\FormSchemaBundle\Model\FormRelation;

class FormSchemaController extends Controller
{
    /**
     * @Route("/form-schema/retrieve-data/{ident}/{data}", name="related_forms_retrieve_data", options={"expose"=true}))
     * @Template()
     */
    public function retrieveDataAction(Request $request)
    {
    	$form_relation = $request->getSession()->get('netstar_form_schema_'.$request->get('ident'));
		
		$res = array();
		
		if($form_relation) {
    		$results = $this->get('doctrine.orm.entity_manager')->getRepository($form_relation->getRetrieveEntity())->findBy(array($form_relation->getFindByFieldName() => $request->get('data')), $form_relation->getOrderByParams());
	    	
			$res[''] = '';
			
			foreach($results as $entity) {
				$field = 'get'.$form_relation->getResultFieldName();
				$res[$entity->getId()] = $entity->$field();
			}
		}
		
		return new Response(json_encode($res));
    }
}
