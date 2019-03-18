<?php

namespace Hp;

//  PROJECT HONEY POT ADDRESS DISTRIBUTION SCRIPT
//  For more information visit: http://www.projecthoneypot.org/
//  Copyright (C) 2004-2019, Unspam Technologies, Inc.
//
//  This program is free software; you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation; either version 2 of the License, or
//  (at your option) any later version.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program; if not, write to the Free Software
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
//  02111-1307  USA
//
//  If you choose to modify or redistribute the software, you must
//  completely disconnect it from the Project Honey Pot Service, as
//  specified under the Terms of Service Use. These terms are available
//  here:
//
//  http://www.projecthoneypot.org/terms_of_service_use.php
//
//  The required modification to disconnect the software from the
//  Project Honey Pot Service is explained in the comments below. To find the
//  instructions, search for:  *** DISCONNECT INSTRUCTIONS ***
//
//  Generated On: Mon, 18 Mar 2019 15:06:31 -0400
//  For Domain: www.fak3r.com
//
//

//  *** DISCONNECT INSTRUCTIONS ***
//
//  You are free to modify or redistribute this software. However, if
//  you do so you must disconnect it from the Project Honey Pot Service.
//  To do this, you must delete the lines of code below located between the
//  *** START CUT HERE *** and *** FINISH CUT HERE *** comments. Under the
//  Terms of Service Use that you agreed to before downloading this software,
//  you may not recreate the deleted lines or modify this software to access
//  or otherwise connect to any Project Honey Pot server.
//
//  *** START CUT HERE ***

define('__REQUEST_HOST', 'hpr3.projecthoneypot.org');
define('__REQUEST_PORT', '80');
define('__REQUEST_SCRIPT', '/cgi/serve.php');

//  *** FINISH CUT HERE ***

interface Response
{
    public function getBody();
    public function getLines(): array;
}

class TextResponse implements Response
{
    private $content;

    public function __construct(string $content)
    {
        $this->content = $content;
    }

    public function getBody()
    {
        return $this->content;
    }

    public function getLines(): array
    {
        return explode("\n", $this->content);
    }
}

interface HttpClient
{
    public function request(string $method, string $url, array $headers = [], array $data = []): Response;
}

class ScriptClient implements HttpClient
{
    private $proxy;
    private $credentials;

    public function __construct(string $settings)
    {
        $this->readSettings($settings);
    }

    private function getAuthorityComponent(string $authority = null, string $tag = null)
    {
        if(is_null($authority)){
            return null;
        }
        if(!is_null($tag)){
            $authority .= ":$tag";
        }
        return $authority;
    }

    private function readSettings(string $file)
    {
        if(!is_file($file) || !is_readable($file)){
            return;
        }

        $stmts = file($file);

        $settings = array_reduce($stmts, function($c, $stmt){
            list($key, $val) = \array_pad(array_map('trim', explode(':', $stmt)), 2, null);
            $c[$key] = $val;
            return $c;
        }, []);

        $this->proxy       = $this->getAuthorityComponent($settings['proxy_host'], $settings['proxy_port']);
        $this->credentials = $this->getAuthorityComponent($settings['proxy_user'], $settings['proxy_pass']);
    }

    public function request(string $method, string $uri, array $headers = [], array $data = []): Response
    {
        $options = [
            'http' => [
                'method' => strtoupper($method),
                'header' => $headers + [$this->credentials ? 'Proxy-Authorization: Basic ' . base64_encode($this->credentials) : null],
                'proxy' => $this->proxy,
                'content' => http_build_query($data),
            ],
        ];

        $context = stream_context_create($options);
        $body = file_get_contents($uri, false, $context);

        if($body === false){
            trigger_error(
                "Unable to contact the Server. Are outbound connections disabled? " .
                "(If a proxy is required for outbound traffic, you may configure " .
                "the honey pot to use a proxy. For instructions, visit " .
                "http://www.projecthoneypot.org/settings_help.php)",
                E_USER_ERROR
            );
        }

        return new TextResponse($body);
    }
}

trait AliasingTrait
{
    private $aliases = [];

    public function searchAliases($search, array $aliases, array $collector = [], $parent = null): array
    {
        foreach($aliases as $alias => $value){
            if(is_array($value)){
                return $this->searchAliases($search, $value, $collector, $alias);
            }
            if($search === $value){
                $collector[] = $parent ?? $alias;
            }
        }

        return $collector;
    }

