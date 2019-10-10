<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Entity\TransactionLiqPay;
use App\Entity\Address;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\AddressFormType;
use App\Service\LiqPayService;
use Symfony\Contracts\Translation\TranslatorInterface;

use App\Controller\CabinetController;


class DefaultController extends CabinetController
{

    public $user;
    public $my_address;

    /**
     * @Route("/post/dashboard", name="post_dashboard")
     */
    public function dashboardAction(): Response
    {
        $this->getTemplateData();
        $this->optionToTemplate['page_id']='post_dashboard';
        $this->optionToTemplate['page_title']='Dashboard';

        $this->user = $this->getUser();
        $my_address = $this->getMyAddress($this->user->getId());
        $ordersNew = $this->getDoctrine()
            ->getRepository(Order::class)
            ->getNewOrders($this->user->getId(),5);
        $ordersSend = $this->getDoctrine()
            ->getRepository(Order::class)
            ->getSendOrders($this->user->getId(),5);

        $payment = $this->getDoctrine()
            ->getRepository(TransactionLiqPay::class)
            ->getNewPayments($this->user->getId(),5);

        return $this->render('cabinet/dashboard/dashboard.html.twig',
            array_merge($this->optionToTemplate,
                [   'ordersNew'=>$ordersNew,
                    'ordersSend'=>$ordersSend,
                    'payment'=>$payment,
                    'addresses'=>$my_address,
                    ]
            ));
    }


    /**
     * @Route("/payment/check", name="payment_check")
     *
     */
    public function liqPayAction(Request $request,LiqPayService $liqPayService)
    {

        $data = $request->request->all();

        $response = '';

        try {

            $liqPayService->check($data);

            $response = 'success';
        } catch (Exception $e) {

        } finally {

        }

        return new Response($response);
    }

    /**
     * @Route("/payment/result", name="payment_result")
     *
     */
    public function paymentResultAction(Request $request,LiqPayService $liqPayService,TranslatorInterface $translateService)
    {

        $data = $request->request->all();

        $this->addFlash(
            'success',
            $translateService->trans("LiqPay Sucesss")
        );
        return $this->redirect($this->generateUrl('post_dashboard'));
    }

    /**
     * @Route("post/ajax/set-locale", name="ajax_post_set_locale")
     * @param Request $request
     * @return Response
     */
    public function ajaxSetLocaleAction(Request $request): Response
    {
        $this->getTemplateData();
        $entityManager = $this->getDoctrine()->getManager();
        $errors =[];
            $locale=$request->get('locale',null);
            if (!empty($locale) && !empty($this->user)){
                $this->user->setLocale($locale);
                $request->getSession()->set('_locale', $locale);
                $entityManager->persist($this->user);
                $entityManager->flush();
            }


        return new JsonResponse([$locale]);

    }

    /**
     * This is a regular Controller action.
     *
     * @Route("/label/{id}/pdf" , name="generate_pdf_label")
     */
    public function pdfAction($id,Request $request, \jonasarts\Bundle\TCPDFBundle\TCPDF\TCPDF $pdf)
    {
        if (!empty($id)) {
            $entityManager = $this->getDoctrine()->getManager();
            /** @var Order $order */
            $order = $entityManager
                ->getRepository(Order::class)
                ->find($id);
            if ($order) {

                $trNum=$order->getTrNum()??"TEST BARCODE 128";

            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('expressposhta.com');
            $pdf->SetTitle('expressposhta.com');
            $pdf->SetSubject('expressposhta.com');
            $pdf->SetKeywords('expressposhta.com');

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set font
            $pdf->SetFont('times', '', 12);

            $pdf->AddPage('P', 'A5');
            $pdf->setJPEGQuality(75);
                $style = array(
                    'border' => 2,
                    'vpadding' => 'auto',
                    'hpadding' => 'auto',
                    'fgcolor' => array(0,0,0),
                    'bgcolor' => false, //array(255,255,255)
                    'module_width' => 1, // width of a single module in points
                    'module_height' => 1 // height of a single module in points
                );

          //  $pdf->write2DBarcode('https://www.system.expressposhta.com/track/'.$trNum, 'QRCODE,H', 100, 10, 50, 50, $style, 'N');
            $pdf->Image(getcwd() . '/img/logo.png', 15, 10);
if (empty($order->getUser())) die("No user");
            // set some text to print
                $shipperName=$order->getUser()->getFullName();
                $shipperPhone=$order->getUser()->getPhone();



                $pdf->setCellPaddings(1, 0, 1, 0);
                $pdf->setCellMargins(1, 0, 1, 0);

                $pdf->MultiCell(40, 5, 'Shipper :', 0, 'L', 0, 0, '' ,'30', true);
                $pdf->MultiCell(40, 5, 'Contact :', 0, 'L', 0, 1, '' ,'', true);
                $pdf->MultiCell(40, 5, $shipperName, 0, 'L', 0, 0, '' ,'', true);
                $pdf->MultiCell(40, 5, $shipperPhone, 0, 'L', 0, 1, '' ,'', true);

                $pdf->Ln(4);


            /** @var Address $address */
            $address=$order->getAddresses();
         if (empty($address)) die("Empty Adress");
            $rName=$address->getFullName();
            $rPhone=$address->getPhone();
            $rAdress=$address->getAddress();
            $rCityZip=$address->getZip().' '.$address->getCity();
            $rCountry=$address->getCountry();



            // print a block of text using Write()
                $pdf->MultiCell(40, 5, 'Receiver :', 0, 'L', 0, 0, '' ,'', true);
                $pdf->MultiCell(40, 5, 'Contact :', 0, 'L', 0, 1, '' ,'', true);
                $pdf->MultiCell(40, 5, $rName, 0, 'L', 0, 0, '' ,'', true);
                $pdf->MultiCell(40, 5, $rPhone, 0, 'L', 0, 1, '' ,'', true);
                $pdf->MultiCell(40, 5, $rAdress, 0, 'L', 0, 0, '' ,'', true);
                $pdf->MultiCell(40, 5, '', 0, 'L', 0, 1, '' ,'', true);
                $pdf->MultiCell(40, 5, $rCityZip, 0, 'L', 0, 0, '' ,'', true);
                $pdf->MultiCell(40, 5, '', 0, 'L', 0, 1, '' ,'', true);
                $pdf->MultiCell(40, 5, $rCountry, 0, 'L', 0, 0, '' ,'', true);
                $pdf->Ln(4);

            // define barcode style
            // define barcode style
            $style = array(
                'position' => '',
                'align' => 'C',
                'stretch' => false,
                'fitwidth' => true,
                'cellfitalign' => '',
                'border' => true,
                'hpadding' => 'auto',
                'vpadding' => 'auto',
                'fgcolor' => array(0, 0, 0),
                'bgcolor' => false, //array(255,255,255),
                'text' => true,
                'font' => 'helvetica',
                'fontsize' => 8,
                'stretchtext' => 0
            );
// CODE 39 EXTENDED + CHECKSUM
                $pdf->Cell(0, 0, '', 0, 1);
            $pdf->Cell(0, 0, 'Order id: '.$order->getId(), 0, 1);
                $pdf->Cell(0, 0, '', 0, 1);
            //$pdf->SetLineStyle(array('width' => 1, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(255, 0, 0)));
            // $pdf->write1DBarcode('RA78963469438EN', 'S25', '', '', 120, 25, 0.4, $style, 'N');
            $pdf->write1DBarcode($trNum, 'C128', '', '', 120, 25, 0.4, $style, 'N');


            $pdf->Output('example_002.pdf', 'I');
            }
        }
    }
}

