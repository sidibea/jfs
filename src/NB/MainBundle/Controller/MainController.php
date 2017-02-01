<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 1/31/17
 * Time: 10:58 AM
 */

namespace NB\MainBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller{

    public function indexAction(){

        $em = $this->getDoctrine()->getManager();
        $national = $em->getRepository('NBMainBundle:City')->findAllNationalCities();
        $international = $em->getRepository('NBMainBundle:City')->findAllInternationalCities();

        return $this->render('NBMainBundle::index.html.twig', [
            'national' => $national,
            'international' => $international
        ]);
    }

    public function listingAction(Request $request){

        if($request->getMethod() == 'GET'){
            $departure =  $this->get('doctrine.orm.entity_manager')->getRepository('NBMainBundle:City') ->find($request->get('from'));
            $arrival = $this->get('doctrine.orm.entity_manager')->getRepository('NBMainBundle:City') ->find($request->get('to'));
        }


        $companies = $this->get('doctrine.orm.entity_manager')->getRepository('NBMainBundle:Company') ->findAll();

        $admin_link = $this->getParameter('admin_link');


        $search =  $this->get('doctrine.orm.entity_manager')
            ->getRepository('NBMainBundle:Travel')
            ->getTravelByRoute($request->get('from'), $request->get('to'), date("d-m-Y", strtotime($request->get('dateJ'))));

        return $this->render('NBMainBundle::listing.html.twig', [
            'departure' => $departure,
            'arrival' => $arrival,
            'listCompany' => $companies,
            'search' => $search,
            'admin_link' => $admin_link,
            'dateJ' => $request->get('dateJ')
        ]);

    }

}