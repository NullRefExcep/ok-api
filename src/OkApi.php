<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2016 NRE
 */


namespace nullref\okapi;


class OkApi
{
    const METHOD_photos_getUploadUrl = 'photosV2.getUploadUrl';
    const METHOD_photos_commit = 'photosV2.commit';
    const METHOD_photos_addAlbumLike = 'photos.addAlbumLike';
    const METHOD_photos_addPhotoLike = 'photos.addPhotoLike';
    const METHOD_photos_createAlbum = 'photos.createAlbum';
    const METHOD_photos_deleteAlbum = 'photos.deleteAlbum';
    const METHOD_photos_deletePhoto = 'photos.deletePhoto';
    const METHOD_photos_deleteTags = 'photos.deleteTags';
    const METHOD_photos_editAlbum = 'photos.editAlbum';
    const METHOD_photos_editPhoto = 'photos.editPhoto';
    const METHOD_photos_getAlbumInfo = 'photos.getAlbumInfo';
    const METHOD_photos_getAlbumLikes = 'photos.getAlbumLikes';
    const METHOD_photos_getAlbums = 'photos.getAlbums';
    const METHOD_photos_getInfo = 'photos.getInfo';
    const METHOD_photos_getPhotoLikes = 'photos.getPhotoLikes';
    const METHOD_photos_getPhotoMarks = 'photos.getPhotoMarks';
    const METHOD_photos_getPhotos = 'photos.getPhotos';
    const METHOD_photos_getTags = 'photos.getTags';
    const METHOD_photos_setAlbumMainPhoto = 'photos.setAlbumMainPhoto';

    public $auth;
    public $log = false;

    protected $host = 'http://api.odnoklassniki.ru/fb.do';

    /**
     * OkApi constructor.
     * @param array $auth
     */
    public function __construct($auth = [])
    {
        $this->auth = $auth;
    }

    /**
     * @param $method
     * @param array $params
     * @return mixed
     * @throws Exception
     */
    public function makeRequest($method, $params = [])
    {
        $url = $this->buildUrl($method, $params);
        $request = curl_init($url);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($request);
        $data = json_decode($response, true);
        curl_close($request);
        if (isset($data['error_code'])) {
            throw new Exception($data['error_msg'], $data['error_code'], null, $data['error_data']);
        }
        return $data;
    }

    /**
     * @param $method
     * @param $params
     * @return string
     */
    protected function buildUrl($method, $params)
    {
        $builtParams = $this->buildParams($method, $params);

        $sig = $this->buildSig($builtParams);

        $url = $this->host .
            '?' . implode('&', $builtParams) .
            '&access_token=' . $this->auth['access_token'] .
            '&sig=' . $sig;
        return $url;
    }

    protected function buildParams($method, $params)
    {
        $newParams = array_merge($params, [
            'application_key' => $this->auth['application_key'],
            'method' => $method,
        ]);

        ksort($newParams);

        return array_map(function ($key, $value) {
            return $key . '=' . $value;
        }, array_keys($newParams), array_values($newParams));
    }

    /**
     * @param $params
     * @return string
     */
    protected function buildSig($params)
    {
        $suffix = md5($this->auth['access_token'] . $this->auth['client_secret']);
        $this->log('$suffix: ' . $suffix);
        $sig = md5(implode('', $params) . $suffix);
        $this->log('$sig: ' . $sig);
        return $sig;
    }

    /**
     * @param $val
     */
    protected function log($val)
    {
        if ($this->log) {
            echo $val . PHP_EOL;
        }
    }
}