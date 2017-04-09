<?php
    require_once 'db.php';
    require_once 'faceApi.php';
    require_once 'common.php';

    $studentID = $_POST['studentID'];
    $image_code = $_POST['image_code'];
    $msg = "fail";
    if($studentID == null || $image_code == null) {
        $res ['err_no'] = 0;
        $res ['msg'] = $msg;
        die(json_encode($res));
    }

    $image_detect1 = json_decode(face_detect($image_code));
    $redis->select(0);
    $register_image_code = $redis->get($studentID);
    if(!$register_image_code) {
        $res ['err_no'] = 0;
        $res ['msg'] = $msg;
        die(json_encode($res));
    }
    $image_detect2 = json_decode(face_detect($register_image_code));
    $faceId1 = $image_detect1->{"faceId"};
    $faceId2 = $image_detect2->{"faceId"};

    if($faceId1 != null && $faceId2 != null) {
        if(face_verify($faceId1, $faceId2)) {
            $msg = "success";
            setcookie("user_id", $studentID, time()+3600);
        }
    }
    $res ['err_no'] = 0;
    $res ['msg'] = $msg;
    die(json_encode($res));

?>