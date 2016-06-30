<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\okapi;


class PhotoUploader
{
    public $gid;
    public $aid;
    public $uid;

    /** @var  OkApi */
    protected $client;

    public function __construct(OkApi $client)
    {
        $this->client = $client;
    }

    public function uploadFile($file, $comment = '')
    {
        /** Step #1 */
        if (!file_exists($file)) {
            throw new \Exception('File not exists');
        }
        $params = [];
        if (isset($this->gid)) {
            $params['gid'] = $this->gid;
        }
        if (isset($this->aid)) {
            $params['aid'] = $this->aid;
        }
        if (isset($this->uid)) {
            $params['uid'] = $this->uid;
        }
        $result = $this->client->makeRequest(OkApi::METHOD_photos_getUploadUrl, $params);

        $photoId = $result['photo_ids'][0];
        $uploadUrl = $result['upload_url'];

        /** Step #2 */
        $data = $this->sendFile(realpath($file), $uploadUrl);


        return [
            'photoId' => $photoId,
        ];
    }

    protected function sendFile($path, $uploadUrl)
    {
        $cmd = '/usr/bin/curl -i -X POST -H "Content-Type: multipart/form-data"  ' . '-F "pic1=@' . $path . '" ' . $uploadUrl;

        $cmd = str_replace('https', 'http', $cmd);
        echo $cmd.PHP_EOL;
        $buffer = [];
        $return_var = null;
        $result = exec($cmd);

        echo $result;

        return $result;

    }

}