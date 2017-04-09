<?php
/**
 * Created by PhpStorm.
 * User: kandorm
 * Date: 17-4-9
 * Time: 上午10:08
 */
    require_once 'common.php';

    function prediction($paramArray) {
        global $PREDICTION_PARAM;
        global $PREDICTION_URL;
        global $PREDICTION_KEY;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $PREDICTION_URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, "CognitiveServices");

        $headers = array();
        $headers[] = 'Content-Type:application/json';
        $headers[] = 'Authorization:Bearer '.$PREDICTION_KEY;
        $headers[] = 'Accept: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $columnnames = [];
        $values = [];
        foreach ($PREDICTION_PARAM as $param) {
            array_push($columnnames, $param);
            array_push($values, $paramArray[$param]);
        }

        $body = array(
            "Inputs"=>array(
                "input1"=>array(
                    "ColumnNames" => $columnnames,
                    "Values"=>array($values)
                )
            ),
            "GlobalParameters" => (object)array()
        );

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));

        try
        {
            $output = json_decode(curl_exec($ch));
            $output = $output->{"Results"};
            $output = $output->{"output1"};
            $output = $output->{"value"};
            $output = $output->{"Values"};
            return $output[0][7];
        }
        catch (HttpException $ex)
        {
            return false;
        }
        finally
        {
            curl_close($ch);
        }
    }