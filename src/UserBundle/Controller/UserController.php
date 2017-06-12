<?php

namespace UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use UserBundle\Entity\Profile;
use UserBundle\Entity\User;
use UserBundle\Form\LoginType;
use UserBundle\Form\PasswordChangeType;
use UserBundle\Form\ProfileEditType;
use UserBundle\Form\ProfileType;
use UserBundle\Form\RegistrationType;
use Symfony\Component\HttpFoundation\Request;




class UserController extends Controller
{
    public function doctrineManager()
    {
        return $this->getDoctrine()->getManager();
    }

    public function registationAction(Request $request)
    {

        $user = new User();
        $form =  $this->createForm(new RegistrationType(), $user);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $password=md5($user->getPassword());
            $phoneNumber=$user->getPhoneNumber();
            $user->setPassword($password);
            $user->getProfile()->upload();
            $this->doctrineManager()->persist($user);
            $this->doctrineManager()->flush();
            $this->addFlash('success', 'সফলভাবে রেজিস্ট্রেশন সম্পন্ন হয়েছে');
            $smsText = 'আপনি DRRS এ সফল ভাবে নিবন্ধিত হয়েছেন। আপনার ব্যবহারকারী আইডি নিজ মোবাইল নং, ধন্যবাদ ';
            $this->get('app_bundle.service.sms_manager')->sendSms($phoneNumber, $smsText);
            return $this->redirect($this->generateUrl('login'));
        }
        return $this->render('UserBundle:User:registration.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    public function loginAction(Request $request)
    {

        $form =  $this->createForm(new LoginType());
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('UserBundle:User');
            $userCheck = $repository->findOneBy(array(
                'phoneNumber' => $request->request->get('user_login')['phoneNumber'],
            ));
            if (isset($userCheck)) {
                $user = $repository->findOneBy(array(
                    'phoneNumber' => $request->request->get('user_login')['phoneNumber'],
                    'password' => md5($request->request->get('user_login')['password']),
                ));

                if (isset($user)) {
                    $phoneNumber = $user->getPhoneNumber();
                    $password = md5($user->getPassword());
                    $session = new Session();
                    $session->set('phoneNumber', $phoneNumber);
                    $session->set('password', $password);
                    $this->addFlash('success', 'সফলভাবে লগইন করা হয়েছে');
                    return $this->redirect($this->generateUrl('citizen_homepage'));
                } else {
                    $this->addFlash('error', 'আপনি ভুল পাসওয়ার্ড দিয়েছেন');
                }
            } else {
                $this->addFlash('error', 'আপনার ফোন নম্বরটি  রেজিস্টার  করা নাই ');
            }
        }

        return $this->render('UserBundle:User:login.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function logoutAction()
    {
        $session = new Session();
        $session->clear();
        return $this->redirect($this->generateUrl('citizen_homepage'));
    }
    public function profileAction(Request $request, $phoneNumber){

        $em=$this->getDoctrine()->getManager();
        $repository=$em->getRepository('UserBundle:User');
        $user=$repository->findOneBy(array(
            'phoneNumber'=> $phoneNumber,
        ));
        return $this->render('UserBundle:User:profile.html.twig', array(
            'user' => $user,
        ));
    }
    public function appliedPorchaAction(Request $request, $phoneNumber){

        $em=$this->getDoctrine()->getManager();
        $repository=$em->getRepository('UserBundle:User');
        $user=$repository->findOneBy(array(
            'phoneNumber'=> $phoneNumber,
        ));
        $porchaList = $this->get('app_bundle.service.api_manager')->getAllPorchaByUser($user->getPhoneNumber());
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $porchaList,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/

        );
        return $this->render('UserBundle:User:appliedPorcha.html.twig', array(
            'user' => $user,
            'porchalist' => $pagination
        ));
    }
    public function appliedMouzaMapAction(Request $request, $phoneNumber){

        $em=$this->getDoctrine()->getManager();
        $repository=$em->getRepository('UserBundle:User');
        $user=$repository->findOneBy(array(
            'phoneNumber'=> $phoneNumber,
        ));
        $mouzaMapList = $this->get('app_bundle.service.api_manager')->getAllMouzaMapByUser($user->getPhoneNumber());
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $mouzaMapList,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/

        );
        return $this->render('UserBundle:User:appliedMouzaMap.html.twig', array(
            'user' => $user,
            'mouzaMapList' => $pagination
        ));
    }
    public function appliedCaseCopyAction(Request $request, $phoneNumber){

        $em=$this->getDoctrine()->getManager();
        $repository=$em->getRepository('UserBundle:User');
        $user=$repository->findOneBy(array(
            'phoneNumber'=> $phoneNumber,
        ));
        $caseCopyList = $this->get('app_bundle.service.api_manager')->getAllCaseCopyByUser($user->getPhoneNumber());
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $caseCopyList,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/

        );
        return $this->render('UserBundle:User:appliedCaseCopy.html.twig', array(
            'user' => $user,
            'caseCopyList' => $pagination
        ));
    }
    public function appliedInformationSlipAction(Request $request, $phoneNumber){

        $em=$this->getDoctrine()->getManager();
        $repository=$em->getRepository('UserBundle:User');
        $user=$repository->findOneBy(array(
            'phoneNumber'=> $phoneNumber,
        ));
        $informationSlipList = $this->get('app_bundle.service.api_manager')->getAllInformationSlipByUser($user->getPhoneNumber());
        $paginator  = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $informationSlipList,
            $request->query->getInt('page', 1)/*page number*/,
            10/*limit per page*/

        );
        return $this->render('UserBundle:User:appliedInformationSlip.html.twig', array(
            'user' => $user,
            'informationSlipList' => $pagination
        ));
    }
    public function profileEditAction(Request $request, $phoneNumber){

        $em=$this->getDoctrine()->getManager();
        $repository=$em->getRepository('UserBundle:User');
        $user=$repository->findOneBy(array(
            'phoneNumber'=> $phoneNumber,
        ));
        $form =  $this->createEditForm($user);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $user->getProfile()->upload();
            $this->doctrineManager()->persist($user);
            $this->doctrineManager()->flush();
            return $this->redirect($this->generateUrl('citizen_homepage'));
        }
        return $this->render('UserBundle:User:profileEdit.html.twig', array(
            'form' => $form->createView(),
            'user' => $user,
        ));
    }
    private function createEditForm(User $entity)
    {
        $form = $this->createForm(new ProfileEditType(), $entity, array(
            'action' => $this->generateUrl('profile_edit', array('phoneNumber' => $entity->getPhoneNumber())),
            'method' => 'PUT',
        ));

        return $form;

    }

    public function passwordChangeAction(Request $request,$phoneNumber)
    {
        $session = new Session();
        $em=$this->getDoctrine()->getManager();
        $repository=$em->getRepository('UserBundle:User');
        $user=$repository->findOneBy(array(
            'phoneNumber'=> $phoneNumber,
        ));
        $form =  $this->createForm(new PasswordChangeType(), $user);
        $form->handleRequest($request);
        if($session->get('phoneNumber')){
            if ($form->isValid()) {
                $user = $repository->findOneBy(array(
                    'phoneNumber' => $phoneNumber,
                    'password' => md5($request->request->all()['user_password_change']['Password']),
                ));
                if (isset($user)) {
                    $password = md5($user->getPassword());
                    $user->setPassword($password);
                    $this->doctrineManager()->persist($user);
                    $this->doctrineManager()->flush();
                    $this->addFlash('success', 'আপনার পাসওয়ার্ড সফলভাবে পরিবর্তন করা হয়েছে');
                    return $this->redirect($this->generateUrl('citizen_homepage'));
                }else{
                    $this->addFlash('error', 'আপনার পুরাতন পাসওয়ার্ড টি ভুল দেওয়া হয়েছে ');
                }
            }
        }
        else{
            $this->addFlash('error', 'আপনি লগইন নেই');
        }
        return $this->render('UserBundle:User:passwordChange.html.twig', array(
            'form' => $form->createView(),
        ));
    }
    public function forgetPasswordAction(Request $request)
    {
        if ($request->getMethod() == Request::METHOD_POST) {
            $data = $request->request->all();
            try {
                $phoneNumber = $data['mobile'];
                $hasUser = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->findOneByPhoneNumber($phoneNumber);
                if($hasUser){
                    $verificationCode = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
                    $session = new Session();
                    $session->set('verificationCode', $verificationCode);
                    $session->set('time', time());
                    $smsText = 'যাচাইকরণ কোড - '.$verificationCode.' ';
                    $this->get('app_bundle.service.sms_manager')->sendSms($phoneNumber, $smsText);
                    $this->addFlash('success', 'আপনার ফোন নম্বর এ যাচাইকারী কোড পাঠানো হয়েছে ');
                    return $this->redirectToRoute('forget_password_verification',array('phoneNumber'=>$phoneNumber));
                }
                else{
                    $this->addFlash('error', 'আপনার ফোন নম্বরটি  রেজিস্টার  করা নাই ');
                }
            } catch (\Exception $e) {
                $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
            }
        }
        return $this->render('UserBundle:User:forgetPassword.html.twig', array(

        ));
    }
    public function forgetPasswordVerificationAction(Request $request,$phoneNumber)
    {

        if ($request->getMethod() == Request::METHOD_POST) {
            $data = $request->request->all();
            try {
                $hasUser = $this->getDoctrine()->getManager()->getRepository('UserBundle:User')->findOneByPhoneNumber($phoneNumber);
                if($hasUser){
                    $session = new Session();
                    if ((time() - $session->get('time')) > (60 * 5)) {
                        $session->clear();
                        $this->addFlash('error', 'আপনার যাচাইকারী কোডটির মেয়াদ শেষ ');
                        return $this->redirectToRoute('forget_password');
                    }
                    $qDecoded=$session->get('verificationCode');
                    if($data['verificationCode']==$qDecoded) {
                        $randomPassword = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
                        $hasUser->setPassword(md5($randomPassword));
                        $this->doctrineManager()->persist($hasUser);
                        $this->doctrineManager()->flush();
                        $smsText = 'আপনার নতুন পাসওয়ার্ড হচ্ছে - ' . $randomPassword . ' ';
                        $this->get('app_bundle.service.sms_manager')->sendSms($phoneNumber, $smsText);
                        return $this->redirectToRoute('login');
                    }
                    else{
                        $this->addFlash('error', 'আপনার যাচাইকারী কোড টি সঠিক নয়');
                    }
                }

            } catch (\Exception $e) {
                $this->addFlash('error', 'আপনার আবেদন সফল ছিল না.');
            }
        }
        return $this->render('UserBundle:User:forgetPasswordVerification.html.twig', array(

        ));
    }


}