    public function getAliases($search): array
    {
        $aliases = $this->searchAliases($search, $this->aliases);
    
        return !empty($aliases) ? $aliases : [$search];
    }

    public function aliasMatch($alias, $key)
    {
        return $key === $alias;
    }

    public function setAlias($key, $alias)
    {
        $this->aliases[$alias] = $key;
    }

    public function setAliases(array $array)
    {
        array_walk($array, function($v, $k){
            $this->aliases[$k] = $v;
        });
    }
}

abstract class Data
{
    protected $key;
    protected $value;

    public function __construct($key, $value)
    {
        $this->key = $key;
        $this->value = $value;
    }

    public function key()
    {
        return $this->key;
    }

    public function value()
    {
        return $this->value;
    }
}

class DataCollection
{
    use AliasingTrait;

    private $data;

    public function __construct(Data ...$data)
    {
        $this->data = $data;
    }

    public function set(Data ...$data)
    {
        array_map(function(Data $data){
            $index = $this->getIndexByKey($data->key());
            if(is_null($index)){
                $this->data[] = $data;
            } else {
                $this->data[$index] = $data;
            }
        }, $data);
    }

    public function getByKey($key)
    {
        $key = $this->getIndexByKey($key);
        return !is_null($key) ? $this->data[$key] : null;
    }

    public function getValueByKey($key)
    {
        $data = $this->getByKey($key);
        return !is_null($data) ? $data->value() : null;
    }

    private function getIndexByKey($key)
    {
        $result = [];
        array_walk($this->data, function(Data $data, $index) use ($key, &$result){
            if($data->key() == $key){
                $result[] = $index;
            }
        });

        return !empty($result) ? reset($result) : null;
    }
}

interface Transcriber
{
    public function transcribe(array $data): DataCollection;
    public function canTranscribe($value): bool;
}

class StringData extends Data
{
    public function __construct($key, string $value)
    {
        parent::__construct($key, $value);
    }
}

class CompressedData extends Data
{
    public function __construct($key, string $value)
    {
        parent::__construct($key, $value);
    }

    public function value()
    {
        $url_decoded = base64_decode(str_replace(['-','_'],['+','/'],$this->value));
        if(substr(bin2hex($url_decoded), 0, 6) === '1f8b08'){
            return gzdecode($url_decoded);
        } else {
            return $this->value;
        }
    }
}

class FlagData extends Data
{
    private $data;

    public function setData($data)
    {
        $this->data = $data;
    }

    public function value()
    {
        return $this->value ? ($this->data ?? null) : null;
    }
}

class CallbackData extends Data
{
    private $arguments = [];

    public function __construct($key, callable $value)
    {
        parent::__construct($key, $value);
    }

    public function setArgument($pos, $param)
    {
        $this->arguments[$pos] = $param;
    }

    public function value()
    {
        ksort($this->arguments);
        return \call_user_func_array($this->value, $this->arguments);
    }
}

class DataFactory
{
    private $data;
    private $callbacks;

    private function setData(array $data, string $class, DataCollection $dc = null)
    {
        $dc = $dc ?? new DataCollection;
        array_walk($data, function($value, $key) use($dc, $class){
            $dc->set(new $class($key, $value));
        });
        return $dc;
    }

    public function setStaticData(array $data)
    {
        $this->data = $this->setData($data, StringData::class, $this->data);
    }

    public function setCompressedData(array $data)
    {
        $this->data = $this->setData($data, CompressedData::class, $this->data);
    }

    public function setCallbackData(array $data)
    {
        $this->callbacks = $this->setData($data, CallbackData::class, $this->callbacks);
    }

    public function fromSourceKey($sourceKey, $key, $value)
    {
        $keys = $this->data->getAliases($key);
        $key = reset($keys);
        $data = $this->data->getValueByKey($key);

        switch($sourceKey){
            case 'directives':
                $flag = new FlagData($key, $value);
                if(!is_null($data)){
                    $flag->setData($data);
                }
                return $flag;
            case 'email':
            case 'emailmethod':
                $callback = $this->callbacks->getByKey($key);
                if(!is_null($callback)){
                    $pos = array_search($sourceKey, ['email', 'emailmethod']);
                    $callback->setArgument($pos, $value);
                    $this->callbacks->set($callback);
                    return $callback;
                }
            default:
                return new StringData($key, $value);
        }
    }
}

