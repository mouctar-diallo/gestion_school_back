<?php

namespace App\Helpers;

use Exception;
use PHPExcel_IOFactory;


class PromoHelper
{  
    /**
     * @param $file_path
     * @return array OR null
     */

    public  function ApprenantFromFileExcel($file_path)
    {
        if(file_exists($file_path)){
            //get extension file
            $inputFileType = PHPExcel_IOFactory::identify($file_path);
            try {
                //instanciation en fonction du type de fichier .xls, csv etc...
                $objRader = PHPExcel_IOFactory::createReader($inputFileType);
                $objPHPExcel = $objRader->load($file_path);
                if (isset($objPHPExcel)) {
                    return $this->getDataApprenantInArray($objPHPExcel->getActiveSheet()->toArray());
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
        return null;
    }

    /**
     * permet de permet recuper les infos du fichier excel et le mettre dans un tableau
     * @param $dataFromFileExecel
     * @return array
     */
    public  function getDataApprenantInArray($dataFromFileExecel)
    {
        $apprenant = [];
        $array = [];
        for ($index = 0; $index < count($dataFromFileExecel); $index++){
            //$dataFromFileExecel[0] donne l'entete du fichier 
            foreach ($dataFromFileExecel[0] as $key => $keyArray){
                //$dataFromFileExecel[$index+1] donne les valeurs Suivant
                if (!empty($dataFromFileExecel[$index+1])){
                    //met la ligne i colonne j de key dans le tableau
                    $array[$keyArray] = $dataFromFileExecel[$index+1][$key];
                }
            }
            array_push($apprenant,$array);
        }
        array_pop($apprenant);

        return $apprenant;
    }





    

    /*----------------------------------------------------------
        DEUXIEME METHODE
    /*----------------------------------------------------------*/

    // public function ExcelFile($file_path)
    // {
    //     $inputFileType = PHPExcel_IOFactory::identify($file_path);
    //     /**  Create a new Reader of the type defined in $inputFileType  **/
    //     $objReader = PHPExcel_IOFactory::createReader($inputFileType);
    //     $objPHPExcel = $objReader->load($file_path);
    //     $cell_collection = $objPHPExcel->getActiveSheet()->getCellCollection();
    //     //extract to a PHP readable array format
    //     foreach ($cell_collection as $cell) {
    //         $column = $objPHPExcel->getActiveSheet()->getCell($cell)->getColumn();
    //         $row = $objPHPExcel->getActiveSheet()->getCell($cell)->getRow();
    //         $data_value = $objPHPExcel->getActiveSheet()->getCell($cell)->getValue();
    //         //header will/should be in row 1 only. of course this can be modified to suit your need.
    //         if ($row == 1) {
    //             $header[$row][$column] = $data_value;
    //         } else {
    //             $arr_data[$row][$column] = $data_value;
    //         }
    //     }
    //     //send the data in an array format
    //     $data['header'] = $header;
    //     $data['values'] = $arr_data;
    //     return $arr_data;
    // }

    //------------------------------------------------------------------------------------------
    //------------------------------------------------------------------------------------------
    // if ($infosjson['groupes']) {
    //     $groupes = new Groupes;
    //     $groupes->setNom($infosjson['groupes'][0]['nom'])
    //         ->setDateCreation(new \DateTime())
    //         ->setStatut($infosjson['groupes'][0]['statut'])
    //         ->setType($infosjson['groupes'][0]['type']);
        
    //     $promos->addGroupe($groupes);
    // }

    // foreach ($infosjson['apprenants'] as $student) {
    //     $apprenant = new Apprenant();
    //     $apprenant->setEmail($student['email']);
    //     $apprenant->setPassword($this->encode->encodePassword($apprenant,$apprenant->getPassword()));
    //     $groupes->addApprenant($apprenant);
    //     $this->em->persist($apprenant);
    //     $this->userHelper->sendMail($apprenant->getEmail());
    // }

    //affectons un formateur au groupe
    // if (isset($infosjson['formateurs'])){
    //     for ($i= 0; $i < count($infosjson['formateurs']); $i++) {
    //         $teacher = $this->formateur->find($infosjson['formateurs'][$i]['id']);
    //         $groupes->addFormateur($teacher);
    //         $promos->addFormateur($teacher);
    //     }
    // }
}