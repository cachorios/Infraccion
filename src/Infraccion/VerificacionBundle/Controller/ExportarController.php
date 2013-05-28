<?php

namespace Infraccion\VerificacionBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Infraccion\VerificacionBundle\Entity\Exportar;
use Infraccion\VerificacionBundle\Form\ExportarType;

class ExportarController extends Controller
{
    public function indexAction()
    {
        $request = $this->getRequest();

        return $this->render('VerificacionBundle:Exportar:index.html.twig', array(

        ));
    }

    public function exportarAction()
    {
        try {
            $excelService = $this->get('xls.service_xls2007');

            // create the object see http://phpexcel.codeplex.com documentation
            $excelService->excelObj->getProperties()->setCreator("Rios Soft")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Actualizar");
            $excelService->excelObj->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Dominio');

            $ids = $this->addRegistro($excelService->excelObj->setActiveSheetIndex(0));


            $excelService->excelObj->getActiveSheet()->setTitle('Simple');
            // Set active sheet index to the first sheet, so Excel opens this as the first sheet
            $excelService->excelObj->setActiveSheetIndex(0);

            //actualiza registro con ultima fecha
            $this->setUltimaActualizacion($ids);
            //create the response
            $response = $excelService->getResponse();
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
            $response->headers->set('Content-Disposition', 'attachment;filename=automotor.xlsx');

            // If you are using a https connection, you have to set those two headers for compatibility with IE <9
            $response->headers->set('Pragma', 'public');
            $response->headers->set('Cache-Control', 'maxage=1');
            return $response;

        } catch (\Exception $e) {
            return $e->getMessage();
        }

    }

    public function addRegistro($excelObj)
    {

        try {
            $em = $this->getDoctrine()->getManager();
            $query = $em->getRepository('VerificacionBundle:Automotor')->getAutomotoresExportar();
            $results = $query->getQuery()->getArrayResult();

            $cantidadPermitido = 10000;
            $ids = array();
            $row = 2;
            if (count($results) != 0) {
                if (count($results) > $cantidadPermitido){
                    throw new \exception ('Error la cantidad de registros es de '.count($results).'.');
                }
                foreach ($results as $result) {
                    $excelObj->setCellValue('A' . $row, $result['dominio']);
                    $ids[] = $result['id'];
                    $row++;
                }

            }
            return $ids;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function setUltimaActualizacion($ids)
    {
        $em = $this->getDoctrine()->getManager();
        return $em->getRepository('VerificacionBundle:Automotor')->setUltimaActualizacion($ids);
    }
}