class DataTranscriber implements Transcriber
{
    private $template;
    private $data;
    private $factory;

    private $transcribingMode = false;

    public function __construct(DataCollection $data, DataFactory $factory)
    {
        $this->data = $data;
        $this->factory = $factory;
    }

    public function canTranscribe($value): bool
    {
        if($value == '<BEGIN>'){
            $this->transcribingMode = true;
            return false;
        }

        if($value == '<END>'){
            $this->transcribingMode = false;
        }

        return $this->transcribingMode;
    }

    public function transcribe(array $body): DataCollection
    {
        $data = $this->collectData($this->data, $body);

        return $data;
    }

    public function collectData(DataCollection $collector, array $array, $parents = []): DataCollection
    {
        foreach($array as $key => $value){
            if($this->canTranscribe($value)){
                $value = $this->parse($key, $value, $parents);
                $parents[] = $key;
                if(is_array($value)){
                    $this->collectData($collector, $value, $parents);
                } else {
                    $data = $this->factory->fromSourceKey($parents[1], $key, $value);
                    if(!is_null($data->value())){
                        $collector->set($data);
                    }
                }
                array_pop($parents);
            }
        }
        return $collector;
    }

    public function parse($key, $value, $parents = [])
    {
        if(is_string($value)){
            if(key($parents) !== NULL){
                $keys = $this->data->getAliases($key);
                if(count($keys) > 1 || $keys[0] !== $key){
                    return \array_fill_keys($keys, $value);
                }
            }

            end($parents);
            if(key($parents) === NULL && false !== strpos($value, '=')){
                list($key, $value) = explode('=', $value, 2);
                return [$key => urldecode($value)];
            }

            if($key === 'directives'){
                return explode(',', $value);
            }

        }

        return $value;
    }
}

interface Template
{
    public function render(DataCollection $data): string;
}

class ArrayTemplate implements Template
{
    public $template;

    public function __construct(array $template = [])
    {
        $this->template = $template;
    }

    public function render(DataCollection $data): string
    {
        $output = array_reduce($this->template, function($output, $key) use($data){
            $output[] = $data->getValueByKey($key) ?? null;
            return $output;
        }, []);
        ksort($output);
        return implode("\n", array_filter($output));
    }
}

class Script
{
    private $client;
    private $transcriber;
    private $template;
    private $templateData;
    private $factory;

    public function __construct(HttpClient $client, Transcriber $transcriber, Template $template, DataCollection $templateData, DataFactory $factory)
    {
        $this->client = $client;
        $this->transcriber = $transcriber;
        $this->template = $template;
        $this->templateData = $templateData;
        $this->factory = $factory;
    }

