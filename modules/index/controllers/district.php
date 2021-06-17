<?php
/**
 * @filesource modules/index/controllers/district.php
 *
 * @copyright 2016 Goragod.com
 * @license http://www.kotchasan.com/license/
 *
 * @see http://www.kotchasan.com/
 */

namespace Index\District;

use Gcms\Login;
use Kotchasan\Html;
use Kotchasan\Http\Request;
use Kotchasan\Language;

/**
 * module=district
 *
 * @author Goragod Wiriya <admin@goragod.com>
 *
 * @since 1.0
 */
class Controller extends \Gcms\Controller
{
    /**
     * รายชื่อตำบล
     *
     * @param Request $request
     *
     * @return string
     */
    public function render(Request $request)
    {
        // ข้อความ title bar
        $this->title = Language::trans('{LNG_List of} {LNG_District}');
        // เลือกเมนู
        $this->menu = 'settings';
        // แอดมิน
        if ($login = Login::isAdmin()) {
            // แสดงผล
            $section = Html::create('section', array(
                'class' => 'content_bg',
            ));
            // breadcrumbs
            $breadcrumbs = $section->add('div', array(
                'class' => 'breadcrumbs',
            ));
            $ul = $breadcrumbs->add('ul');
            $ul->appendChild('<li><span class="icon-settings">{LNG_Settings}</span></li>');
            $ul->appendChild('<li><span>{LNG_District}</span></li>');
            $section->add('header', array(
                'innerHTML' => '<h2 class="icon-location">'.$this->title.'</h2>',
            ));
            // menu
            $section->appendChild(\Index\Tabmenus\View::render($request, 'settings', 'district'));
            // แสดงฟอร์ม
            $section->appendChild(\Index\District\View::create()->render($request));
            // คืนค่า HTML
            return $section->render();
        }
        // 404.html
        return \Index\Error\Controller::execute($this, $request->getUri());
    }
}
