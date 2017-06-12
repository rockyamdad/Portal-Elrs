<?php

namespace AppBundle\Controller;

use AppBundle\Event\ApplicationCreate;
use AppBundle\Event\UserCreate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use UserBundle\Entity\Profile;
use UserBundle\Entity\User;
use Symfony\Component\EventDispatcher\EventDispatcher;


class PorchaController extends Controller
{
    public function indexAction(Request $request)
    {
        $surveys = $this->get('app_bundle.service.api_manager')->getAllSurveys();
        $divisions = $this->get('app_bundle.service.api_manager')->getAllDivisions();
        $statistics = $this->get('app_bundle.service.api_manager')->getAllDashBoardStatistics();
        return $this->render('Frontend/index.html.twig', array(
            'surveys'    => $surveys,
            'statistics' => $statistics,
            'divisions'  => $divisions));
    }

    public function comboDistrictsAction($divisionId)
    {
        $districts = $this->get('app_bundle.service.api_manager')->getDistrictsByDivision($divisionId);
        $ret = [];
        foreach ($districts->data as $district) {
            $ret[] = ['id' => $district->id, 'text' => $district->name];
        }
        return new JsonResponse($ret);
    }

    public function comboUpozilasAction($districtId)
    {
        $upozilas = $this->get('app_bundle.service.api_manager')->getUpozilasByDistrict($districtId);
        $ret = [];
        foreach ($upozilas->data as $upozila) {
            $ret[] = ['id' => $upozila->id, 'text' => $upozila->name];
        }
        return new JsonResponse($ret);
    }

    public function comboMouzasAction($upozilaId)
    {
        $mouzas = $this->get('app_bundle.service.api_manager')->getMouzasByUpozila($upozilaId);
        $ret = [];
        foreach ($mouzas->data as $mouza) {
            $ret[] = ['id' => $mouza->id, 'text' => $mouza->name];
        }
        return new JsonResponse($ret);
    }

    public function courtFeesAction($districtId)
    {
        $type = 'PORCHA';
        $courtFees = $this->get('app_bundle.service.api_manager')->getCourtFeesByOffice($districtId, $type);
        return new JsonResponse($courtFees);
    }

    public function getKhatianStatusAction($applicationId)
    {
        $khatianStatus = $this->get('app_bundle.service.api_manager')->getKahtianStatusByApplicationId($applicationId);
        return new JsonResponse($khatianStatus);
    }

    public function caseCopyCourtFeesAction($districtId)
    {
        $type = 'CASE_COPY';
        $courtFees = $this->get('app_bundle.service.api_manager')->getCaseCopyCourtFeesByOffice($districtId, $type);
        return new JsonResponse($courtFees);
    }

    public function mouzaApplicationCourtFeesAction($districtId)
    {
        $type = 'MOUZA_MAP';
        $courtFees = $this->get('app_bundle.service.api_manager')->getMouzaApplicationCourtFeesByOffice($districtId, $type);
        return new JsonResponse($courtFees);
    }

    public function informationApplicationCourtFeesAction($districtId)
    {
        $type = 'INFORMATION_SLIP';
        $courtFees = $this->get('app_bundle.service.api_manager')->getInformationApplicationCourtFeesByOffice($districtId, $type);
        return new JsonResponse($courtFees);
    }

    public function deliveryFeesAction($districtId)
    {
        $deliveryFees = $this->get('app_bundle.service.api_manager')->getDeliveryFeesByOffice($districtId);
        return new JsonResponse($deliveryFees);
    }