    public static function run(string $host, int $port, string $script, string $settings = '')
    {
        $client = new ScriptClient($settings);

        $templateData = new DataCollection;
        $templateData->setAliases([
            'doctype'   => 0,
            'head1'     => 1,
            'robots'    => 8,
            'nocollect' => 9,
            'head2'     => 1,
            'top'       => 2,
            'legal'     => 3,
            'style'     => 5,
            'vanity'    => 6,
            'bottom'    => 7,
            'emailCallback' => ['email','emailmethod'],
        ]);

        $factory = new DataFactory;
        $factory->setStaticData([
            'doctype' => '<!DOCTYPE html>',
            'head1'   => '<html><head>',
            'head2'   => '<title>omen halfway www.fak3r.com frowzy long</title></head>',
            'top'     => '<body><div align="center">',
            'bottom'  => '</div></body></html>',
        ]);
        $factory->setCompressedData([
            'robots'    => 'H4sIAAAAAAAAA7PJTS1JVMhLzE21VSrKT8ovKVZSSM7PK0nNK7FVysvPzEtJrdDJy08sSs7ILEvVScvPyckvV7IDAOxce9M3AAAA',
            'nocollect' => 'H4sIAAAAAAAAA7PJTS1JVMhLzE21VcrL103NTczM0U3Oz8lJTS7JzM9TUkjOzytJzSuxVdJXsgMAKsBXli0AAAA',
            'legal'     => 'H4sIAAAAAAAAA6Va23LbOBJ936_AOluepMpx4iTjy9LjKsVREk1l7KwkJ5VHiIRIbEiCA4BSPF-_fQFIyrElT-1DHJICgUZfTp9u8NzLRanEwthM2d_2Xu6JVJVlI7NM13l37xqZhvuLc28v_nHuM7jwF_tPjo5fJuJ8cXEOY2rh_G2pfttLTWnsv5-8f_8-2bvw5y_wt4vzF4uL_XrhmmTrcDUcTtN3i9jufXsxL-Dxy6NErNXC4QNvxK1pBV0b-O3oOCmUFV7hg68wSHtFc1gl8VWY0Rf0o8xV7Z0wNEuhqkOx_-T0TVLgfU1ToQxvfk28lXw3mPIQr0k0lvLe_zZFnzVq_8nZWaLl-T-fPxelzgsvnj-_KGnclamff2wrideu0WAWGmVVI28rEBSuSun1SuEr7gCHNdbQmEpbmbalaR3-lltY5SSpSCOy9aaC16K0Y1TBWSJ1KWhUhg8nXmiHMh-9SVgzOOo0MbU6FN_wQSHdw5ZrguWkWLH03ljQLYm4ClPh5CeJEiyw17myTri0UFkLbti0rjArsBqIv4xTgOM9vGYRvYXsSJOaRlly6sZYsJj2Qi6XyqLqdL00FtVgaiFTXMUqBUqNWnm8H99j5XttPZZpIRppWT6pa1QAeBINe7r3e5vpFDXzK432eAm6BxvnZCrH-oI5SjLTmuapM4EuhGYiLZmlWCp0FPoRfnh1lOxWmUx9v_JM2ZUm9YHPpbcgcg6vw6S1lxA3y1LmqK6U_d3H4JMLs_r7AfDN8LYeltEEGVNTk_tWknclZNOwZy54CgeeOccHXzRrVnPoG-uiXF8ms8n8ejrD68-j6fzbbuy5nvKGTl8nN_tPjo-T2fg_N2Oa8AWgHvzJfsLAB_eN_xB7ltZUeN2CJU-T_6qUJRWeQKnaElhpECs3K9Y7voFgRz6Rpmy3Fvw-LaTNVYamIjVVIpVWLYO62UgvXxHyQTDgAwWBkPKsjlTmlnyHWAdylqoPjjs2fgz4u_vAf4Z3cUqrHAOcMd-dKEF-I3JrWnByZ_L8FvfirQ7aIkwWX7QLdsa_rA-efWG8OxC6f5App_OaNpHKsrzt8DqDhZ1ipyE7pSav9V-8eYJDULAkoP5eU24oWyXqAMa-tbXIpLYkH0vhB24OMfrC2MGDGDJO0Qx_thL3tobkWslMge4IlbwJFzQrvUg25qAjcD4F-FzQvUAgwB284oz0mCAMY1hzFFA5QGDIfMLEjFg-bNE8WHQU4cPEOQHWeI8hg2RiRA5FG9Z1WrYupK2gIwvO15CmLacjFRIzI7IsaYec0WuxAKfIaQ87ZQMtnSVgPoAP8vHdoC5zy7Kr6u-oEpNKABxBfAGQ_sG1dFjLmUoRkIPGJ4iwA7dBa4guH_HDEcc3hHajQGegEVUu17pGioba_DAdjxkEefxsfAWgdZpM4L8TDo6ruXg7HY8u_w8I47nXnKE0ZKRbsgtBybIPQ1ujVAxM0TFeJVFLhXLkYCWzAYjGnWlqVNNCmV7pjHGMJi97mSz7IbAoNwi4tfaFaf0Qvc4xcZ8icB2fJZeT0acNVPo0RCUIQWCENRNGdyiueIc18UACoUz9CDmYSVgmvHl4N987z2TEHkgFq2Bok5EXxjlRKUlKXJQGM70EXF8QHuYwoQ7srs-mEV9kzCdF1PsZoHeWb_H-GC9E10rAw2gmySp3ALyLlq4ZLl0kJaBvr36h8SEfNTggmFUMNL6bL97D9Le8lW6rDxhvdmYjUJYXHbBaAKcIFfXeM8GmyAvA4vJWYHWQEsoXkpwuq3QdyIa3vYvz1rUiP-hQVgNbB5gngEUilRpSJxB-IsSvgYHTug6RxLOT0U4UVxqeZKmJ8EORBq4C5YtrLcPokzfHyaNU_Te4K6HYCv3v1jBIwWKgnEdRcL3UgT2IoCLycNiaEW3HyD6O5ru94uNo-mWAgQBlp8k38cd4xso9ARZz_V7M6Wo7Neu9aj6e_rFBPjDFpym7dVmaNW6buEAgBm7DJrt14BUQOSG3D5ZhcKj0cPBT90ysC0OrhLqtZgcCfxoGuQoR94Od7g66TS4J2q5m492rT4d6gJINKBU7LhafSLG2byKGkjVIucAT35AwgAEHQI_kUDCYOJYLtscLyARCVZKWqTnv74bPEDQxVzMbvJmJf_36chBzgKhQEvEDjJCdZcYkFvmkcU7IFdbGMssY20zIewvdZZQucYw-f_40uRwN9Qk568FFl5EGtNpT5qhVSpSPnHA3fVgokXPVxW0AMPlJIGE9h6hIN5ACdR0SgoXsuNahwuwpW0gaha6YNSvGMe5QeGv6hsfAz6ZjcLSTs2T6bvY4oI7vtlvoUdTLpPZcyhLhhUK031pb6z9DB4GgWFHmFJ23jWfUuZlcfTggbc5GH8ZPZ8_4GjjSeApinySzh-Cis-jVu91yXl5fDbfmInllyAamhkncQTCkutEI75hCNEENJJtMg81pl7Hm_xoaWZDo4XeerC_Q5RYEjpruyyJaJFAWMGI57JBRAgoR5EL9s2Ymo-_YmdBkNp9OLueTjZrtlwDuO5VU8g5S1XscwIx1wxIttXJdhrRBCfZAkPdxjwgoDzUhMELUj6Y0VESksobyIG1LH3Jh1WwKvxP_sLXAwrGqo4Tg_Bj2lpNCX7wBJPvdnQPVsYpD8Y0N0Aouulv2acQ9mQWOiXsYcGemWDvt3HtF8LnDgc3efhpv0Frw-ONk9DXQy5rZ43DEEM1kj0TIn0UoYUMPcildCpxQQNlb6hqLVmMV1O2VSG2bathMm5daOXL1GjLX4jaiO_gk19JePCUExY7roi8Xj7m1pcQoq3h7LAXBV8geo-hDEGRgPYBNGpFCPYTNq03XvX6_oQVqAm0gNE_KwSrBwXJTw95gtkxhFtc10rXcmKxn25-t8SzlU8zEe3i9Begj6aqkT7muRrfqYRfibhnF-TAi5GI2M51c8VbhybWYf9ySzSNbmvQ2BFY0GzN3ugfjQBfvJnGt1wnB-NXsQX_ocwkrHkqheaF6aqe9ohwr3jLp91Q4sYmxB7ez9Ij8it44ELKFqs0yDKotySLtmDw3LwLDqLvG6E9I9tM-L96z7RewIHWHHXLr6hZ9y1uIBIDulapbgpfr6c8qYvw4iogPxWYKlT3EjKxz1beFOD3V5P0lZ6-MXmgA1HDdNcaSV7hhatwRmSJtQmW5kw4U0q64pt5S6EU3gXjON7gZKys0vQTwnIZzmaP6hM4BHpw0GxD_tEAyx5Fb9hP7IphDcBrv_D-ojgv2smQGREtjwjR5fcd-WzNy5KK_30w30hTsFnbEuUdaSI4108fdyWGTcpcytmv2Rgy4FXMlVSNe0fUMy7twSoIVooAHMSCQd5GSFPLSxt6FqukdwD5FfDh9nWwC-QbBNCB2OLnhoxvsI0H1RcsLzvSsgtB9W1oJ_t2mlC_beokNBTXAtnCydSs66YBDzebXU4IJvH_HtQUzrPcTmrctU3BaW9z6otJ0oDJm48M7l3zEA9HgSk7fjbLOYKltrc7YOKCpQI3Gh6G1Sn1YGo8i2gyCsDQNyFqpDFKMBnpYljrnozAJE4Y22MOAR8G_mQ9uZuN7AnpLgo_h9nQvtEgGlQznVp2LpWpRxNrwqeqgh0uFBdaG-ALVWnisFRhHDECxF4C5b6JyXzR4Hx05cpCA9kxo9NAK8xBnVUgsRC1DJnUu5vRNQDwN6P_84w3R4g0HI7iCyvgR532x4XR2mrQMhWvwNbVsS6Fq0-aFANqA7gnEINNlu0KmQCCrF-09zW7L57QwhYVictBMwfNcZoYb8BD6f8oHyOJMxDXb0Wmiwc8HGY0DmNpZAyKMlYSkIA1HbgvnuQwPiYlglt7Qf8F8u0_XHqG4OyaZveO0oOr6VjiDhEq7Cq4aiw1Bg2pCJr5Zd7D3yQx4i2pwCLWFFxTGeOTUdV57HZj9_hSI_F7MAkXzai88oG4VIUi7Oygil-Z4IByEQoO8VpBfDNOMTAtGMOw3WnVHCdtwvmsJv72ZERht1CQho1ACYdK690zo5SMKVHxbQNk5DDw-OJAuZGogvBJP36W9FQ2Uoa6gs3hZGkB0b34A-AG6aZMxCpq6o-QUaB-2JPJYOsynI6J9UwGV5Ww-6ZRyH6YNSdpXlnsxOBMLlsATFr2lcIz229Gwinp_um2uyAa6A2jCKaZASzFTXasUgBAPM7jU4jLergNSBRyknhi9tNJkS7PFjN97KshgcRflfioEmFhvBtHOzfP3HrF0oOO-_fhJA4he9UVZZDwQxVwveHSVJT_ekDBUOpjQ2GdZzCWge9Fygh1m6mpASwZdg02ykvIpM4DHfmjGZ5qJSGA_B4ECyt0qXWneaik3zlE2syYivA-lXkxGmOkMgzjiiKE655nAjYVulHI6Hn1h53vL0YnfSDPcGoEUhzqNOjyK9RQGY9_hyqXNYkf3AdnZKFlXR-_EbMAdtvKaWrGwaNtXdK_6LgL6B9aLG_YG1Dp5mWAX6vQVdqnw2fxmPia2Jzj8d4Q8YlrYLK_ILZNMd4V7Rznw-x6oku0z5hyp2nt2OBixQF_hzyvkdyD87HDA51QFnApS9FpayD6tzXUKd_i1Ee1OCcmovYa5RcwldwJvek9JqZiwNk1wzEd8NbAI_TEsThg7DHf2kAqAp1O1UlEi-9rDXvznW5AbP44ib6fdFVAG-Eo64degEG_KjFnm0hgvUm1ToG3qx-Couhar-GWJj8GN1wGpwrERm0KXsutAk2DcCax9AdNJai5wVYhBDykkZCwSFTak-eOCEs8AM3m7MOa7cLIE03jT5LIsJTPlnLrGetuZTIyZYJ1HgOem_Yax4lr6hClCMhM88OyCDFCCc_hQSFAsBcQOR7Mc5jsgNgrRJ7KBqlF_ih1uyxQxk_23BQMT3dsGKbJvpDE1wj1ltq2_91SUOuyhWlX8RSDAWmC6dAh9lmDM7fdfkNz9V4fDGjpKIPDyIWjNMFMHD0JeG_uPkaT-bI35tRixNB_7hyM6A4h_zpLP89EjoCTwY_bHSJLDxwyST90fg8pkms8lcCU-c8hCK3fIugMN6AIzZK_7lNafnp4F_VCCa8OhwKI7fR6-FLs-nr_vCV9_eakZ3w42UmR_w1_pEJ5od58w1vdkhr8hisksxD_5Ar2aiRC_lcnwu5bGQKVKJNFVAC4FUJ6VolLH9x1f3fU9qNEUzoLDEgRM90kl-z4i71MHNkUTOXc4HDyZ0scgeHl1ORbYSLnHNV7QV70vqG6FC_j1f85nYLAaLAAA',
            'style'     => 'H4sIAAAAAAAAAyXMPQqAMAwG0KsIrv6urTj2HtGmUCj5JM1QEe_u4DvA26rdhXeaosLQ8vWcKFDXhxB8gpg7UGK3LlfrSDOVoZLUsbLm5I2bjZFPKFmGOIGwf7f5Pz855TlPWwAAAA',
            'vanity'    => 'H4sIAAAAAAAAA22S3U7DMAyFX8XKbmEdf5OWtRViGkJIsGnABZdpk7WBEEeOWbe3Jy3jBlBkKZbj7xwnyVlVzkBtnItB1dY3hZiIPg1K62NaIWlD_S7ywZlCVKp-bwg_vZaj2Ww276zmVp5fTMJ-LsqcKYWGnXK28YVgDD-NR6iEs7CH8xRXKS5T17fEKdmmZRnRWT0cGS0Wi56YvHk4MrboWVboNPR6oMgqdxKVj6fRkN3Oa3RIcjSdTudJWfaeAkbLFr0k4xTbnUnM6zzrqWWesf5jF457Z7Ys4Jf5i6Q6Sevye1oFLZltIVrmILOs67pxIHwzNbfozSEgj5GaTEDtVIyF0ISMextE-bB8uFluYHUL683qfrl4hrvV4_IV1qvnPFNlXtG_9E-fjH-Ma_wQf5BPqQJ3inYmsiFYp0IykkaHR8Md0nsPTfZ2VhsN1QFeBtggN1xE1j9eNvyK8gv57FSQHQIAAA',
        ]);
        $factory->setCallbackData([
            'emailCallback' => function($email, $style = null){
                $value = $email;
                $display = 'style="display:' . ['none',' none'][random_int(0,1)] . '"';
                $style = $style ?? random_int(0,5);
                $props[] = "href=\"mailto:$email\"";
        
                $wrap = function($value, $style) use($display){
                    switch($style){
                        case 2: return "<!-- $value -->";
                        case 4: return "<span $display>$value</span>";
                        case 5:
                            $id = '1itr738st';
                            return "<div id=\"$id\">$value</div>\n<script>document.getElementById('$id').innerHTML = '';</script>";
                        default: return $value;
                    }
                };
        
                switch($style){
                    case 0: $value = ''; break;
                    case 3: $value = $wrap($email, 2); break;
                    case 1: $props[] = $display; break;
                }
        
                $props = implode(' ', $props);
                $link = "<a $props>$value</a>";
        
                return $wrap($link, $style);
            }
        ]);

        $transcriber = new DataTranscriber($templateData, $factory);

        $template = new ArrayTemplate([
            'doctype',
            'injDocType',
            'head1',
            'injHead1HTMLMsg',
            'robots',
            'injRobotHTMLMsg',
            'nocollect',
            'injNoCollectHTMLMsg',
            'head2',
            'injHead2HTMLMsg',
            'top',
            'injTopHTMLMsg',
            'actMsg',
            'errMsg',
            'customMsg',
            'legal',
            'injLegalHTMLMsg',
            'altLegalMsg',
            'emailCallback',
            'injEmailHTMLMsg',
            'style',
            'injStyleHTMLMsg',
            'vanity',
            'injVanityHTMLMsg',
            'altVanityMsg',
            'bottom',
            'injBottomHTMLMsg',
        ]);

        $hp = new Script($client, $transcriber, $template, $templateData, $factory);
        $hp->handle($host, $port, $script);
    }

