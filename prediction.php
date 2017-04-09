<?php
/**
 * Created by PhpStorm.
 * User: kandorm
 * Date: 17-4-9
 * Time: 上午11:52
 */
    require_once 'common.php';
    require_once 'predictionApi.php';

    $paramArray = array();
    foreach ($PREDICTION_PARAM as $param) {
        $value = $_POST[$param];
        if(!$value)
            $value = "0";
        $paramArray[$param] = $value;
    }

    $price = prediction($paramArray);

    $res ['err_no'] = 0;
    $res ['msg'] = "success";
    $res ['price'] = $price;
    die(json_encode($res));

?>