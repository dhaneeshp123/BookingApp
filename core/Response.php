<?php


namespace app\core;


class Response
{
    public const RESPONSE_STATUS_SUCCESS = 'success';

    public const RESPONSE_STATUS_FAILED = 'failed';

    public const RESPONSE_FAILED_REASON_404 = 'Page/Method not found';

    public const RESPONSE_SUCCESS_CODE = 200;

    public const RESPONSE_PAGE_NOT_FOUND = 404;


    public function __construct()
    {
    }

    /**
     * @param array $jsonArray
     */
    public function outputJson(array $jsonArray)
    {
        header("Content-Type:application/json;chartset=utf-8");
        echo json_encode($jsonArray, true);
        exit;
    }

    public function getPageNotFoundResponse()
    {
        $this->outputJson(
            [
                'status' => self::RESPONSE_STATUS_FAILED,
                'statusCode' => self::RESPONSE_PAGE_NOT_FOUND,
                'message' => self::RESPONSE_FAILED_REASON_404,
            ]
        );
    }

    /**
     * @param array $result
     * @return array
     */
    public function getSuccessResponse(array $result): array
    {
        return
            [
                'status' => self::RESPONSE_STATUS_SUCCESS,
                'statusCode' => self::RESPONSE_SUCCESS_CODE,
                'result' => $result,
            ];
    }

    /**
     * @param array $result
     * @return array
     */
    public function getErrorResponse(array $result)
    {
        return
            [
                'status' => self::RESPONSE_STATUS_FAILED,
                'statusCode' => self::RESPONSE_SUCCESS_CODE,
                'errors' => $result,
            ];
    }

}
