<?php


namespace Tests\Json;


class JsonResponseBuilder
{
    private $message    = '';
    private $data       = [];
    private $error      = [];
    private $metadata   = [];
    private $is_success = true;

    /**
     * @param string $message
     * @return $this
     */
    public function setMessage($message = ''): JsonResponseBuilder
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @param array $error
     * @return $this
     */
    public function setError($error = []): JsonResponseBuilder
    {
        $this->is_success = false;
        $this->error = $error;

        return $this;
    }

    /**
     * @param array $data
     * @return $this
     */
    public function setData($data = []): JsonResponseBuilder
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param $metadata
     * @return $this
     */
    public function setMetadata($metadata): JsonResponseBuilder
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * @return array
     */
    public function get(): array
    {
        if ($this->message) {
            $response['message'] = $this->message;
        } else {
            $response[] = 'message';
        }

        if ($this->is_success) {
            $response['data'] = $this->data;
            $response['meta'] = $this->metadata;
        } else {
            $response['errors'] = $this->error;
        }

        return $response;
    }
}