    public function divisionVrrAction(Request $request,$id)
    {
        $data = $this->get('app_bundle.service.api_manager')->getDistrictsByDivision($id);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $data->data,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/

        );
        return $this->render('Frontend/Vrr/division.html.twig', array(
            'districts'                => $pagination,
            'totalServiceDelivered'    => $data->totalServiceDelivered,
            'totalRecordDigitization'  => $data->totalRecordDigitization,
            'totalApplicationReceived' => $data->totalApplicationReceived,
            'totalUdcServiceDelivered' => $data->totalUdcServiceDelivered,
        ));
    }

    public function districtVrrAction(Request $request,$id)
    {
        $data = $this->get('app_bundle.service.api_manager')->getUpozilasByDistrict($id);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $data->data,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/

        );
        return $this->render('Frontend/Vrr/district.html.twig', array(
            'upozilas'                 => $pagination,
            'totalServiceDelivered'    => $data->totalServiceDelivered,
            'totalUdcServiceDelivered' => $data->totalUdcServiceDelivered,
            'totalRecordDigitization'  => $data->totalRecordDigitization,
            'totalApplicationReceived' => $data->totalApplicationReceived,
        ));

    }

    public function upozilaVrrAction(Request $request,$id)
    {
        $data = $this->get('app_bundle.service.api_manager')->getMouzasByUpozila($id);
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $data->data,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/

        );
        return $this->render('Frontend/Vrr/upozila.html.twig', array(
            'mouzas'                   => $pagination,
            'totalServiceDelivered'    => $data->totalServiceDelivered,
            'totalUdcServiceDelivered' => $data->totalUdcServiceDelivered,
            'totalRecordDigitization'  => $data->totalRecordDigitization,
            'totalApplicationReceived' => $data->totalApplicationReceived,
        ));

    }

    public function mouzaVrrAction($id, $type)
    {
        $volumes = $this->get('app_bundle.service.api_manager')->getVolumesByMouzaAndSurveyType($id, $type);
        $volumesJsonData = json_encode($volumes);
        $arrayVolumes = json_decode($volumesJsonData, true);
        return $this->render('Frontend/Vrr/mouza.html.twig', array(
            'volumes' => $arrayVolumes
        ));

    }

    public function getLandProjectsAction(Request $request)
    {
        return $this->render('Frontend/land_projects.html.twig');
    }

    public function getUserManualAction(Request $request)
    {
        return $this->render('Frontend/user_manual.html.twig');
    }

    public function getDlrsServicesAction(Request $request)
    {
        $UDCList = $this->get('app_bundle.service.api_manager')->getUDCList();
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $UDCList,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/

        );
        return $this->render('Frontend/dlrs_services.html.twig', array(
            'UDCLists' => $pagination
        ));
    }

    public function getRecordCheckAction($applicationId)
    {
        $khatianId = $this->get('app_bundle.service.api_manager')->getKahtianIdByApplicationId($applicationId);
        return new JsonResponse($khatianId);
    }

    public function getPorchaApplicationAction(Request $request)
    {
        $surveys = $this->get('app_bundle.service.api_manager')->getAllSurveys();
        $divisions = $this->get('app_bundle.service.api_manager')->getAllDivisions();

        list($fullname, $nationalID, $birthDate, $email) = $this->getUserInfoFromSession();

        if ($request->getMethod() == Request::METHOD_POST) {
            $data = $request->request->all();
            try {
                $phoneNumber = $data['mobile'];
                list($event, $dispatcher) = $this->userCreate($phoneNumber, $data);

                $porcha = $this->get('app_bundle.service.api_manager')->porchaApplicationCreate($data);

                if($porcha[0]){
                    $event = new ApplicationCreate($porcha[0]);
                    $dispatcher = $this->get('event_dispatcher');
                    $dispatcher->dispatch('application.create', $event);

                    $this->addFlash('success', 'আপনার আবেদন সফল হয়েছে.');
                }else{
                    $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
            }
            return $this->redirectToRoute('invoice_application', [
                'data' => json_encode($porcha[0])
            ]);
        }

        return $this->render('Frontend/Services/porchaApplication.html.twig', array(
            'surveys'    => $surveys,
            'fullname'   => $fullname,
            'nationalID' => $nationalID,
            'birthDate'  => $birthDate,
            'email'      => $email,
            'divisions'  => $divisions));
    }

    public function getInvoiceApplicationAction($data){

        return $this->render('Frontend/Services/applicationInvoice.html.twig',array(
            'data'=>json_decode($data)
        ));

    }

    public function getPorchaCorrectionApplicationAction(Request $request)
    {
        $surveys = $this->get('app_bundle.service.api_manager')->getAllSurveys();
        $divisions = $this->get('app_bundle.service.api_manager')->getAllDivisions();

        list($fullname, $nationalID, $birthDate, $email) = $this->getUserInfoFromSession();

        if ($request->getMethod() == Request::METHOD_POST) {
            $data = $request->request->all();
            try {
                $phoneNumber = $data['mobile'];
                list($event, $dispatcher) = $this->userCreate($phoneNumber, $data);

                $this->get('app_bundle.service.api_manager')->porchaCorrectionApplicationCreate($data);
                $smsText = 'আপনার আবেদন টি সফল হয়েছে।';
                $this->get('app_bundle.service.sms_manager')->sendSms($phoneNumber, $smsText);

                $this->addFlash('success', 'আপনার আবেদন সফল হয়েছে.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
            }

            return $this->redirectToRoute('porcha_correction_application');
        }

        return $this->render('Frontend/Services/porchaCorrectionApplication.html.twig', array(
            'surveys'    => $surveys,
            'fullname'   => $fullname,
            'nationalID' => $nationalID,
            'birthDate'  => $birthDate,
            'email'      => $email,
            'divisions'  => $divisions));
    }

    public function doctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    public function getPorchaSearchAction(Request $request)
    {
        $surveys = $this->get('app_bundle.service.api_manager')->getAllSurveys();
        $divisions = $this->get('app_bundle.service.api_manager')->getAllDivisions();
        if ($request->getMethod() == Request::METHOD_POST) {
            $data = $request->request->all();
            try {
                $porchas = $this->get('app_bundle.service.api_manager')->porchaSearch($data);
                if(!$porchas){
                    $this->addFlash('error', 'আপনার রেকর্ড টি পাওয়া যাই নি .');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'আপনার রেকর্ড টি পাওয়া যাই নি .');
            }

            return $this->render('Frontend/Services/porcha_search.html.twig', array(
                'porcha'   => $porchas,
                'surveys'   => $surveys,
                'divisions' => $divisions));
        }
        return $this->render('Frontend/Services/porcha_search.html.twig', array(
            'surveys'   => $surveys,
            'divisions' => $divisions));
    }

    public function porchaSearchResponseAction(Request $request) {

        if ($request->getMethod() == Request::METHOD_POST) {
            $data = $request->request->all();
            try {
                $porchas = $this->get('app_bundle.service.api_manager')->porchaSearch($data);
            } catch (\Exception $e) {
                $this->addFlash('error', 'আপনার রেকর্ড টি পাওয়া যাই নি .');
            }

            return $this->render('Frontend/Services/search_result.html.twig', array(
                'porcha'   => $porchas));
        }

    }

    public function getMouzaApplicationAction(Request $request)
    {
        $surveys = $this->get('app_bundle.service.api_manager')->getAllSurveys();
        $divisions = $this->get('app_bundle.service.api_manager')->getAllDivisions();
        list($fullname, $nationalID, $birthDate, $email) = $this->getUserInfoFromSession();

        if ($request->getMethod() == Request::METHOD_POST) {
            $data = $request->request->all();

            try {
                $phoneNumber = $data['mobile'];
                list($event, $dispatcher) = $this->userCreate($phoneNumber, $data);

                $mouza = $this->get('app_bundle.service.api_manager')->mouzaApplicationCreate($data);

                if($mouza[0]){
                    //Trying to use event dispatcher
                    $event = new ApplicationCreate($mouza[0]);
                    $dispatcher = $this->get('event_dispatcher');
                    $dispatcher->dispatch('application.create', $event);

                    $this->addFlash('success', 'আপনার আবেদন সফল হয়েছে.');

                }else{
                    $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
                }

            } catch (\Exception $e) {
                $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
            }

            return $this->redirectToRoute('mouza_application');
        }

        return $this->render('Frontend/Services/mouzaApplication.html.twig', array(
            'surveys'    => $surveys,
            'fullname'   => $fullname,
            'nationalID' => $nationalID,
            'birthDate'  => $birthDate,
            'email'      => $email,
            'divisions'  => $divisions));
    }

    public function getCaseCopyApplicationAction(Request $request)
    {
        $district = $this->get('app_bundle.service.api_manager')->getAllDistricts();
        $divisions = $this->get('app_bundle.service.api_manager')->getAllDivisions();
        list($fullname, $nationalID, $birthDate, $email) = $this->getUserInfoFromSession();

        if ($request->getMethod() == Request::METHOD_POST) {
            $data = $request->request->all();
            try {
                $phoneNumber = $data['mobile'];
                list($event, $dispatcher) = $this->userCreate($phoneNumber, $data);

                $kescopy = $this->get('app_bundle.service.api_manager')->caseCopyApplicationCreate($data);
                if($kescopy[0]){
                    $event = new ApplicationCreate($kescopy[0]);
                    $dispatcher = $this->get('event_dispatcher');
                    $dispatcher->dispatch('application.create', $event);

                    $this->addFlash('success', 'আপনার আবেদন সফল হয়েছে.');
                }else{
                    $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
            }

            return $this->redirectToRoute('case_copy_application');
        }

        return $this->render('Frontend/Services/CaseCopyApplication.html.twig', array(
            'districts'  => $district,
            'fullname'   => $fullname,
            'nationalID' => $nationalID,
            'birthDate'  => $birthDate,
            'email'      => $email,
            'divisions'  => $divisions));
    }

    public function getInformationApplicationAction(Request $request)
    {
        $district = $this->get('app_bundle.service.api_manager')->getAllDistricts();
        $divisions = $this->get('app_bundle.service.api_manager')->getAllDivisions();
        list($fullname, $nationalID, $birthDate, $email) = $this->getUserInfoFromSession();

        if ($request->getMethod() == Request::METHOD_POST) {
            $data = $request->request->all();
            try {
                $phoneNumber = $data['mobile'];
                list($event, $dispatcher) = $this->userCreate($phoneNumber, $data);

                $informationApp = $this->get('app_bundle.service.api_manager')->informationApplicationCreate($data);

                if($informationApp[0]){
                    $event = new ApplicationCreate($informationApp[0]);
                    $dispatcher = $this->get('event_dispatcher');
                    $dispatcher->dispatch('application.create', $event);

                    $this->addFlash('success', 'আপনার আবেদন সফল হয়েছে.');

                }else{
                    $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
                }

            } catch (\Exception $e) {
                $this->addFlash('error', 'আপনার আবেদন সফল ছিল না. ');
            }

            return $this->redirectToRoute('information_application');
        }

        return $this->render('Frontend/Services/informationApplication.html.twig', array(
            'districts'  => $district,
            'fullname'   => $fullname,
            'nationalID' => $nationalID,
            'birthDate'  => $birthDate,
            'email'      => $email,
            'divisions'  => $divisions));
    }

    /**
     * @param $data
     * @param $mergedPhoneNumber
     * @return User
     */
    protected function setUserLoginInfo($data, $mergedPhoneNumber, $randomPassword)
    {
        $user = new User();
        $user->setNationalID($data['nid']);
        $user->setPhoneNumber($mergedPhoneNumber);
        $user->setEmail($data['email']);
        $user->setPassword(md5($randomPassword));
        $this->doctrineManager()->persist($user);
        $this->doctrineManager()->flush();
        return $user;
    }

    /**
     * @param $data
     * @param $user
     */
    protected function setUserProfile($data, $user)
    {
        $profile = new Profile();
        $profile->setBirthDate($data['dob']);
        $profile->setFullname($data['name']);
        $profile->setUser($user);
        $profile->setAddress($data['citizenDistrict']);
        $profile->setNationalID($data['nid']);
        $this->doctrineManager()->persist($profile);
        $this->doctrineManager()->flush();
    }

    public function getDivisionsAction(Request $request)
    {
        $divisions = $this->get('app_bundle.service.api_manager')->getAllDivisions();
        return $this->render('Frontend/headerDivision.html.twig', array('divisions' => $divisions));
    }

    public function showAction(Request $request, $id)
    {
        $khatianView = $this->get('app_bundle.service.api_manager')->viewKhatian($id);
        return $this->render('Frontend/khatianShow.html.twig', array('content' => $khatianView));
    }

    /**
     * @param $phoneNumber
     * @param $data
     * @return array
     */
    protected function userCreate($phoneNumber, $data)
    {
        $hasUser = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->findOneByPhoneNumber($phoneNumber);
        if (!$hasUser) {
            $randomPassword = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $user = $this->setUserLoginInfo($data, $phoneNumber, $randomPassword);
            $this->setUserProfile($data, $user);

            $userInfo = array(
                'phoneNumber' => $phoneNumber,
                'password' => $randomPassword);

            $event = new UserCreate($userInfo);
            $dispatcher = $this->get('event_dispatcher');
            $dispatcher->dispatch('user.create', $event);
            return array($event, $dispatcher);
        }
    }

    /**
     * @return array
     */
    protected function getUserInfoFromSession()
    {
        $session = new Session();
        if ($session->get('phoneNumber')) {
            $hasUser = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->findOneByPhoneNumber($session->get('phoneNumber'));
            $fullname = $hasUser->getProfile()->getFullname();
            $nationalID = $hasUser->getNationalID();
            $birthDate = $hasUser->getProfile()->getBirthDate();
            $email = $hasUser->getEmail();
            return array($fullname, $nationalID, $birthDate, $email);
        } else {
            $fullname = null;
            $nationalID = null;
            $birthDate = null;
            $email = null;
            return array($fullname, $nationalID, $birthDate, $email);
        }
    }

}
