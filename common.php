<?php
/**
 * Created by PhpStorm.
 * User: kandorm
 * Date: 17-4-8
 * Time: 下午10:09
 */
    require_once 'db.php';
    $FACE_API_KEY1 = "07fbd5f9f9924f0b9cb37ffaeb590638";
    $FACE_DETECT_URL = 'https://westus.api.cognitive.microsoft.com/face/v1.0/detect';
    $FACE_VERIFY_URL = 'https://westus.api.cognitive.microsoft.com/face/v1.0/verify';
    $PREDICTION_URL = 'https://ussouthcentral.services.azureml.net/workspaces/4def3382708a44f8b81e666369d6d232/services/b8186afb8ade4e36a0056b09676fed0c/execute?api-version=2.0&details=true';
    $PREDICTION_KEY = 'psFLzoVJY8xxVPjoKdfmroUAsumiBTOH1AO1pzTV9SkipkgYQbvOmj1XlBRqp/IsaHc58DfCR6J++dziyJwk2Q==';
    $HOME_PAGE = 'http://usedcarvaluation.southeastasia.cloudapp.azure.com/';
    function base64ToImg($image_code) {
        $img = str_replace('data:image/png;base64,', '', $image_code);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        return $data;
    }
    $user = null;
    if(key_exists('user_id', $_COOKIE)) {
        $user_id = $_COOKIE['user_id'];
        $redis->select(1);
        $age = $redis->get($user_id);
        $user = array("user_id" => $user_id, "age"=>$age);
    }

    $PREDICTION_PARAM = array(
        "make",
        "body-style",
        "wheel-base",
        "engine-size",
        "horsepower",
        "peak-rpm",
        "highway-mpg",
        "price");
?>

