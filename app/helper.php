<?php
function curl($url) {
    // cURL 세션 초기화
    $ch = curl_init();

    // cURL 옵션 설정
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  // SSL 인증서 검증 비활성화
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);  // 호스트 검증 비활성화
    curl_setopt($ch, CURLOPT_VERBOSE, true);          // 디버깅을 위한 상세 정보 출력

    // cURL 실행 및 결과 저장
    $response = curl_exec($ch);

    // 에러가 발생했다면 에러 정보 출력
    if (curl_errno($ch)) {
        echo 'Curl error: ' . curl_error($ch) . "\n";
    }

    // HTTP 응답 코드 확인
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // echo $http_code;

    // cURL 세션 닫기
    curl_close($ch);

    // HTTP 응답 코드가 200일 경우 JSON 데이터 출력
    if ($http_code == 200) {
        return json_decode($response, true);  // JSON 문자열을 PHP 배열로 변환하여 반환
    } else {
        echo $url."<br/>";
        echo "HTTP 요청 실패, 응답 코드: $http_code\n";
        echo "응답 내용: " . $response . "\n";  // 실패 시 응답 내용도 확인
        return null;
    }

}
