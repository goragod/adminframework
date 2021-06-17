<?php
/**
 * @filesource modules/demo/models/province.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Demo\Province;

use Kotchasan\ArrayTool;
use Kotchasan\Http\Request;
use Kotchasan\Province;

/**
 * คลาสสำหรับการโหลด ตำบล อำเภอ จังหวัด
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านข้อมูล อำเภอ สำหรับใส่ลงใน select
     *
     * @param int $provinceID
     *
     * @return array
     */
    public static function amphur($provinceID)
    {
        $query = static::createQuery()
            ->select('id', 'amphur')
            ->from('amphur')
            ->where(array('province_id', $provinceID))
            ->cacheOn();
        $result = array();
        foreach ($query->execute() as $item) {
            $result[$item->id] = $item->amphur;
        }
        return $result;
    }

    /**
     * อ่านข้อมูล ตำบล สำหรับใส่ลงใน select
     *
     * @param int $amphurID
     *
     * @return array
     */
    public static function district($amphurID)
    {
        $query = static::createQuery()
            ->select('id', 'district')
            ->from('district')
            ->where(array('amphur_id', $amphurID))
            ->cacheOn();
        $result = array();
        foreach ($query->execute() as $item) {
            $result[$item->id] = $item->district;
        }
        return $result;
    }

    /**
     * @param $provinceID
     * @param $amphurID
     * @param $districtID
     */
    public static function find($provinceID, $amphurID, $districtID)
    {
        return static::createQuery()
            ->from('province P')
            ->join('amphur A', 'INNER', array('A.province_id', 'P.id'))
            ->join('district D', 'INNER', array('D.amphur_id', 'A.id'))
            ->where(array(
                array('P.id', $provinceID),
                array('A.id', $amphurID),
                array('D.id', $districtID),
            ))
            ->toArray()
            ->cacheOn()
            ->first('province', 'amphur', 'district', 'zipcode');
    }

    /**
     * คืนค่า ตำบล อำเภอ จังหวัด
     *
     * @param Request $request
     *
     * @return JSON
     */
    public function get(Request $request)
    {
        // session, referer
        if ($request->initSession() && $request->isReferer()) {
            try {
                // ค่าที่ส่งมา
                $src = $request->post('srcItem')->toString();
                // อำเภอตามจังหวัดที่เลือก
                $amphur = self::amphur($request->post('provinceID')->toInt());
                // อำเภอที่ต้องการ ถ้าไม่มีใช้อำเภอรายการแรกสุด
                $amphurID = $request->post('amphurID')->toInt();
                $amphurID = isset($amphur[$amphurID]) ? $amphurID : ArrayTool::getFirstKey($amphur);
                if ($src == 'amphurID') {
                    // เลือกอำเภอ
                    $result = array(
                        'districtID' => self::district($amphurID),
                    );
                } elseif ($src == 'provinceID') {
                    // เลือกจังหวัด
                    $result = array(
                        'amphurID' => $amphur,
                        'districtID' => self::district($amphurID),
                    );
                }
                if (!empty($result)) {
                    // คืนค่า JSON
                    echo json_encode($result);
                }
            } catch (\Kotchasan\InputItemException $e) {
            }
        }
    }
}
