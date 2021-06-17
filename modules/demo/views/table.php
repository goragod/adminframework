<?php
/**
 * @filesource modules/demo/views/table.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Demo\Table;

use Kotchasan\DataTable;
use Kotchasan\Date;
use Kotchasan\Http\Request;

/**
 * module=demo-table
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * จัดรูปแบบการแสดงผลในแต่ละแถว
     *
     * @param array  $item ข้อมูลแถว
     * @param int    $o    ID ของข้อมูล
     * @param object $prop กำหนด properties ของ TR
     *
     * @return array คืนค่า $item กลับไป
     */
    public function onRow($item, $o, $prop)
    {
        $item['create_date'] = Date::format($item['create_date'], 'd M Y');
        if ($item['active'] == 1) {
            $item['active'] = '<a id=access_'.$item['id'].' class="icon-valid access" title="{LNG_Can login}"></a>';
            $item['lastvisited'] = empty($item['lastvisited']) ? '-' : Date::format($item['lastvisited'], 'd M Y H:i').' ('.number_format($item['visited']).')';
        } else {
            $item['active'] = '<a id=access_'.$item['id'].' class="icon-valid disabled" title="{LNG_Unable to login}"></a>';
            $item['lastvisited'] = '-';
        }
        if ($item['social'] == 1) {
            $item['social'] = '<span class="icon-facebook notext"></span>';
        } elseif ($item['social'] == 2) {
            $item['social'] = '<span class="icon-google notext"></span>';
        } else {
            $item['social'] = '';
        }
        $item['status'] = isset(self::$cfg->member_status[$item['status']]) ? '<span class=status'.$item['status'].'>{LNG_'.self::$cfg->member_status[$item['status']].'}</span>' : '';
        $item['phone'] = self::showPhone($item['phone']);
        $item['name'] = preg_replace('/[^\s]/', 'x', $item['name']);
        return $item;
    }

    /**
     * แสดงตาราง
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // สถานะสมาชิก
        $member_status = array(-1 => '{LNG_all items}');
        foreach (self::$cfg->member_status as $key => $value) {
            $member_status[$key] = '{LNG_'.$value.'}';
        }
        // URL สำหรับส่งให้ตาราง
        $uri = $request->createUriWithGlobals(WEB_URL.'index.php');
        // ตาราง
        $table = new DataTable(array(
            /* Uri */
            'uri' => $uri,
            /* Model */
            'model' => \Demo\Table\Model::toDataTable(),
            /* รายการต่อหน้า */
            'perPage' => $request->cookie('table_perPage', 30)->toInt(),
            /* เรียงลำดับ */
            'sort' => $request->cookie('table_sort', 'id desc')->toString(),
            /* ฟังก์ชั่นจัดรูปแบบการแสดงผลแถวของตาราง */
            'onRow' => array($this, 'onRow'),
            /* คอลัมน์ที่ไม่ต้องแสดงผล */
            'hideColumns' => array('id', 'visited', 'website'),
            /* คอลัมน์ที่สามารถค้นหาได้ */
            'searchColumns' => array('name', 'username', 'phone'),
            /* ตั้งค่าการกระทำของของตัวเลือกต่างๆ ด้านล่างตาราง ซึ่งจะใช้ร่วมกับการขีดถูกเลือกแถว */
            'action' => 'index.php/demo/model/table/action',
            'actionCallback' => 'dataTableActionCallback',
            'actions' => array(
                array(
                    'id' => 'action',
                    'class' => 'ok',
                    'text' => '{LNG_With selected}',
                    'options' => array(
                        'sendpassword' => '{LNG_Get new password}',
                        'active_1' => '{LNG_Can login}',
                        'active_0' => '{LNG_Unable to login}',
                        'delete' => '{LNG_Delete}',
                    ),
                ),
                array(
                    'class' => 'float_button icon-register',
                    'href' => $uri->createBackUri(array('module' => 'demo', 'page' => 'form')),
                    'title' => '{LNG_Register}',
                ),
            ),
            /* ตัวเลือกด้านบนของตาราง ใช้จำกัดผลลัพท์การ query */
            'filters' => array(
                'status' => array(
                    'type' => 'checkbox',
                    'name' => 'status',
                    'default' => -1,
                    'text' => '{LNG_Member status}',
                    'options' => $member_status,
                    'value' => $request->request('status', -1)->toInt(),
                ),
            ),
            /* ส่วนหัวของตาราง และการเรียงลำดับ (thead) */
            'headers' => array(
                'name' => array(
                    'text' => '{LNG_Name}',
                    'sort' => 'name',
                ),
                'active' => array(
                    'text' => '',
                    'colspan' => 2,
                ),
                'phone' => array(
                    'text' => '{LNG_Phone}',
                    'class' => 'center',
                ),
                'status' => array(
                    'text' => '{LNG_Member status}',
                    'class' => 'center',
                ),
                'create_date' => array(
                    'text' => '{LNG_Created}',
                    'class' => 'center',
                ),
                'lastvisited' => array(
                    'text' => '{LNG_Last login} ({LNG_times})',
                    'class' => 'center',
                    'sort' => 'lastvisited',
                ),
            ),
            /* รูปแบบการแสดงผลของคอลัมน์ (tbody) */
            'cols' => array(
                'active' => array(
                    'class' => 'center',
                ),
                'social' => array(
                    'class' => 'center',
                ),
                'phone' => array(
                    'class' => 'center',
                ),
                'status' => array(
                    'class' => 'center',
                ),
                'create_date' => array(
                    'class' => 'center',
                ),
                'lastvisited' => array(
                    'class' => 'center',
                ),
            ),
            /* ปุ่มแสดงในแต่ละแถว */
            'buttons' => array(
                'preview' => array(
                    'class' => 'icon-info button orange notext',
                    'id' => ':id',
                    'title' => '{LNG_Show}',
                ),
                array(
                    'class' => 'icon-edit button green',
                    'href' => $uri->createBackUri(array('module' => 'demo', 'page' => 'form', 'id' => ':id')),
                    'text' => '{LNG_Edit}',
                ),
            ),
        ));
        // save cookie
        setcookie('table_perPage', $table->perPage, time() + 3600 * 24 * 365, '/');
        setcookie('table_sort', $table->sort, time() + 3600 * 24 * 365, '/');
        // คืนค่า HTML
        return $table->render();
    }
}
