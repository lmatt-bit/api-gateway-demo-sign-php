<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
include_once 'Util/Autoloader.php';

$demo = new Demo();
$demo->doPostString();

/**
*请求示例
*如一个完整的url为http://api.aaaa.com/createobject?key1=value&key2=value2
*$host为http://api.aaaa.com
*$path为/createobject
*query为key1=value&key2=value2
*/
class Demo
{
	private static $appKey = "appKey";
    private static $appSecret = "appSecret";
    private static $host = "https://apiatman.market.alicloudapi.com";


	/**
	*method=POST且是非表单提交，请求示例
	*/
	public function doPostString() {
		//域名后、query前的部分
		$path = "/translate_batch_v2";
		$request = new HttpRequest($this::$host, $path, HttpMethod::POST, $this::$appKey, $this::$appSecret);
		//传入内容是json格式的字符串
		$bodyContent = "{\"qs\": [\"nice to meet you.\", \"hello world\"], \"source\": \"en\", \"target\": \"zh\", \"domain\":\"medical\"}";

        //设定Content-Type，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_TYPE, ContentType::CONTENT_TYPE_JSON);
		
        //设定Accept，根据服务器端接受的值来设置
		$request->setHeader(HttpHeader::HTTP_HEADER_ACCEPT, ContentType::CONTENT_TYPE_JSON);
	//如果是调用测试环境请设置
		//$request->setHeader(SystemHeader::X_CA_STAG, "TEST");

		//注意：业务body部分，不能设置key值，只能有value
		if (0 < strlen($bodyContent)) {
			$request->setHeader(HttpHeader::HTTP_HEADER_CONTENT_MD5, base64_encode(md5($bodyContent, true)));
			$request->setBodyString($bodyContent);
		}

		//指定参与签名的header
		$request->setSignHeader(SystemHeader::X_CA_TIMESTAMP);

		$response = HttpClient::execute($request);
		print_r($response);
	}

}
