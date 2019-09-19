<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
* @Route("/api", name="api")
*/
class ApiController extends AbstractController
{
    /**
     * @Route("/entreprise", name="entreprise")
     */
    public function index(Request $request)
    {
        $headers=apache_request_headers();
        $signatureProjet=$headers['Authorization'];
        $data=$request->request->all();
        $reference=$data['reference']; 
        $signatureApi=$this->generateSignature($reference);
       //verification correspondance des clés
        if($signatureApi==$signatureProjet)
        {
            $conn = $this->getDoctrine()->getManager()->getConnection();
            $dql="SELECT * FROM entreprise where reference='$reference'";
            $entreprise=$conn->prepare($dql);
            $entreprise->execute();
            $response=$entreprise->fetchAll();
            $output = array('success' => true, 'data' => $response);
        }
        else{
            $output = array('success' => false,'data'=>null);
        }
        return $this->Json($output);
    }

    public function generateSignature($reference)
    {
        $sha="6c2cef9fe21832a232da7386e4775654";
        $cle_bin=pack('H*',$sha);
        $algo="SHA256";
        $message="cle_secrete=$sha";
        $message.="&reference=$reference";
        $signatureApi = strtoupper(hash_hmac(strtolower($algo), $message, $cle_bin));
      
    }
}
