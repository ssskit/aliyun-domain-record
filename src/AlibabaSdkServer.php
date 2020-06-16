<?php

namespace AliyunIntegrate;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

/**
 * 阿里巴巴API SDK
 * https://help.aliyun.com/document_detail/29776.html?spm=a2c4g.11186623.6.655.11af3192wvduN8
 * Class AlibabaSdkServer
 * @package App\Services
 */
class AlibabaSdkServer
{
    protected static $product = 'Alidns';
    protected static $ver = '2015-01-09'; //接口版本
    protected static $host = 'alidns.aliyuncs.com';

    protected static function action($api,$options = [])
    {
        // 设置一个全局客户端
        AlibabaCloud::accessKeyClient(env('ALIBABASDK_ACCESSKEY'), env('ALIBABASDK_ACCESSKEYSECRET'))
            ->regionId(env('ALIBABASDK_REGIONID'))
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product(self::$product)
                // ->scheme('https') // https | http
                ->version(self::$ver)
                ->action($api)
                ->method('POST')
                ->host(self::$host)
                ->options([
                    'query' => $options
                ])
                ->request();

            return($result->toArray());

        } catch (ClientException $e) {
            return  $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            return  $e->getErrorMessage() . PHP_EOL;
        }
    }

    /**
     * 添加解析记录
     * @param $domain
     * @param $domain_type
     * @param $domain_rtype
     * @param $bind_ip
     * @return array|string
     */
    public static function addAlidns($domain,$domain_type,$domain_rtype,$bind_ip)
    {
        $query = [
            'DomainName' => $domain,
            'RR' => $domain_rtype,
            'Type' => $domain_type,
            'Value' => $bind_ip,
        ];
        return self::action('AddDomainRecord',$query);
    }

    /**
     * 修改解析记录
     * @param $record_id
     * @param $domain_type
     * @param $domain_rtype
     * @param $bind_ip
     * @return array|string
     */
    public static function updateAlidns($record_id,$domain_type,$domain_rtype,$bind_ip)
    {
        $query = [
            'RegionId' => env('ALIBABASDK_REGIONID'),
            'RecordId' => $record_id,
            'RR' => $domain_rtype,
            'Type' => $domain_type,
            'Value' => $bind_ip,
        ];
        return self::action('UpdateDomainRecord',$query);
    }

    /**
     * 删除解析记录
     * @param $record_id
     * @return array|string
     */
    public static function delAlidns($record_id)
    {
        $query = [
            'RegionId' => env('ALIBABASDK_REGIONID'),
            'RecordId' => $record_id,
        ];
        return self::action('DeleteDomainRecord',$query);
    }

    /**
     * 获取解析记录列表
     * @param $domain
     * @return array|string
     */
    public static function listAlidns($domain)
    {
        $query = [
            'RegionId' => env('ALIBABASDK_REGIONID'),
            'DomainName' => $domain,
        ];
        return self::action('DescribeDomainRecords',$query);
    }

    /**
     * 获取解析记录信息
     * @param $domain
     * @return array|string
     */
    public static function oneAlidns($record_id)
    {
        $query = [
            'RegionId' => env('ALIBABASDK_REGIONID'),
            'RecordId' => $record_id,
        ];
        return self::action('DescribeDomainRecordInfo',$query);
    }

    /**
     * 获取域名列表
     * @param string $page_number 获取页数
     * @param string $page_size   每页获取个数
     * @return array|string
     */
    public static function listDomain($page_number = '1',$page_size = '20')
    {
        $query = [
            'RegionId' => env('ALIBABASDK_REGIONID'),
            'PageNumber' => $page_number,
            'PageSize' => $page_size,
        ];
        return self::action('DescribeDomains',$query);
    }

    /**
     * 获取域名信息
     * @param string $domain 域名
     * @return array|string
     */
    public static function oneDomain($domain)
    {
        $query = [
            'RegionId' => env('ALIBABASDK_REGIONID'),
            'DomainName' => $domain,
        ];
        return self::action('DescribeDomainInfo',$query);
    }

    /**
     * 添加域名
     * @param $domain
     * @return array|string
     */
    public static function addDomain($domain)
    {
        $query = [
            'RegionId' => env('ALIBABASDK_REGIONID'),
            'DomainName' => $domain,
        ];
        return self::action('AddDomain',$query);
    }
}

