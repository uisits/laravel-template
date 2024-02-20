<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use Helper;

class SlateWebService 
{
	public static function getOrientationInfo($uin)
	{
		$result = (array) null;

		$uri = env('SLATE_WS_ORIENTATIONS_URL_BY_UIN') . $uin;
		try
		{
			$body = Helper::getWebServiceData($uri, null, 'get', null, null, 'Authorization', env('SLATE_WS_ORIENTATIONS_AUTH'));

			if(isset($body->row))
			{
				$result = $body->row;
			}
		}
		catch (\Exception $e)
		{
			\Log::error('An Exception occured in getOrientationInfo: ' . $e->getMessage());
		}

		return $result;
	}


	public static function getAllOrientationInfo()
	{
		$result = (array) null;

		$uri = env('SLATE_WS_ORIENTATIONS_URL');
		try
		{
			$body = Helper::getWebServiceData($uri, null, 'get', null, null, 'Authorization', env('SLATE_WS_ORIENTATIONS_AUTH'));

			if(isset($body->row))
			{
				$result = $body->row;
			}
		}
		catch (\Exception $e)
		{
			\Log::error('An Exception occured in getOrientationInfo: ' . $e->getMessage());
		}

		return $result;
	}


}
