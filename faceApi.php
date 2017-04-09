<?php
    require_once 'common.php';
    require_once 'HTTP/Request2.php';
    function face_verify($faceId1, $faceId2) {
        global $FACE_VERIFY_URL;
        global $FACE_API_KEY1;

        $request = new Http_Request2($FACE_VERIFY_URL);
        $url = $request->getUrl();

        $headers = array(
            // Request headers
            'Content-Type' => 'application/json',
            'Ocp-Apim-Subscription-Key' => $FACE_API_KEY1,
        );

        $request->setHeader($headers);

        $parameters = array(
            // Request parameters
        );

        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

        $body = array("faceId1"=>$faceId1, "faceId2"=>$faceId2);
        // Request body
        $request->setBody(json_encode($body));

        try
        {
            $response = $request->send();
            $result =  json_decode($response->getBody());
            if($result->{"isIdentical"} == "true")
                return true;
            else
                return false;
        }
        catch (HttpException $ex)
        {
            return false;
        }
    }

    function face_detect($image_code) {
        global $FACE_DETECT_URL;
        global $FACE_API_KEY1;

        $data = base64ToImg($image_code);

        $request = new Http_Request2($FACE_DETECT_URL);
        $url = $request->getUrl();

        $headers = array(
            // Request headers
            'Content-Type' => 'application/octet-stream',
            'Ocp-Apim-Subscription-Key' => $FACE_API_KEY1,
        );

        $request->setHeader($headers);

        $parameters = array(
            // Request parameters
            'returnFaceId' => 'true',
            'returnFaceLandmarks' => 'false',
            'returnFaceAttributes' => 'age',
        );

        $url->setQueryVariables($parameters);

        $request->setMethod(HTTP_Request2::METHOD_POST);

        // Request body
        $request->setBody($data);

        try
        {
            $response = $request->send();
            $person_array = json_decode($response->getBody());
            if(sizeof($person_array) == 0) {
                $result = array("msg" => "fail");
                return json_encode($result);
            }
            $default_person = $person_array[0];
            $msg = "success";
            $faceId = $default_person->{'faceId'};
            $age = $default_person->{'faceAttributes'}->{"age"};
            $result = array("msg" => $msg, "faceId" => $faceId, "age" => $age);
            return json_encode($result);
        }
        catch (HttpException $ex)
        {
            $result = array("msg" => "fail");
            return json_encode($result);
        }
    }
?>