<?php

namespace App\Controller;

use App\Entity\Invoices;
use App\Entity\Order;
use App\Entity\User;
use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AddressFormType;
use App\Service\AuthorizeDotNetService;
use Symfony\Contracts\Translation\TranslatorInterface;

use Psr\Log\LoggerInterface;


/**
 * Class AuthDotNetController
 * @package App\Controller
 * @Route("/authorize-dot-net")
 */
class AuthorizeDotNetController extends AbstractController
{
    /**
     * @Route("/callback", methods={"GET","POST"})
     * @return Response
     */
    public function callbackAction(Request $request,AuthorizeDotNetService $authDotNetService)
    {
        $log_file = getcwd() . "/../auhtorizeDotNet.log";


//        $logger->addDebug('AUTH_DOT_NET_START ' . (new \DateTime())->format('Y-m-d H:i:s'));
//        $logger->addDebug('post start ' . (new \DateTime())->format('Y-m-d H:i:s'));
//        $logger->addDebug(print_r($request->request->all(), 1));
//       $logger->addDebug('post end ' . (new \DateTime())->format('Y-m-d H:i:s'));
//        $logger->addDebug('raw post start ' . (new \DateTime())->format('Y-m-d H:i:s'));
        error_log('AUTH_DOT_NET_START ' . (new \DateTime())->format('Y-m-d H:i:s') . PHP_EOL, 3, $log_file);
        error_log(print_r($request->request->all(), true) . PHP_EOL, 3, $log_file);
        $raw = file_get_contents('php://input');
        error_log(print_r($raw, true) . PHP_EOL, 3, $log_file);

   //     $logger->addDebug(print_r($raw, 1));
  //      $logger->addDebug('raw post end ' . (new \DateTime())->format('Y-m-d H:i:s'));
   //     $logger->addDebug('AUTH_DOT_NET_END ' . (new \DateTime())->format('Y-m-d H:i:s'));


        $response = file_get_contents('php://input');


        try {
            $authDotNetService->storePaymentData();
        } catch (\Exception $e) {
           // $logger->addError('Error while processing AuthorizeDotNetInvoice\'s callback: ' . $e->getMessage());
          //  $logger->addError('LINE: ' . $e->getLine());
           // $logger->addError('FILE: ' . $e->getFile());
            $authDotNetService->pre([
                'Error while processing AuthorizeDotNetInvoice\'s callback: ' . $e->getMessage(),
                'LINE: ' . $e->getLine(),
                'FILE: ' . $e->getFile()
            ]);
        }
        error_log('AUTH_DOT_NET_END ' . (new \DateTime())->format('Y-m-d H:i:s') . PHP_EOL, 3, $log_file);
        return new Response('');

    }

    /**
     * @Route("/ajax/get-payment-form", name="get_authorize_payment_form")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getPaymentForm(Request $request,AuthorizeDotNetService $authDotNetService) {

        $data = [
            'status'    => 'error',
            'message'   => 'There is some error',
        ];

        $amount = $request->request->get('amount');

        $user = $this->getUser();

        try {

            if(
                !$amount
                ||
                !$user
            ) {
                throw new \Exception('Not valid data');
            }

            $title = "Expressposhta  pay from user #{$user->getId()}";


            $invoice = $authDotNetService->makeAuthorizeDotNetInvoice($amount, $title, $user,true);

            $token = $invoice->getFormToken();

            $view = $this->renderView('authorize_dot_net/popupOuter.html.twig', [
//                'buttonTitle'   => 'Pay!',
                'token'         => $token
            ]);

            $data = [
                'status'        => 'success',
                'data'          => $view,
            ];

        } catch (\Exception $e) {
            $data['message'] = $e->getMessage();
        }

        return new JsonResponse($data);
    }

//    /**
//     * @Route("/testpage1/", name="auth_testpage1")
//     *
//     * @return Response
//     */
//    public function getTestPage() {
//        return $this->render('@App/Backend/authorize_dot_net/test1.html.twig');
//    }

    /**
     * @Route("/inner-popup/", name="auth_inner_popup")
     *
     * @return Response
     */
    public function getInnerPopup() {
        return $this->render('authorize_dot_net/popupInner.html.twig');
    }

    /**
     * @Route("/iframe-communicator/", name="auth_iframe_communicator")
     *
     * @return Response
     */
    public function getIframeCommunicator() {
        return $this->render('authorize_dot_net/iframeCommunicator.html.twig');
    }

    /**
     * @Route("/try-paid", name="try_paid")
     *
     * @return Response
     */
    public function tryPaidAction(Request $request) {
        $entityManager = $this->getDoctrine()->getManager();
        $id=$request->get("id",false);
        if ($id){
            /** @var Invoices $invoice */
            $invoice=$entityManager->getRepository(Invoices::class)->find($id);
            if ($invoice)
            {
                $invoice->setTryPaid(true);
                $entityManager->persist($invoice);
                $entityManager->flush();
            }
        }
        return 'success';
    }
}