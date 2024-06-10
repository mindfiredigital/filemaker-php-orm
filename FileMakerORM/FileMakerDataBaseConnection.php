<?php

class FileMakerDataBaseConnection{
     function connection($curlArray)
    {
            $con = curl_init();
            curl_setopt_array($con, array(
                        CURLOPT_URL => $curlArray['curlURL'],
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_SSL_VERIFYHOST => false,
                        CURLOPT_SSL_VERIFYPEER =>false,
                        CURLOPT_CUSTOMREQUEST =>$curlArray['curlRequest'],
                        CURLOPT_POSTFIELDS =>$curlArray['curlPostfields']??'',
                        CURLOPT_HTTPHEADER => $curlArray['curlHeaders']
            ));
            $response = curl_exec($con);
            curl_close($con);
            $jsonResponse = json_decode($response,true);
            
            return $jsonResponse;         
    }
}