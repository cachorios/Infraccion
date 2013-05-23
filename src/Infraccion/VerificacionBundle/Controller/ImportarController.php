<?php

namespace Infraccion\VerificacionBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;

use Pagerfanta\View\TwitterBootstrapView;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Infraccion\VerificacionBundle\Entity\Importar;
use Infraccion\VerificacionBundle\Form\ImportarType;


/**
 * Importar controller.
 *
 */
class ImportarController extends Controller
{
    public function importarAction()
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Inicio", $this->get("router")->generate("home_page"));
        $breadcrumbs->addItem("Automotor", $this->get("router")->generate("automotor"));
        $breadcrumbs->addItem("Importar");

        $request = $this->getRequest();

        $importar = new Importar();
        $form = $this->createForm(new ImportarType(), $importar);

        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $file = $form['file']->getData();

                $directorio = $this->container->getParameter('directorio.importa');

                $file = $this->copiar($directorio, $file);
                $error = $this->procesarQuery($file, $importar);

                $this->get('session')->getFlashBag()->add('success', $error);
            } else {
                $this->get('session')->getFlashBag()->add('error', 'error. !!!');
            }
        }

        return $this->render('VerificacionBundle:Automotor:importar.html.twig', array(
            'form' => $form->createView()

        ));
    }

    public function phpexcel($file, $importar = null)
    {
        $excelObj = $this->get('xls.load_xls2007')->load($this->container->getParameter('directorio.importa') . $file);


        $objWorksheet = $excelObj->getActiveSheet();
        $highestRow = $objWorksheet->getHighestRow();

        $em = $this->getDoctrine()->getManager();

        $inicio = $importar->getFila();

        $end = $highestRow;

        $cell = $importar->getColumnaArray();
//        $cell = array("dominio" => "A", "marca" => "B", "modelo" => "C", "dni" => "D", "cuit_cuil" => "E", "nombre" => "F", "domicilio" => "G", "codigo_postal" => "H", "provincia" => "I", "localidad" => "J");

        $em->getConnection()->beginTransaction(); // suspend auto-commit
        try {

            for ($row = $inicio; $row <= $end; $row++) {

                $dominio = \addslashes($objWorksheet->getCell($cell["dominio"] . $row)->getValue());
                $dominio = preg_replace('/\s+/', ' ', $dominio);

                if (preg_match("/^[a-zA-Z][a-zA-Z][a-zA-Z][0-9][0-9][0-9]$/", $dominio)) {

                    $sql = "INSERT INTO automotorimportar(dominio, marca, modelo, dni, cuit_cuil, nombre, domicilio, codigo_postal, provincia, localidad)
                        VALUES ("
                        . '"' . \addslashes($objWorksheet->getCell($cell["dominio"] . $row)->getValue()) . '"' . ','
                        . '"' . \addslashes($objWorksheet->getCell($cell["marca"] . $row)->getValue()) . '"' . ','
                        . '"' . \addslashes($objWorksheet->getCell($cell["modelo"] . $row)->getValue()) . '"' . ','
                        . '"' . \addslashes($objWorksheet->getCell($cell["dni"] . $row)->getValue()) . '"' . ','
                        . '"' . \addslashes($objWorksheet->getCell($cell["cuit_cuil"] . $row)->getValue()) . '"' . ','
                        . '"' . \addslashes($objWorksheet->getCell($cell["nombre"] . $row)->getValue()) . '"' . ','
                        . '"' . \addslashes($objWorksheet->getCell($cell["domicilio"] . $row)->getValue()) . '"' . ','
                        . '"' . \addslashes($objWorksheet->getCell($cell["codigo_postal"] . $row)->getValue()) . '"' . ','
                        . '"' . \addslashes($objWorksheet->getCell($cell["provincia"] . $row)->getValue()) . '"' . ','
                        . '"' . \addslashes($objWorksheet->getCell($cell["localidad"] . $row)->getValue()) . '"' . ')';

                    $em->getConnection()->executeUpdate($sql);

                }
            }

            $em->getConnection()->commit();
        } catch (Exception $e) {
            $em->getConnection()->rollback();
            $em->close();
            //throw $e;
            return $e->getMessage();
        }

        return true;
    }

    public function actualizarTabla()
    {
        $em = $this->getDoctrine()->getManager();

        $queryUpdate = "Update automotor a  join automotorimportar b on a.dominio = b.dominio";
        $queryUpdate .= " set a.marca = COALESCE(b.marca,''),";
        $queryUpdate .= "    a.modelo = COALESCE(b.modelo,''),";
        $queryUpdate .= "    a.dni = COALESCE(b.dni,''),";
        $queryUpdate .= "    a.cuit_cuil = COALESCE(b.cuit_cuil,''),";
        $queryUpdate .= "    a.nombre = COALESCE(b.nombre,''),";
        $queryUpdate .= "    a.domicilio = COALESCE(b.domicilio,''),";
        $queryUpdate .= "    a.codigo_postal = COALESCE(b.codigo_postal,''),";
        $queryUpdate .= "    a.provincia = COALESCE(b.provincia,''),";
        $queryUpdate .= "    a.localidad = COALESCE(b.localidad,''),";
        $queryUpdate .= "    a.ultima_actualizacion = SYSDATE()";

        $em->getConnection()->executeUpdate($queryUpdate);

        $queryInsert = "insert into automotor( dominio, marca, modelo, dni, cuit_cuil, nombre, domicilio, codigo_postal, provincia, localidad, ultima_actualizacion )";
        $queryInsert .= " select dominio, marca, modelo, dni, cuit_cuil, nombre, domicilio, codigo_postal, provincia, localidad, SYSDATE() from automotorimportar";
        $queryInsert .= " where 0 in( select count(*) from automotor where automotor.dominio = automotor.dominio)";

        $em->getConnection()->executeUpdate($queryInsert);

    }

    public function vaciarTabla()
    {
        $em = $this->getDoctrine()->getManager();
        $em->getConnection()->executeUpdate('TRUNCATE TABLE automotorimportar;');
    }

    public function copiar($uploadDir, $file)
    {
        try {
            if (!is_dir($uploadDir)) {
                \mkdir($uploadDir);
            }

            if (\file_exists($uploadDir . $file)) {
                \unlink($uploadDir . $file);
            }

            $file->move($uploadDir, $file->getClientOriginalName());

        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $file->getClientOriginalName();
    }

    public function procesarQuery($file, $importar)
    {
        try {
            $this->phpexcel($file, $importar); //Leer archivo Excel.
            $this->actualizarTabla(); //Actualiza la tabla automotor.
            $this->vaciarTabla(); //Vacia la tabla automotorImportar

        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return 'Proceso terminado. !!!';
    }
}