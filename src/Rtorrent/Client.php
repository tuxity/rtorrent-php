<?php

namespace Rtorrent;

class Client
{
    private $RPCClient;
    private $encoder;

    private function call($method, $params)
    {
        $request = new \PhpXmlRpc\Request($method, [ $this->encoder->encode($params) ]);
        $response = $this->RPCClient->send($request);

        if ($response->faultCode()) {
            return ['faultCode' => $response->faultCode(), 'faultString' => $response->faultString()];
        }
        return $this->encoder->decode($response->value());
    }

    public function __construct($addr)
    {
        $this->RPCClient = new \PhpXmlRpc\Client($addr);
        $this->encoder = new \PhpXmlRpc\Encoder();
        //$this->XMLRPC_client->setDebug(2);
    }

    public function get()
    {

    }

    public function getAll()
    {
        $params = [
            '', // view
            'd.get_hash=',
            'd.get_name=',
            'd.get_size_bytes=',
            'd.get_completed_bytes=',
            'd.get_down_rate=',
            'd.get_up_rate=',
            'd.get_down_total=',
            'd.get_up_total=',
            'd.is_open=',
            'd.is_active=',
            'd.is_hash_checking=',
            'd.get_ratio=',
            'd.get_chunk_size=',
            'd.get_size_chunks=',
            'd.get_completed_chunks=',
            'd.get_state_changed='
        ];

        return $this->call('d.multicall', $params);
    }

    public function add($torrent, $directory)
    {

    }

    public function delete($hash)
    {
        $this->stop($hash);
        return $this->call('d.erase', $hash);
    }

    public function start($hash)
    {
        $this->call('d.open', $hash);
        return $this->call('d.start', $hash);
    }

    public function stop($hash)
    {
        $resp = $this->call('d.stop', $hash);
        $this->call('d.close', $hash);
        return $resp;
    }

    public function pause($hash)
    {
        return $this->call('d.stop', $hash);
    }

    public function exists($hash)
    {
        return $this->call('d.get_name', $hash) ? true : false;
    }
}
