<?php
/**
 * @filesource modules/demo/views/multiselect.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Demo\Multiselect;

use Kotchasan\Html;
use Kotchasan\Http\Request;

/**
 * module=demo-multiselect
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class View extends \Gcms\View
{
    /**
     * ตัวอย่างฟอร์ม
     *
     * @return string
     */
    public function render(Request $request)
    {
        /* คำสั่งสร้างฟอร์ม */
        $form = Html::create('form', array(
            'id' => 'setup_frm',
            'class' => 'setup_frm',
            'autocomplete' => 'off',
            /*
             * คลาสรับค่าจากการ submit ปกติแล้วควรจะเป็นชื่อเดียวกันกับ Controller
             * demo/model/multiselect/submit หมายถึงคลาสและเมธอด \Demo\Form\Multiselect::submit()
             */
            'action' => 'index.php/demo/model/multiselect/submit',
            /* ฟังก์ชั่น Javascript (common.js) สำหรับรับค่าที่ตอบกลับจาก Server หลังการ submit */
            'onsubmit' => 'doFormSubmit',
            /* form แบบ Ajax */
            'ajax' => true,
            /* เปิดการใช้งาน Token สำหรับรักษาความปลอดภัยของฟอร์ม */
            'token' => true,
        ));
        /*
         * คำสั่งสร้าง fieldset และ legend สำหรับจัดกลุ่ม input
         * <legend><span>...</span></legend>
         */
        $fieldset = $form->add('fieldset', array(
            'title' => '{LNG_Details of} {LNG_Address}',
        ));
        // province
        $fieldset->add('select', array(
            'id' => 'provinceID',
            'itemClass' => 'item',
            'labelClass' => 'g-input icon-location',
            'label' => '{LNG_Province}',
            /* ข้อมูลรายชื่อจังหวัดทั้งหมด */
            'options' => \Kotchasan\Province::all(),
            'value' => $request->request('provinceID')->toInt(),
        ));
        // amphur
        $fieldset->add('select', array(
            'id' => 'amphurID',
            'itemClass' => 'item',
            'labelClass' => 'g-input icon-location',
            'label' => '{LNG_Amphur}',
            /* สำหรับกำหนดรายการที่เลือกไว้ */
            'options' => array($request->request('amphurID')->toInt() => ''),
            'value' => $request->request('amphurID')->toInt(),
        ));
        // district
        $fieldset->add('select', array(
            'id' => 'districtID',
            'itemClass' => 'item',
            'labelClass' => 'g-input icon-location',
            'label' => '{LNG_District}',
            /* สำหรับกำหนดรายการที่เลือกไว้ */
            'options' => array($request->request('districtID')->toInt() => ''),
            'value' => $request->request('districtID')->toInt(),
        ));
        $fieldset = $form->add('fieldset', array(
            'class' => 'submit',
        ));
        /* ปุ่ม submit */
        $fieldset->add('submit', array(
            'class' => 'button save large',
            'value' => '{LNG_Save}',
        ));
        /* Javascript สำหรับ Multi Select */
        $form->script('initProvince();');
        // คืนค่า HTML
        return $form->render();
    }
}
