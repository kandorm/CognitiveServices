<?php
    require_once 'db.php';
    require_once 'faceApi.php';
    $studentID = $_POST['studentID'];
    $image_code = $_POST['image_code'];

    $image_detect = json_decode(face_detect($image_code));
    $msg = "fail";

    if($image_detect->{"msg"} == "success") {
        $redis->select(1);
        $redis->set($studentID, $image_detect->{"age"});
        $redis->select(0);
        $redis->set($studentID, $image_code);
        $msg = "success";
    }
    $res ['err_no'] = 0;
    $res ['msg'] = $msg;
    die(json_encode($res));

?>