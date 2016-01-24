<?php namespace RobinCms\Core\Http;

class JsonResponse
{
    protected $success = true;
    protected $errors = [];
    protected $data;
    protected $message;
    protected $code;

    public function __construct($data = null, $success = true, array $errors = [], $code = 200)
    {
        $this->data    = $data;
        $this->success = count($errors) < 1? $success : false;
        $this->errors  = $errors;
        $this->code    = $code;
    }

    public function setError($error)
    {
        return $this->setErrors($error);
    }

    public function setCode($code)
    {
        $this->code = intval($code);
     
        return $this;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function setErrors($errors)
    {
        if (!is_array($errors)) {
            $errors = [$errors];
        }

        $this->errors  = array_merge($this->errors, $errors);
        $this->success = false;

        return $this;
    }

    public function setSuccess($success)
    {
        $this->success = (boolean) $success;

        return $this;
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    public function getJson()
    {
        return json_encode([
            'success' => $this->success,
            'errors'  => $this->errors,
            'data'    => $this->data,
            'message' => $this->message,
            'code'    => $this->code,
        ]);
    }
}