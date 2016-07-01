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
        $torrents = [];

        foreach ($this->call('d.multicall', $params) as $t) {
            $t_obj                   = new \stdClass();
            $t_obj->hash             = $t[0];
            $t_obj->name             = $t[1];
            $t_obj->size_bytes       = $t[2];
            $t_obj->completed_bytes  = $t[3];
            $t_obj->down_rate        = $t[4];
            $t_obj->up_rate          = $t[5];
            $t_obj->down_total       = $t[6];
            $t_obj->up_total         = $t[7];
            $t_obj->is_open          = $t[8];
            $t_obj->is_active        = $t[9];
            $t_obj->is_hash_checking = $t[10];
            $t_obj->ratio            = $t[11];
            $t_obj->chunk_size       = $t[12];
            $t_obj->size_chunks      = $t[13];
            $t_obj->completed_chunks = $t[14];
            $t_obj->state_changed    = $t[15];

            $torrents[$t[0]] = $t_obj;
        }

        return $torrents;
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
