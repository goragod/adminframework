<?php
/**
 * @filesource modules/index/models/amphur.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\Amphur;

use Gcms\Login;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=amphur
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Model extends \Kotchasan\Model
{
    /**
     * อ่านข้อมูล อำเภอ สำหรับใส่ลงในตาราง
     *
     * @param int $province_id
     *
     * @return array
     */
    public static function toDataTable($province_id)
    {
        $result = static::createQuery()
            ->select('id', 'amphur')
            ->from('amphur')
            ->where(array('province_id', $province_id))
            ->toArray()
            ->execute();
        if (empty($result)) {
            $result = array(
                array('id' => 10101, 'amphur' => ''),
            );
        }
        return $result;
    }

    /**
     * อ่านข้อมูล อำเภอ สำหรับใส่ลงใน select
     *
     * @param int $province_id
     *
     * @return array
     */
    public static function toSelect($province_id)
    {
        $query = static::createQuery()
            ->select('id', 'amphur')
            ->from('amphur')
            ->where(array('province_id', $province_id))
            ->cacheOn();
        $result = array();
        foreach ($query->execute() as $item) {
            $result[$item->id] = $item->amphur;
        }
        return $result;
    }

    /**
     * บันทึก (amphur.php)
     *
     * @param Request $request
     */
    public function submit(Request $request)
    {
        $ret = array();
        // referer, token, admin
        if ($request->initSession() && $request->isSafe()) {
            if (Login::isAdmin()) {
                // ค่าที่ส่งมา
                $details = $request->post('amphur', array())->topic();
                $province_id = $request->post('province_id')->toInt();
                if ($province_id > 0) {
                    $id_exists = array();
                    $save = array();
                    foreach ($request->post('id', array())->toInt() as $key => $value) {
                        if ($details[$key] != '') {
                            if (isset($id_exists[$value])) {
                                $ret['ret_id_'.$key] = Language::replace('This :name already exist', array(':name' => 'ID'));
                            } else {
                                $id_exists[$value] = $value;
                                $save[$key] = array(
                                    'id' => $value,
                                    'province_id' => $province_id,
                                    'amphur' => $details[$key],
                                );
                            }
                        }
                    }
                    if (empty($ret)) {
                        // ชื่อตาราง
                        $table_name = $this->getTableName('amphur');
                        // db
                        $db = $this->db();
                        // ลบข้อมูลเดิม
                        $db->delete($table_name, array(
                            array('province_id', $province_id),
                        ), 0);
                        // ปรับปรุงตาราง
                        $db->optimizeTable($table_name);
                        // เพิ่มข้อมูลใหม่
                        foreach ($save as $item) {
                            $db->insert($table_name, $item);
                        }
                        // คืนค่า
                        $ret['alert'] = Language::get('Saved successfully');
                        $ret['location'] = 'reload';
                        // เคลียร์
                        $request->removeToken();
                    }
                }
            }
        }
        if (empty($ret)) {
            $ret['alert'] = Language::get('Unable to complete the transaction');
        }
        // คืนค่า JSON
        echo json_encode($ret);
    }
}
