<?php

namespace App\Http\Response;

class Response
{
    private $status = 'success';
    private $code = 200;
    private $message = null;
    private $data = null;
    private $aditionalFields = [];
    private $errorMessage = null;

    public function setStatus($status): static
    {
        $this->status = $status;
        return $this;
    }

    public function setCode($code): static
    {
        $this->code = $code;
        return $this;
    }

    public function setMessage($message): static
    {
        $this->message = $message;
        return $this;
    }

    public function setErrorMessage($message): static
    {
        $this->errorMessage = $message;
        return $this;
    }

    public function setData($data): static
    {
        $this->data = $data;
        return $this;
    }

    public function setAditionalFields($aditionalField, $fieldName): static
    {
        $this->aditionalFields[$fieldName] = $aditionalField;
        return $this;
    }

    public function response()
    {   
        $tmp = [
            'status' => $this->status,
            'code' => $this->code,
            'timeResponse' => now()
        ];

        if ($this->errorMessage != null) {
            $tmp['errorMessage'] = $this->errorMessage;
        }

        if ($this->message != null) {
            $tmp['message'] = $this->message;
        }

        if (!empty($this->aditionalFields)) {
            foreach ($this->aditionalFields as $key => $value) {
                $tmp[$key] = $value;
            }
        }

        if ($this->data != null) {
            $tmp['data'] = $this->data;
        }

        return response()->json($tmp, $this->code);
    }
}