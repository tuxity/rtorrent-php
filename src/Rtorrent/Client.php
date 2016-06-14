<?php

namespace Rtorrent;

use \PhpXmlRpc\Client as XMLRPCClient;
use \PhpXmlRpc\Request as XMLRPCRequest;
use \PhpXmlRpc\Value as XMLRPCValue;

class Client
{
    private $XMLRPC_client;

    public function __construct($addr)
    {
        $this->XMLRPC_client = new XMLRPCClient($addr);
        //$this->XMLRPC_client->setDebug(2);
    }

    public function getDownloads()
    {
        $params = new XMLRPCValue([
            new XMLRPCValue(''), // view
            new XMLRPCValue('d.get_base_filename='),
            new XMLRPCValue('d.get_name='),
            new XMLRPCValue('d.get_base_path='),
            new XMLRPCValue('d.get_complete='),
            new XMLRPCValue('d.get_hash='),
            new XMLRPCValue('d.get_local_id='),
            new XMLRPCValue('d.get_ratio=')
        ], 'array');

        $request = new XMLRPCRequest('d.multicall', [$params]);
        $response = $this->XMLRPC_client->send($request);

        return $response;
    }
}