    public function handle($host, $port, $script)
    {
        $data = [
            'tag1' => '8b05dbeafa73da91ff9ca0bd37d60630',
            'tag2' => '0f4cde978b1ec980e98da0f1c11f6018',
            'tag3' => '3649d4e9bcfd3422fb4f9d22ae0a2a91',
            'tag4' => md5_file(__FILE__),
            'version' => "php-".phpversion(),
            'ip'      => $_SERVER['REMOTE_ADDR'],
            'svrn'    => $_SERVER['SERVER_NAME'],
            'svp'     => $_SERVER['SERVER_PORT'],
            'sn'      => $_SERVER['SCRIPT_NAME']     ?? '',
            'svip'    => $_SERVER['SERVER_ADDR']     ?? '',
            'rquri'   => $_SERVER['REQUEST_URI']     ?? '',
            'phpself' => $_SERVER['PHP_SELF']        ?? '',
            'ref'     => $_SERVER['HTTP_REFERER']    ?? '',
            'uagnt'   => $_SERVER['HTTP_USER_AGENT'] ?? '',
        ];

        $headers = [
            "User-Agent: PHPot {$data['tag2']}",
            "Content-Type: application/x-www-form-urlencoded",
            "Cache-Control: no-store, no-cache",
            "Accept: */*",
            "Pragma: no-cache",
        ];

        $subResponse = $this->client->request("POST", "http://$host:$port/$script", $headers, $data);
        $data = $this->transcriber->transcribe($subResponse->getLines());
        $response = new TextResponse($this->template->render($data));

        $this->serve($response);
    }

    public function serve(Response $response)
    {
        header("Cache-Control: no-store, no-cache");
        header("Pragma: no-cache");

        print $response->getBody();
    }
}

Script::run(__REQUEST_HOST, __REQUEST_PORT, __REQUEST_SCRIPT, __DIR__ . '/phpot_settings.php');

