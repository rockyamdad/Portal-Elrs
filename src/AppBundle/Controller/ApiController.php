<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Division;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{

    public function getDivisionsAction(Request $request)
    {
        //$divisions = file_get_contents('http://www.elrs.local/api/divisions');
        $this->get('app_bundle.service.api_manager')->getAllDivisions();
        return new Response('divisions getd successfully');
    }
    public function getDistrictsAction(Request $request)
    {
        $districts = file_get_contents('http://www.elrs.local/api/districts');
        $this->get('app_bundle.service.api_manager')->getAllDistricts();
        return new Response('Districts getd successfully');
    }
    public function getUpozilasAction(Request $request)
    {
        $upozilas = file_get_contents('http://www.elrs.local/api/upozilas');
        $this->get('app_bundle.service.api_manager')->getAllUpozilas();
        return new Response('Upozilas getd successfully');
    }
    public function getMouzasAction(Request $request)
    {
        $mouzas = file_get_contents('http://www.elrs.local/api/mouzas');
        $this->get('app_bundle.service.api_manager')->getAllMouzas();
        return new Response('Mouzas getd successfully');
    }
    public function getJlnumbersAction(Request $request)
    {
        $jlnumbers = file_get_contents('http://www.elrs.local/api/jlnumbers');
        $this->get('app_bundle.service.api_manager')->getAllJlnumbers();
        return new Response('Jlnumbers getd successfully');
    }
    public function getSurveysAction(Request $request)
    {
        $surveys = file_get_contents('http://www.elrs.local/api/surveys');
        $this->get('app_bundle.service.api_manager')->getAllSurveys();
        return new Response('Surveys getd successfully');
    }
}
