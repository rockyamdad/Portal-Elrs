<?php
namespace AppBundle\Model;
use Tbbc\CacheBundle\Cache\CacheManagerInterface;
use Tbbc\CacheBundle\Cache\KeyGenerator\KeyGeneratorInterface;

class ApiManager {
    Private $apiEndpoint;

    private $cacheManager;
    private $keyGenerator;

    public function __construct($apiEndpoint, CacheManagerInterface $cacheManager, KeyGeneratorInterface $keyGenerator)
    {
        $this->apiEndpoint = $apiEndpoint;
        $this->cacheManager = $cacheManager;
        $this->keyGenerator = $keyGenerator;
    }

    public function getAllDivisions()
    {
        return $this->getThroughCacheManager('api/divisions', 'common');
    }
    public function getAllPorchaByUser($user)
    {
        return $this->curlRequest('api/allPorchaListByUser/'.$user);
    }
    public function getAllMouzaMapByUser($user)
    {
        return $this->curlRequest('api/allMouzaMapByUser/'.$user);
    }
    public function getAllCaseCopyByUser($user)
    {
        return $this->curlRequest('api/allCaseCopyByUser/'.$user);
    }
    public function getAllInformationSlipByUser($user)
    {
        return $this->curlRequest('api/allInformationSlipByUser/'.$user);
    }
    public function getAllDistricts()
    {
        return $this->getThroughCacheManager('api/allDistricts', 'common');

    }
    private  function curlRequest($url_param){
        $ch = curl_init();

        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $this->apiEndpoint . '/'.$url_param);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-ApiKey: 123'
        ]);

        //execute post
        $result = curl_exec($ch);

        $info = curl_getinfo($ch);

        //close connection
        curl_close($ch);
        if ($info['http_code'] == 200) {
            return json_decode($result);
        }

        if($info['http_code'] >= 500) {
            throw new \Exception('Service unavailable');
        }
    }
    public function getDistrictsByDivision($divisionId)
    {
        return $this->getThroughCacheManager('api/districts/'.$divisionId, 'common');
    }
    public function viewKhatian($id)
    {
        return $this->getThroughCacheManager('api/khatian-view/'.$id, 'common');

    }
    public function getUpozilasByDistrict($districtId)
    {
        return $this->getThroughCacheManager('api/upozilas/'.$districtId, 'common');

    }
    public function getMouzasByUpozila($upozilaId)
    {
        return $this->getThroughCacheManager('api/mouzas/'.$upozilaId, 'common');

    }
    public function getVolumesByMouzaAndSurveyType($mouzaId,$type)
    {
        return $this->getThroughCacheManager('api/volumes/'.$mouzaId.'/'.$type, 'common');

    }
    public function getAllJlnumbers()
    {
        $this->curlRequest('api/jlnumbers');

    }
    public function getAllSurveys()
    {
        return $this->getThroughCacheManager('api/surveys', 'common');
    }

    public function getUDCList()
    {
        return $this->getThroughCacheManager('api/udcList', 'common');
    }
    public function porchaApplicationCreate($data)
    {
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $this->apiEndpoint.'/api/save-khatian');
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-ApiKey: 123'
        ]);
        $result = curl_exec($ch);
        $info   = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] == 201) {
            return json_decode($result);
        }
        throw new \Exception($result);
    }
    public function porchaCorrectionApplicationCreate($data)
    {
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $this->apiEndpoint.'/api/save-khatian-Correction');
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-ApiKey: 123'
        ]);
        $result = curl_exec($ch);
        $info   = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] == 201) {
            return json_decode($result);
        }
        throw new \Exception($result);
    }
    public function porchaSearch($data)
    {
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $this->apiEndpoint.'/api/search-khatian');
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-ApiKey: 123'
        ]);
        $result = curl_exec($ch);
        $info   = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] == 201) {
            return json_decode($result);
        }
        throw new \Exception($result);
    }
    public function mouzaApplicationCreate($data)
    {
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $this->apiEndpoint.'/api/save-mouza');
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-ApiKey: 123'
        ]);
        $result = curl_exec($ch);
        $info   = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] == 201) {
            return json_decode($result);
        }
        throw new \Exception($result);
    }

    public function caseCopyApplicationCreate($data)
    {
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $this->apiEndpoint.'/api/save-case-copy');
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-ApiKey: 123'
        ]);
        $result = curl_exec($ch);
        $info   = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] == 201) {
            return json_decode($result);
        }
        throw new \Exception($result);
    }

    public function informationApplicationCreate($data)
    {
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL, $this->apiEndpoint.'/api/save-information-application');
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_POST, count($data));
        curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-ApiKey: 123'
        ]);
        $result = curl_exec($ch);
        $info   = curl_getinfo($ch);
        curl_close($ch);

        if ($info['http_code'] == 201) {
            return json_decode($result);
        }
        throw new \Exception($result);
    }
    public function getCourtFeesByOffice($districtId,$type)
    {
        return $this->getThroughCacheManager('api/court-fees/'.$districtId.'/'.$type, 'common');

    }

    public function getKahtianStatusByApplicationId($applicationId)
    {
        return $this->getThroughCacheManager('api/KhatianStatusByApplicationId/'.$applicationId, 'common');
    }

    public function getKahtianIdByApplicationId($applicationId)
    {
        return $this->getThroughCacheManager('api/KhatianIdByApplicationId/'.$applicationId, 'common');
    }

    public function getCaseCopyCourtFeesByOffice($districtId,$type)
    {
        return $this->getThroughCacheManager('api/court_fees_case_copy/'.$districtId.'/'.$type, 'common');

    }

    public function getMouzaApplicationCourtFeesByOffice($districtId,$type)
    {
        return $this->getThroughCacheManager('api/court_fees_mouza_application/'.$districtId.'/'.$type, 'common');

    }
    public function getInformationApplicationCourtFeesByOffice($districtId,$type)
    {
        return $this->getThroughCacheManager('api/court_fees_information_application/'.$districtId.'/'.$type, 'common');
    }
    public function getDeliveryFeesByOffice($districtId)
    {
        return $this->getThroughCacheManager('api/delivery-fees/'.$districtId, 'common');

    }
    public function getAllDashBoardStatistics()
    {
        return $this->getThroughCacheManager('api/stats', 'common');

    }

    /**
     * @param $url
     * @param $cacheDriver
     * @return mixed
     * @throws \Exception
     */
    protected function getThroughCacheManager($url, $cacheDriver)
    {
        $cacheKey = $this->keyGenerator->generateKey($url);
        $cache = $this->cacheManager->getCache($cacheDriver);

        if ($data = $cache->get($cacheKey)) {
            return $data;
        }

        $data = $this->curlRequest($url);

        $cache->set($cacheKey, $data);

        return $data;
    }
}
