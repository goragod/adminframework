<?php
/**
 * @filesource modules/demo/controllers/table.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Demo\Table;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;

/**
 * module=demo-table
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * Controller สำหรับคัดเลือกหน้าของโมดูล demo
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // ข้อความ title bar
        $this->title = 'Data Table';
        // เลือกเมนู
        $this->menu = 'demo';
        // สมาชิก
        if (Login::isMember()) {
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-home">{LNG_Home}</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-table">'.$this->title.'</h2>',
            ));
            // แสดงตาราง
            $section->appendChild(\Demo\Table\View::create()->render($request));
            // คืนค่า HTML
            return $section->render();
        }
        // 404
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
