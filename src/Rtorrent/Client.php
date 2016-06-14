<?php

namespace Rtorrent;

use \PhpXmlRpc\Client as XMLRPCClient;
use \PhpXmlRpc\Request as XMLRPCRequest;
use \PhpXmlRpc\Encoder as XMLRPCEncoder;

class Client
{
    private $XMLRPC_client;
    private $encoder;

    public function __construct($addr)
    {
        $this->XMLRPC_client = new XMLRPCClient($addr);
        $this->encoder = new XMLRPCEncoder();
        //$this->XMLRPC_client->setDebug(2);
    }

    public function getDownloads()
    {
        $method = 'd.multicall';
        $params = [
            '', // view
            'd.get_base_filename=',
            'd.get_name=',
            'd.get_base_path=',
            'd.get_complete=',
            'd.get_hash=',
            'd.get_local_id=',
            'd.get_ratio='
        ];

        $resp = $this->XMLRPC_client->send(new XMLRPCRequest($method, [ $this->encoder->encode($params) ]));

        return $this->encoder->decode($resp->value());
    }
}
